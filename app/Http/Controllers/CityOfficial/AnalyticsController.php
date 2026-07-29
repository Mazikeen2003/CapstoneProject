<?php

namespace App\Http\Controllers\CityOfficial;

use App\Models\PortalVisit;
use App\Models\Project;
use Illuminate\Http\Request;

class AnalyticsController
{
    public function index(Request $request)
    {
        // City sees all projects
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
            'total_projects'  => $projects->count(),
            'completed'       => $projects->where('current_status', 'Completed')->count(),
            'ongoing'         => $projects->where('current_status', 'On Going')->count(),
            'on_hold'         => $projects->where('current_status', 'On Hold')->count(),
            'planning'        => $projects->where('current_status', 'Planning')->count(),
            'total_budget'    => $projects->sum('approved_budget') ?? 0,
            'total_spent'     => $projects->sum('actual_budget') ?? 0,
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

        $portalVisits = PortalVisit::query()->orderBy('visited_at');
        $portalVisitStats = [
            'total_visits' => (clone $portalVisits)->count(),
            'visits_today' => (clone $portalVisits)->today()->count(),
            'visits_this_week' => (clone $portalVisits)->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'visits_this_month' => (clone $portalVisits)->whereBetween('visited_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'estimated_unique_visitors' => (clone $portalVisits)->last30Days()->distinct('ip_address')->count('ip_address'),
        ];

        $dailyVisits = collect();
        $startDate = now()->subDays(29)->startOfDay();
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayKey = $date->toDateString();
            $dayVisits = PortalVisit::query()
                ->whereDate('visited_at', $dayKey)
                ->get()
                ->groupBy('page');

            $dailyVisits->push([
                'date' => $date->format('M d'),
                'map' => $dayVisits->get('map', collect())->count(),
                'analytics' => $dayVisits->get('analytics', collect())->count(),
            ]);
        }

        $pageBreakdown = [
            'map' => PortalVisit::query()->forPage('map')->count(),
            'analytics' => PortalVisit::query()->forPage('analytics')->count(),
        ];

        return view('city-official.analytics.index', compact(
            'stats',
            'byStatus',
            'byBarangay',
            'availableYears',
            'statusYear',
            'budgetYear',
            'budgetStats',
            'portalVisitStats',
            'dailyVisits',
            'pageBreakdown'
        ));
    }
}
