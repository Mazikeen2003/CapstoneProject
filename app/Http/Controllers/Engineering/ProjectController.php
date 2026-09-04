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
    public function index()
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->latest('created_at')
            ->get();

        return view('engineering.projects.index', compact('projects'));
    }

    public function show($id)
    {
        $project = Project::withoutRoleScope()
            ->with(['barangay', 'updates', 'budgetTransactions', 'forms'])
            ->findOrFail($id);

        $this->authorize('view', $project);

        return view('engineering.projects.show', compact('project'));
    }

    public function updateProgress(Request $request, $id)
    {
        $project = Project::withoutRoleScope()->findOrFail($id);
        $this->authorize('updateForms', $project);

        $validated = $request->validate([
            'update_date' => ['required', 'date'],
            'progress_percentage' => ['required', 'integer', 'between:0,100'],
            'status' => ['nullable', 'string', 'in:Proposed,For bidding,Bidding ongoing,Award of contract,Implementation,Completed,Planning,On Going,On Hold,Cancelled,Bidding - Success,Bidding - Failed,Procurement'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

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
