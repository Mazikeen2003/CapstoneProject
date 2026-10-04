<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Barangay;
use App\Models\AuditLog;
use App\Models\BudgetTransaction;
use App\Models\EditPermissionRequest;
use App\Models\Project;
use Carbon\Carbon;
use App\Services\AuditLogService;
use App\Services\BackupService;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        // No need to call ->forUser() anymore — the global scope on
        // the Project model filters this automatically by role.
        $query = Project::withBasicRelations();
        $projectListView = $request->query('view') === 'archived' ? 'archived' : 'active';
        if ($projectListView === 'archived') {
            $query->where('current_status', 'Completed');
        } elseif ($request->query('filter') !== 'completed') {
            $query->where(fn ($statusQuery) => $statusQuery->whereNull('current_status')->orWhere('current_status', '!=', 'Completed'));
        }
        $filter = $request->string('filter')->toString();
        $terminalStatuses = ['Completed', 'Cancelled', 'On Hold'];

        match ($filter) {
            'active' => $query->whereNotIn('current_status', $terminalStatuses),
            'completed' => $query->where('current_status', 'Completed'),
            'on_hold' => $query->where('current_status', 'On Hold'),
            'overdue' => $query->whereNotNull('target_end_date')->whereDate('target_end_date', '<', today())->whereNotIn('current_status', $terminalStatuses),
            'due_soon' => $query->whereNotNull('target_end_date')->whereBetween('target_end_date', [today(), today()->copy()->addDays(30)])->whereNotIn('current_status', $terminalStatuses),
            'missing_updates' => $query->whereNotIn('current_status', $terminalStatuses)->whereDoesntHave('latestUpdate'),
            default => null,
        };

        match ($request->query('sort')) {
            'approved_budget' => $query->orderByDesc('approved_budget'),
            'actual_budget' => $query->orderByDesc('actual_budget'),
            default => $query->latest('created_at'),
        };

        $projects = $query->withActualTransactionSum()->paginate(10)->withQueryString();
        $deletePermissionRequests = EditPermissionRequest::query()
            ->where('requested_by', Auth::id())
            ->where('request_type', 'delete')
            ->whereIn('status', ['pending', 'approved'])
            ->get()
            ->keyBy('project_id');

        return view('department.projects.index', compact('projects', 'deletePermissionRequests', 'projectListView'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        $barangays = Barangay::orderBy('barangay_name')->get();

        return view('department.projects.create', compact('barangays'));
    }

    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $data = $request->validated();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('project_image')) {
            $data['project_image'] = $this->storeProjectImage($request->file('project_image'));
        }

        $project = Project::create($data);

        CacheService::invalidateGeoJsonCache();
        AuditLogService::logCreate($project);
        BackupService::createBackup('project_create', Auth::id());

        $notificationPayload = [
            'id' => 'project-created-' . $project->project_id . '-' . time(),
            'title' => 'New Project Added',
            'message' => ($project->project_name ?: 'A new project') . ' has been added to the system.',
            'time' => now()->toIso8601String(),
            'type' => 'project_created',
            'project_id' => $project->project_id,
        ];

        if (! empty($data['approved_budget']) && $data['approved_budget'] > 0) {
            BudgetTransaction::create([
                'project_id'       => $project->project_id,
                'action'           => 'initial_budget',
                'amount'           => $data['approved_budget'],
                'transaction_type' => 'approved_budget',
                'description'      => 'Initial approved budget set on project creation.',
                'user_id'          => Auth::id(),
                'created_at'       => now(),
            ]);
        }

        return redirect()
            ->route('department.projects.show', $project->project_id)
            ->with('success', 'Project created successfully.')
            ->with('pending_notification', $notificationPayload);
    }

    public function show($id)
    {
        // findOrFail already respects the global scope, so a department
        // user can't even fetch another department's project by guessing
        // the ID — it'll 404 before the policy check even runs.
        $project = Project::with(['barangay', 'latestUpdate', 'updates.user', 'budgetTransactions', 'stageDocuments.uploader'])
            ->findOrFail($id);

        $this->authorize('view', $project);

        return view('department.projects.show', [
            'project' => $project,
            'projectRoutePrefix' => 'department.projects',
            'budgetTrackingAvailable' => BudgetTransaction::supportsCategoryTracking(),
        ]);
    }

    public function storeBudgetTransaction(Request $request, $id)
    {
        if (! BudgetTransaction::supportsCategoryTracking()) {
            return back()->with('error', 'Category budget tracking is unavailable until its database migration has been applied.');
        }

        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        $validated = $request->validate([
            'category' => ['required', Rule::in(BudgetTransaction::CATEGORIES)],
            'type' => ['required', Rule::in(BudgetTransaction::TYPES)],
            'amount' => ['required', 'numeric', 'gt:0', 'max:999999999999.99'],
            'description' => ['nullable', 'string', 'max:2000'],
            'period' => ['required', 'date_format:Y-m'],
        ]);

        $period = Carbon::createFromFormat('!Y-m', $validated['period']);
        if ($period->greaterThan(today()->startOfMonth())) {
            throw ValidationException::withMessages([
                'period' => 'Budget entries can only be recorded for the current or a previous month.',
            ]);
        }

        $implementationStart = $project->start_date;
        $transactionDate = $validated['type'] === 'planned'
            && $implementationStart
            && $implementationStart->isSameMonth($period)
                ? $implementationStart->toDateString()
                : ($validated['type'] === 'planned'
                    ? $period->copy()->startOfMonth()->toDateString()
                    : $period->copy()->endOfMonth()->toDateString());

        DB::transaction(function () use ($validated, $project, $period, $transactionDate): void {
            $lockedProject = Project::where('project_id', $project->project_id)->lockForUpdate()->firstOrFail();
            $transactions = $lockedProject->budgetTransactions()
                ->whereIn('category', BudgetTransaction::CATEGORIES)
                ->whereIn('type', BudgetTransaction::TYPES)
                ->get();
            $plannedTransactions = $transactions->where('type', 'planned');
            $actualTransactions = $transactions->where('type', 'actual');
            $inPeriod = static fn ($transaction): bool => $transaction->transaction_date?->format('Y-m') === $period->format('Y-m');
            $monthlyPlanned = $plannedTransactions->filter($inPeriod);
            $existingEntry = $transactions->first(
                fn ($transaction) => $transaction->category === $validated['category']
                    && $transaction->type === $validated['type']
                    && $inPeriod($transaction)
            );
            $existingAmount = (float) ($existingEntry?->amount ?? 0);
            $amount = (float) $validated['amount'];
            $currentSpent = max((float) ($lockedProject->actual_budget ?? 0), (float) $actualTransactions->sum('amount'));

            if ($validated['type'] === 'planned') {
                $approvedBudget = $lockedProject->approved_budget !== null ? (float) $lockedProject->approved_budget : null;
                $newPlannedTotal = (float) $plannedTransactions->sum('amount')
                    - $existingAmount
                    + $amount;
                $monthlyActualForCategory = (float) $actualTransactions
                    ->filter($inPeriod)
                    ->where('category', $validated['category'])
                    ->sum('amount');

                if ($monthlyActualForCategory > $amount) {
                    throw ValidationException::withMessages([
                        'amount' => 'The monthly planned amount cannot be lower than the actual expenditure already recorded for this category.',
                    ]);
                }

                if ($approvedBudget !== null && $newPlannedTotal > $approvedBudget) {
                    throw ValidationException::withMessages([
                        'amount' => 'Planned category amounts cannot exceed the approved budget of ₱' . number_format($approvedBudget, 2) . '.',
                    ]);
                }
            } else {
                $categoryPlanned = $monthlyPlanned->where('category', $validated['category']);
                if ($categoryPlanned->isEmpty()) {
                    throw ValidationException::withMessages([
                        'amount' => 'Record the monthly planned amount for ' . $validated['category'] . ' before entering actual expenditure.',
                    ]);
                }

                $categoryLimit = (float) $categoryPlanned->sum('amount');
                if ($amount > $categoryLimit) {
                    throw ValidationException::withMessages([
                        'amount' => 'This expenditure would exceed the monthly planned ' . $validated['category'] . ' amount of ₱' . number_format($categoryLimit, 2) . '.',
                    ]);
                }

                $approvedBudget = $lockedProject->approved_budget !== null ? (float) $lockedProject->approved_budget : null;
                $plannedBudget = $plannedTransactions->isNotEmpty() ? (float) $plannedTransactions->sum('amount') : null;
                $projectLimit = match (true) {
                    $approvedBudget !== null && $plannedBudget !== null => min($approvedBudget, $plannedBudget),
                    $plannedBudget !== null => $plannedBudget,
                    default => $approvedBudget,
                };

                $newSpent = $currentSpent - $existingAmount + $amount;
                if ($projectLimit !== null && $newSpent > $projectLimit) {
                    throw ValidationException::withMessages([
                        'amount' => 'This expenditure would exceed the project spending limit of ₱' . number_format($projectLimit, 2) . '.',
                    ]);
                }
            }

            $entryData = [
                'category' => $validated['category'],
                'type' => $validated['type'],
                'amount' => $amount,
                'transaction_date' => $transactionDate,
                'description' => $validated['description'] ?? null,
                'action' => $validated['type'] === 'planned' ? 'category_budget' : 'expenditure',
                'transaction_type' => $validated['type'],
                'user_id' => Auth::id(),
                'created_at' => now(),
            ];
            if ($existingEntry) {
                $existingEntry->update($entryData);
            } else {
                $lockedProject->budgetTransactions()->create($entryData);
            }

            if ($validated['type'] === 'actual') {
                $previousCategorizedSpent = (float) $actualTransactions->sum('amount');
                $unclassifiedSpent = max(0, (float) ($lockedProject->actual_budget ?? 0) - $previousCategorizedSpent);
                $updatedCategorizedSpent = $previousCategorizedSpent
                    - $existingAmount
                    + $amount;
                $lockedProject->forceFill(['actual_budget' => $unclassifiedSpent + $updatedCategorizedSpent])->save();
            }
        });

        CacheService::invalidateGeoJsonCache();

        return redirect()
            ->route('department.projects.show', $project->project_id)
            ->with('budget_success', 'Budget entry recorded successfully.');
    }

    public function edit($id)
    {
        $project = Project::with(['barangay', 'latestUpdate', 'budgetTransactions'])->findOrFail($id);

        if (Auth::user()?->cannot('update', $project)) {
            abort(403, 'This action is unauthorized.');
        }

        $barangays = Barangay::orderBy('barangay_name')->get();

        $latestPermissionRequest = EditPermissionRequest::where('project_id', $project->project_id)
            ->where('requested_by', Auth::id())
            ->where('request_type', 'edit')
            ->latest('created_at')
            ->first();

        $canEditCriticalFields = $latestPermissionRequest?->status === 'approved';
        $canRequestPermission = ! $latestPermissionRequest || in_array($latestPermissionRequest->status, ['rejected', 'used'], true);
        $actualBudgetTotal = $project->actual_budget_total;

        return view('department.projects.edit', compact('project', 'barangays', 'canEditCriticalFields', 'canRequestPermission', 'actualBudgetTotal'));
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::findOrFail($id);

        if (Auth::user()?->cannot('update', $project)) {
            abort(403, 'This action is unauthorized.');
        }

        $original = $project->getOriginal();

        $data = $request->validated();
        unset($data['actual_budget']);
        $data['updated_by'] = Auth::id();

        $actualBudgetChanged = array_key_exists('actual_budget', $data)
            && (float) ($data['actual_budget'] ?? 0) !== (float) ($original['actual_budget'] ?? 0);
        $approvedBudgetChanged = array_key_exists('approved_budget', $data)
            && (float) ($data['approved_budget'] ?? 0) !== (float) ($original['approved_budget'] ?? 0);
        $finalActualBudget = array_key_exists('actual_budget', $data) ? $data['actual_budget'] : ($original['actual_budget'] ?? null);
        $finalApprovedBudget = array_key_exists('approved_budget', $data) ? $data['approved_budget'] : ($original['approved_budget'] ?? null);

        if (($actualBudgetChanged || $approvedBudgetChanged)
            && $finalActualBudget !== null
            && $finalApprovedBudget !== null
            && (float) $finalActualBudget > (float) $finalApprovedBudget) {
            return back()->withErrors([
                'actual_budget' => 'Actual expenditure cannot exceed the approved budget of ₱' . number_format((float) $finalApprovedBudget, 2) . '.',
            ])->withInput();
        }

        if (! $project->hasStarted()) {
            foreach (['current_status', 'lifecycle_stage', 'actual_budget'] as $field) {
                if (! array_key_exists($field, $data)) {
                    continue;
                }

                $hasChanged = $field === 'actual_budget'
                    ? (float) ($data[$field] ?? 0) !== (float) ($original[$field] ?? 0)
                    : $data[$field] !== ($original[$field] ?? null);

                if ($hasChanged) {
                    return back()->withErrors([
                        $field => 'Project lifecycle, progress, and expenditure updates are unavailable until the project start date.',
                    ])->withInput();
                }
            }
        }

        $lockedFields = ['start_date', 'target_end_date', 'approved_budget'];
        $latestPermissionRequest = EditPermissionRequest::where('project_id', $project->project_id)
            ->where('requested_by', Auth::id())
            ->where('request_type', 'edit')
            ->latest('created_at')
            ->first();

        if ($latestPermissionRequest && $latestPermissionRequest->status === 'pending') {
            foreach ($lockedFields as $field) {
                if (array_key_exists($field, $data) && $data[$field] !== ($original[$field] ?? null)) {
                    return back()->withErrors([
                        $field => 'This field is still locked pending admin approval.',
                    ])->withInput();
                }
            }
        }

        if ($request->hasFile('project_image')) {
            if ($project->project_image) {
                Storage::disk('public')->delete($project->project_image);
            }
            $data['project_image'] = $this->storeProjectImage($request->file('project_image'));
        }

        $project->update($data);

        if ($latestPermissionRequest && $latestPermissionRequest->status === 'approved') {
            $latestPermissionRequest->status = 'used';
            $latestPermissionRequest->used_at = now();
            $latestPermissionRequest->save();
        }

        CacheService::invalidateGeoJsonCache();
        AuditLogService::logUpdate($project, $original);
        BackupService::createBackup('project_update', Auth::id());

        foreach (['approved_budget', 'actual_budget'] as $field) {
            if (array_key_exists($field, $data) && (float) ($original[$field] ?? 0) !== (float) $data[$field]) {
                BudgetTransaction::create([
                    'project_id'       => $project->project_id,
                    'action'           => 'budget_adjustment',
                    'amount'           => $data[$field],
                    'transaction_type' => $field,
                    'description'      => sprintf(
                        '%s changed from %s to %s',
                        $field,
                        number_format((float) ($original[$field] ?? 0), 2),
                        number_format((float) $data[$field], 2)
                    ),
                    'user_id'    => Auth::id(),
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()
            ->route('department.projects.show', $project->project_id)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $user = Auth::user();

        if ($user?->cannot('delete', $project)) {
            abort(403, 'This action is unauthorized.');
        }

        if (! $user->isDepartmentHead()) {
            $permissionRequest = EditPermissionRequest::query()
                ->where('project_id', $project->project_id)
                ->where('requested_by', $user->user_id)
                ->where('request_type', 'delete')
                ->where('status', 'approved')
                ->latest('created_at')
                ->first();

            abort_unless($permissionRequest, 403, 'Department Head approval is required before deleting this project.');
            $permissionRequest->update(['status' => 'used', 'used_at' => now()]);
        }

        AuditLogService::logDelete($project);
        BackupService::createBackup('project_delete', Auth::id());

        $project->delete();

        CacheService::invalidateGeoJsonCache();

        return redirect()
            ->route('department.projects.index')
            ->with('success', 'Project deleted.');
    }

    public function requestDeletePermission(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('delete', $project);

        if (Auth::user()->isDepartmentHead()) {
            return redirect()->route('department.projects.index')->with('info', 'Department heads can delete projects directly.');
        }

        $existingRequest = EditPermissionRequest::query()
            ->where('project_id', $project->project_id)
            ->where('requested_by', Auth::id())
            ->where('request_type', 'delete')
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existingRequest) {
            return redirect()->route('department.projects.index')->with('info', 'A deletion request is already pending or approved for this project.');
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        EditPermissionRequest::create([
            'project_id' => $project->project_id,
            'requested_by' => Auth::id(),
            'request_type' => 'delete',
            'fields_requested' => ['delete_project'],
            'reason' => $validated['reason'] ?? 'Department staff requested project deletion.',
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_permission_requested',
            'table_name' => 'projects',
            'record_id' => $project->project_id,
            'old_values' => null,
            'new_values' => ['request_type' => 'delete', 'status' => 'pending'],
            'full_name' => Auth::user()?->full_name ?: Auth::user()?->username,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('department.projects.index')->with('success', 'Deletion request sent to the department head for approval.');
    }

    private function storeProjectImage($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::uuid()->toString() . '.' . $extension;

        return $file->storeAs('project_images', $filename, 'public');
    }

    public function requestEditPermission(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        if (Auth::user()?->cannot('update', $project)) {
            abort(403, 'This action is unauthorized.');
        }

        $fieldsRequested = $request->input('fields_requested', ['start_date', 'target_end_date', 'approved_budget']);
        $reason = $request->input('reason');

        $permissionRequest = EditPermissionRequest::create([
            'project_id' => $project->project_id,
            'requested_by' => Auth::id(),
            'request_type' => 'edit',
            'fields_requested' => $fieldsRequested,
            'reason' => $reason,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'permission_requested',
            'table_name' => 'edit_permission_requests',
            'record_id' => $permissionRequest->request_id,
            'old_values' => null,
            'new_values' => [
                'project_id' => $project->project_id,
                'fields_requested' => $fieldsRequested,
                'reason' => $reason,
            ],
            'full_name' => Auth::user()?->full_name ?: Auth::user()?->username,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('department.projects.edit', $project->project_id)
            ->with('permission_requested', true)
            ->with('success', 'Permission request submitted successfully.');
    }
}
