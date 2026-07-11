<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;
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

    public static function generateSystemUsageReport(): array
    {
        $user = Auth::user();

        $totalUsers = User::count();
        $usersByRole = User::with('role')
            ->get()
            ->groupBy(fn($u) => $u->role?->role_name ?? 'Unknown')
            ->map(fn($g) => $g->count());

        $activeUserIds = AuditLog::distinct()->pluck('user_id')->filter()->all();
        $activeUsersByRole = User::with('role')
            ->whereIn('user_id', $activeUserIds)
            ->get()
            ->groupBy(fn($u) => $u->role?->role_name ?? 'Unknown')
            ->map(fn($g) => $g->count());

        return [
            'title' => 'System Usage Report',
            'generated_by' => $user->username,
            'generated_date' => now()->format('M d, Y H:i A'),
            'total_users' => $totalUsers,
            'users_by_role' => $usersByRole,
            'active_users_by_role' => $activeUsersByRole,
        ];
    }

    public static function generateActivitySummaryReport(): array
    {
        $user = Auth::user();

        $auditStats = AuditLog::selectRaw('action, count(*) as total')
            ->groupBy('action')
            ->orderByDesc('total')
            ->get();

        $topUsers = AuditLog::with('user')
            ->selectRaw('user_id, count(*) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $peakUsage = AuditLog::where('created_at', '>=', now()->subDay())
            ->selectRaw('HOUR(created_at) as hour, count(*) as total')
            ->groupBy('hour')
            ->orderByDesc('total')
            ->get();

        return [
            'title' => 'Activity Summary Report',
            'generated_by' => $user->username,
            'generated_date' => now()->format('M d, Y H:i A'),
            'audit_stats' => $auditStats,
            'top_users' => $topUsers,
            'peak_usage' => $peakUsage,
        ];
    }

    public static function generateDataQualityReport(): array
    {
        $user = Auth::user();
        $projects = Project::withoutRoleScope();

        $dataQuality = [
            'incomplete_projects' => (clone $projects)->where(function ($query) {
                $query->whereNull('project_name')->orWhere('project_name', '')
                    ->orWhereNull('project_type')->orWhere('project_type', '')
                    ->orWhereNull('current_status')->orWhere('current_status', '');
            })->count(),
            'missing_coordinates' => (clone $projects)->where(fn($q) => $q->whereNull('latitude')->orWhereNull('longitude'))->count(),
            'missing_budget' => (clone $projects)->where(function ($query) {
                $query->whereNull('approved_budget')->orWhere('approved_budget', 0)
                    ->orWhereNull('actual_budget')->orWhere('actual_budget', 0);
            })->count(),
            'orphaned_projects' => (clone $projects)->where(function ($query) {
                $query->whereNotNull('barangay_id')->whereDoesntHave('barangay');
            })->orWhere(function ($query) {
                $query->whereNotNull('created_by')->whereDoesntHave('creator');
            })->count(),
        ];

        return [
            'title' => 'Data Quality Report',
            'generated_by' => $user->username,
            'generated_date' => now()->format('M d, Y H:i A'),
            'data_quality' => $dataQuality,
        ];
    }

    public static function generateTechnicalComplianceReport(): array
    {
        $user = Auth::user();
        $dq = self::generateDataQualityReport()['data_quality'];

        $technicalMetrics = [
            'total_audit_logs' => AuditLog::count(),
            'projects_with_validation_issues' => $dq['incomplete_projects'] + $dq['missing_budget'],
            'recent_audit_count' => AuditLog::where('created_at', '>=', now()->subDays(30))->count(),
            'recent_project_updates' => ProjectUpdate::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return [
            'title' => 'Technical / Compliance Report',
            'generated_by' => $user->username,
            'generated_date' => now()->format('M d, Y H:i A'),
            'technical_metrics' => $technicalMetrics,
        ];
    }
}
