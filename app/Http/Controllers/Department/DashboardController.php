<?php

namespace App\Http\Controllers\Department;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index()
    {
        $user = Auth::user();

        // Use eager loading to avoid N+1 queries
        $projects = Project::withBasicRelations()->with('latestUpdate')->withActualTransactionSum()->get();

        $stats = [
            'total_projects'   => $projects->count(),
            'ongoing'          => $projects->whereNotIn('current_status', ['Completed', 'Cancelled', 'On Hold'])->count(),
            'completed'        => $projects->where('current_status', 'Completed')->count(),
            'budget_allocated' => $projects->sum('approved_budget') ?? 0,
            'budget_used'      => $projects->sum(fn (Project $project) => $project->actual_budget_total),
        ];

        // Get recent projects with relations already loaded
        $recentProjects = $projects->sortByDesc('created_at')->take(4);

        return view('department.dashboard', compact('stats', 'recentProjects'));
    }
}
