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
use App\Services\AuditLogService;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Project::class);

        // No need to call ->forUser() anymore — the global scope on
        // the Project model filters this automatically by role.
        $projects = Project::latest('created_at')->get();

        return view('department.projects.index', compact('projects'));
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
            $data['project_image'] = $request->file('project_image')
                ->store('project_images', 'public');
        }

        $project = Project::create($data);

        CacheService::invalidateGeoJsonCache();
        AuditLogService::logCreate($project);

        $notificationPayload = [
            'id' => 'project-created-' . $project->project_id . '-' . time(),
            'title' => 'New Project Added',
            'message' => ($project->project_name ?: 'A new project') . ' has been added to the system.',
            'time' => 'Just now',
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
        $project = Project::with(['barangay', 'updates', 'budgetTransactions'])
            ->findOrFail($id);

        $this->authorize('view', $project);

        return view('department.projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);

        $this->authorize('update', $project);

        $barangays = Barangay::orderBy('barangay_name')->get();

        $latestPermissionRequest = EditPermissionRequest::where('project_id', $project->project_id)
            ->where('requested_by', Auth::id())
            ->latest('created_at')
            ->first();

        $canEditCriticalFields = $latestPermissionRequest?->status === 'approved';

        return view('department.projects.edit', compact('project', 'barangays', 'canEditCriticalFields'));
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::findOrFail($id);

        $this->authorize('update', $project);

        $original = $project->getOriginal();

        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        $lockedFields = ['start_date', 'target_end_date', 'approved_budget', 'actual_budget'];
        $latestPermissionRequest = EditPermissionRequest::where('project_id', $project->project_id)
            ->where('requested_by', Auth::id())
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
            $data['project_image'] = $request->file('project_image')
                ->store('project_images', 'public');
        }

        $project->update($data);

        if ($latestPermissionRequest && $latestPermissionRequest->status === 'approved') {
            $latestPermissionRequest->status = 'used';
            $latestPermissionRequest->used_at = now();
            $latestPermissionRequest->save();
        }

        CacheService::invalidateGeoJsonCache();
        AuditLogService::logUpdate($project, $original);

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

        $this->authorize('delete', $project);

        AuditLogService::logDelete($project);

        $project->delete();

        CacheService::invalidateGeoJsonCache();

        return redirect()
            ->route('department.projects.index')
            ->with('success', 'Project deleted.');
    }

    public function requestEditPermission(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);

        $fieldsRequested = $request->input('fields_requested', ['start_date', 'target_end_date', 'approved_budget', 'actual_budget']);
        $reason = $request->input('reason');

        $permissionRequest = EditPermissionRequest::create([
            'project_id' => $project->project_id,
            'requested_by' => Auth::id(),
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
            'created_at' => now(),
        ]);

        return redirect()->route('department.projects.edit', $project->project_id)
            ->with('permission_requested', true)
            ->with('success', 'Permission request submitted successfully.');
    }
}
