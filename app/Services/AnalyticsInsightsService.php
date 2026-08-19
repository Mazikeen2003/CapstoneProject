<?php

namespace App\Services;

use Illuminate\Support\Collection;

class AnalyticsInsightsService
{
    public static function summarize(Collection $projects): array
    {
        $today = now()->startOfDay();
        $activeProjects = $projects->reject(fn ($project) => in_array($project->current_status, ['Completed', 'Cancelled'], true));
        $lifecycleAliases = [
            'Planning' => 'Proposed',
            'Procurement' => 'For bidding',
            'Bidding - Success' => 'Award of contract',
            'On Going' => 'Implementation',
        ];
        $lifecycleCounts = $projects->countBy(fn ($project) => $lifecycleAliases[$project->current_status] ?? $project->current_status);
        $totalBudget = (float) $projects->sum('approved_budget');
        $totalSpent = (float) $projects->sum('actual_budget');

        return [
            'lifecycle_counts' => $lifecycleCounts,
            'overdue' => $activeProjects->filter(fn ($project) => $project->target_end_date && $project->target_end_date->lt($today))->count(),
            'due_soon' => $activeProjects->filter(fn ($project) => $project->target_end_date && $project->target_end_date->between($today, $today->copy()->addDays(30)))->count(),
            'without_updates' => $activeProjects->filter(fn ($project) => ! $project->latestUpdate)->count(),
            'budget_utilization' => $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 1) : 0,
        ];
    }
}