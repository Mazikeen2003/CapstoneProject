<?php

namespace App\Http\Controllers\CityOfficial;

use App\Models\Project;

class DashboardController
{
    public function index()
    {
        // City official sees ALL projects (no role scope filter applied)
        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->withActualTransactionSum()
            ->get();

        $stats = [
            'total_projects'   => $projects->count(),
            'ongoing'          => $projects->whereNotIn('current_status', ['Completed', 'Cancelled', 'On Hold'])->count(),
            'completed'        => $projects->where('current_status', 'Completed')->count(),
            'budget_allocated' => $projects->sum('approved_budget') ?? 0,
            'budget_used'      => $projects->sum(fn (Project $project) => $project->actual_budget_total),
        ];

        $recentProjects = $projects->sortByDesc('created_at')->take(4);

        return view('city-official.dashboard', compact('stats', 'recentProjects'));
    }
}
