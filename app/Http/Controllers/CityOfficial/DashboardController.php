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
            ->get();

        $stats = [
            'total_projects'   => $projects->count(),
            'ongoing'          => $projects->where('current_status', 'On Going')->count(),
            'completed'        => $projects->where('current_status', 'Completed')->count(),
            'budget_allocated' => $projects->sum('approved_budget') ?? 0,
            'budget_used'      => $projects->sum('actual_budget') ?? 0,
        ];

        $recentProjects = $projects->sortByDesc('created_at')->take(5);

        return view('city-official.dashboard', compact('stats', 'recentProjects'));
    }
}