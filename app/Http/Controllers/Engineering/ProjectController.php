<?php

namespace App\Http\Controllers\Engineering;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Services\AuditLogService;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $query = Project::withoutRoleScope()->withBasicRelations();
        $terminalStatuses = ['Completed', 'Cancelled', 'On Hold'];

        match ($request->query('filter')) {
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

        $projects = $query->paginate(10)->withQueryString();

        return view('engineering.projects.index', compact('projects'));
    }

    public function show($id)
    {
        $project = Project::withoutRoleScope()
            ->with(['barangay', 'latestUpdate', 'updates', 'budgetTransactions', 'forms'])
            ->findOrFail($id);

        $this->authorize('view', $project);

        return view('department.projects.show', [
            'project' => $project,
            'projectRoutePrefix' => 'engineering.projects',
        ]);
    }

    public function updateProgress(Request $request, $id)
    {
        $project = Project::withoutRoleScope()->findOrFail($id);
        $this->authorize('updateForms', $project);

        if (! $project->hasReachedImplementationStage()) {
            return back()->with('error', 'Progress updates are available once the project reaches the Implementation stage.');
        }

        $validated = $request->validate([
            'update_date' => ['required', 'date'],
            'progress_percentage' => ['required', 'numeric', 'between:0,100'],
            'status' => ['nullable', 'string', 'in:Proposed,For bidding,Bidding ongoing,Award of contract,Implementation,Completed,Planning,On Going,On Hold,Cancelled,Bidding - Success,Bidding - Failed,Procurement'],
            'remarks' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('progress_updates', 'public');
        }
        unset($validated['image']);

        $update = ProjectUpdate::create([
            ...$validated,
            'project_id' => $project->project_id,
            'user_id' => Auth::id(),
        ]);

        AuditLogService::logCreate($update);
        CacheService::invalidateGeoJsonCache();

        return redirect()
            ->route('engineering.projects.show', $project->project_id)
            ->with('success', 'Project progress updated successfully.');
    }
}
