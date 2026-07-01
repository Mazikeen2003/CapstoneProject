<?php

namespace App\Http\Controllers\CityOfficial;

use App\Models\Project;

class AnalyticsController
{
    public function index()
    {
        // City sees all projects
        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->get();

        $stats = [
            'total_projects'  => $projects->count(),
            'completed'       => $projects->where('current_status', 'Completed')->count(),
            'ongoing'         => $projects->where('current_status', 'On Going')->count(),
            'on_hold'         => $projects->where('current_status', 'On Hold')->count(),
            'planning'        => $projects->where('current_status', 'Planning')->count(),
            'total_budget'    => $projects->sum('approved_budget') ?? 0,
            'total_spent'     => $projects->sum('actual_budget') ?? 0,
        ];

        $byStatus = $projects->groupBy('current_status')->map(fn($group) => [
            'count'  => $group->count(),
            'budget' => $group->sum('approved_budget'),
            'spent'  => $group->sum('actual_budget'),
        ]);

        $byBarangay = $projects->groupBy(fn($p) => $p->barangay?->barangay_name ?? 'Unknown')
            ->map(fn($group) => [
                'count'  => $group->count(),
                'budget' => $group->sum('approved_budget'),
            ])
            ->sortByDesc('budget');

        return view('city-official.analytics.index', compact('stats', 'byStatus', 'byBarangay'));
    }
}