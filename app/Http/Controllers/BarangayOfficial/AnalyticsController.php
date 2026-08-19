<?php

namespace App\Http\Controllers\BarangayOfficial;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\AnalyticsInsightsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Barangay sees only their projects (global scope applied)
        $projects = Project::withBasicRelations()->with('latestUpdate')->get();
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
            'ongoing'         => $projects->whereIn('current_status', ['Implementation', 'On Going'])->count(),
            'on_hold'         => $projects->where('current_status', 'On Hold')->count(),
            'planning'        => $projects->whereIn('current_status', ['Proposed', 'Planning'])->count(),
            'total_budget'    => $projects->sum('approved_budget') ?? 0,
            'total_spent'     => $projects->sum('actual_budget') ?? 0,
        ];

        $byStatus = $statusProjects->groupBy('current_status')->map(fn($group) => [
            'count'  => $group->count(),
            'budget' => $group->sum('approved_budget'),
            'spent'  => $group->sum('actual_budget'),
        ]);

        $budgetStats = ['total_budget' => $budgetProjects->sum('approved_budget') ?? 0, 'total_spent' => $budgetProjects->sum('actual_budget') ?? 0];
        $insights = AnalyticsInsightsService::summarize($projects);

        return view('barangay-official.analytics.index', compact('stats', 'byStatus', 'availableYears', 'statusYear', 'budgetYear', 'budgetStats', 'insights'));
    }
}
