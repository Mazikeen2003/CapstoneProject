<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::withoutRoleScope()
            ->withBasicRelations()
            ->get();
        $availableYears = collect(range(now()->year, 2000));
        $statusYear = $request->query('status_year');
        $budgetYear = $request->query('budget_year');
        $statusYear = preg_match('/^\\d{4}$/', (string) $statusYear) ? $statusYear : null;
        $budgetYear = preg_match('/^\\d{4}$/', (string) $budgetYear) ? $budgetYear : null;
        $statusProjects = $statusYear ? $projects->filter(fn ($project) => $project->start_date?->year === (int) $statusYear) : $projects;
        $budgetProjects = $budgetYear ? $projects->filter(fn ($project) => $project->start_date?->year === (int) $budgetYear) : $projects;

        $stats = [
            'total_projects' => $projects->count(),
            'completed'      => $projects->where('current_status', 'Completed')->count(),
            'ongoing'        => $projects->where('current_status', 'On Going')->count(),
            'on_hold'        => $projects->where('current_status', 'On Hold')->count(),
            'planning'       => $projects->where('current_status', 'Planning')->count(),
            'total_budget'   => $projects->sum('approved_budget') ?? 0,
            'total_spent'    => $projects->sum('actual_budget') ?? 0,
        ];

        $byStatus = $statusProjects->groupBy('current_status')->map(fn($group) => [
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

        $budgetStats = ['total_budget' => $budgetProjects->sum('approved_budget') ?? 0, 'total_spent' => $budgetProjects->sum('actual_budget') ?? 0];

        return view('public.analytics', compact('stats', 'byStatus', 'byBarangay', 'availableYears', 'statusYear', 'budgetYear', 'budgetStats'));
    }
}
