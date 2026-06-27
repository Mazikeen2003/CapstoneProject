<?php

namespace App\Http\Controllers\Department;

use App\Models\Project;

class AnalyticsController
{
    public function index()
    {
        // Get all projects with eager loading
        $projects = Project::withBasicRelations()->get();

        // Calculate statistics
        $stats = [
            'total_projects'  => $projects->count(),
            'completed'       => $projects->where('current_status', 'Completed')->count(),
            'ongoing'         => $projects->where('current_status', 'On Going')->count(),
            'on_hold'         => $projects->where('current_status', 'On Hold')->count(),
            'planning'        => $projects->where('current_status', 'Planning')->count(),
            'total_budget'    => $projects->sum('approved_budget') ?? 0,
            'total_spent'     => $projects->sum('actual_budget') ?? 0,
        ];

        // Budget by status
        $byStatus = $projects->groupBy('current_status')->map(fn($group) => [
            'count'  => $group->count(),
            'budget' => $group->sum('approved_budget'),
            'spent'  => $group->sum('actual_budget'),
        ]);

        // Budget by barangay
        $byBarangay = $projects->groupBy(fn($p) => $p->barangay?->barangay_name ?? 'Unknown')
            ->map(fn($group) => [
                'count'  => $group->count(),
                'budget' => $group->sum('approved_budget'),
            ])
            ->sortByDesc('budget')
            ->take(10);

        return view('department.analytics.index', compact('stats', 'byStatus', 'byBarangay'));
    }
}