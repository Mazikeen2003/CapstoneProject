<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    /**
     * Generate a comprehensive report dataset with eager loading.
     * Returns an array with project list, summary stats, and metadata.
     */
    public static function generateProjectReport(): array
    {
        $user = Auth::user();
        
        // Eager load all required relations to avoid N+1 queries
        $projects = Project::withRelations()
            ->latest('created_at')
            ->get();

        $totalBudget = $projects->sum('approved_budget') ?? 0;
        $totalSpent = $projects->sum('actual_budget') ?? 0;
        $completedCount = $projects->where('current_status', 'Completed')->count();
        $ongoingCount = $projects->where('current_status', 'On Going')->count();

        $projectData = $projects->map(function (Project $p) {
            return [
                'code'        => $p->project_code,
                'name'        => $p->project_name,
                'type'        => $p->project_type,
                'barangay'    => $p->barangay->barangay_name ?? 'Citywide',
                'status'      => $p->current_status,
                'start_date'  => $p->start_date?->format('M d, Y'),
                'end_date'    => $p->target_end_date?->format('M d, Y'),
                'budget'      => $p->approved_budget ?? 0,
                'spent'       => $p->actual_budget ?? 0,
                'remarks'     => $p->remarks,
                'created_by'  => $p->creator->username ?? 'Unknown',
            ];
        });

        return [
            'title'           => match ($user->role_slug) {
                'department' => 'Department Project Report',
                'city'       => 'Citywide Project Report',
                'barangay'   => 'Barangay ' . ($user->barangay->barangay_name ?? 'Unknown') . ' Report',
                'admin'      => 'System-Wide Project Report',
                default      => 'Project Report',
            },
            'generated_by'    => $user->username,
            'generated_date'  => now()->format('M d, Y H:i A'),
            'total_projects'  => $projects->count(),
            'completed'       => $completedCount,
            'ongoing'         => $ongoingCount,
            'total_budget'    => $totalBudget,
            'total_spent'     => $totalSpent,
            'budget_balance'  => $totalBudget - $totalSpent,
            'projects'        => $projectData,
        ];
    }

    /**
     * Generate budget analysis report with eager loading.
     */
    public static function generateBudgetReport(): array
    {
        $user = Auth::user();
        
        // Eager load relations for efficient grouping
        $projects = Project::withRelations()
            ->latest('created_at')
            ->get();

        $byStatus = $projects->groupBy('current_status')->map(fn($group) => [
            'count'  => $group->count(),
            'budget' => $group->sum('approved_budget'),
            'spent'  => $group->sum('actual_budget'),
        ]);

        $byBarangay = $projects->groupBy(fn($p) => $p->barangay?->barangay_name ?? 'Citywide')
            ->map(fn($group) => [
                'count'  => $group->count(),
                'budget' => $group->sum('approved_budget'),
                'spent'  => $group->sum('actual_budget'),
            ]);

        return [
            'title'         => 'Budget Analysis Report',
            'generated_by'  => $user->username,
            'generated_date' => now()->format('M d, Y H:i A'),
            'by_status'     => $byStatus,
            'by_barangay'   => $byBarangay,
            'total_budget'  => $projects->sum('approved_budget'),
            'total_spent'   => $projects->sum('actual_budget'),
        ];
    }
}