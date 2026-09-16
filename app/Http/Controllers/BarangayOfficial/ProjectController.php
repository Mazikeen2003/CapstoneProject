<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $query = Project::withBasicRelations();
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

        return view('barangay-official.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        // Eager load all relations
        $project = Project::withRelations()
            ->findOrFail($project->project_id);

        return view('barangay-official.projects.show', compact('project'));
    }
}
