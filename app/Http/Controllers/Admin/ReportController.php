<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\Report;
use App\Models\User;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $totalUsers = User::count();
        $usersByRole = User::with('role')
            ->get()
            ->groupBy(fn($user) => $user->role?->role_name ?? 'Unknown')
            ->map(fn($group) => $group->count());

        $activeUserIds = AuditLog::distinct()->pluck('user_id')->filter()->all();
        $activeUsersByRole = User::with('role')
            ->whereIn('user_id', $activeUserIds)
            ->get()
            ->groupBy(fn($user) => $user->role?->role_name ?? 'Unknown')
            ->map(fn($group) => $group->count());

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

        $projects = Project::withoutRoleScope();

        $dataQuality = [
            'incomplete_projects' => $projects->where(function ($query) {
                $query->whereNull('project_name')
                    ->orWhere('project_name', '')
                    ->orWhereNull('project_type')
                    ->orWhere('project_type', '')
                    ->orWhereNull('current_status')
                    ->orWhere('current_status', '');
            })->count(),
            'missing_coordinates' => $projects->whereNull('latitude')->orWhereNull('longitude')->count(),
            'missing_budget' => $projects->where(function ($query) {
                $query->whereNull('approved_budget')
                    ->orWhere('approved_budget', 0)
                    ->orWhereNull('actual_budget')
                    ->orWhere('actual_budget', 0);
            })->count(),
            'orphaned_projects' => $projects->where(function ($query) {
                $query->whereNotNull('barangay_id')->whereDoesntHave('barangay');
            })->orWhere(function ($query) {
                $query->whereNotNull('created_by')->whereDoesntHave('creator');
            })->count(),
        ];

        $technicalMetrics = [
            'total_audit_logs' => AuditLog::count(),
            'projects_with_validation_issues' => $dataQuality['incomplete_projects'] + $dataQuality['missing_budget'],
            'recent_audit_count' => AuditLog::where('created_at', '>=', now()->subDays(30))->count(),
            'recent_project_updates' => ProjectUpdate::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        $reportHistory = Report::orderByDesc('created_at')->paginate(10);

        return view('admin.reports.index', [
            'totalUsers' => $totalUsers,
            'usersByRole' => $usersByRole,
            'activeUsersByRole' => $activeUsersByRole,
            'auditStats' => $auditStats,
            'topUsers' => $topUsers,
            'peakUsage' => $peakUsage,
            'dataQuality' => $dataQuality,
            'technicalMetrics' => $technicalMetrics,
            'reportHistory' => $reportHistory,
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => ['required', 'in:system_usage,activity_summary,data_quality,technical_compliance,budget,project'],
        ]);

        $this->authorize('viewAny', \App\Models\Project::class);

        $type = $request->input('report_type');

        [$reportView, $reportMethod] = match ($type) {
            'system_usage' => ['reports.system-usage-pdf', 'generateSystemUsageReport'],
            'activity_summary' => ['reports.activity-summary-pdf', 'generateActivitySummaryReport'],
            'data_quality' => ['reports.data-quality-pdf', 'generateDataQualityReport'],
            'technical_compliance' => ['reports.technical-compliance-pdf', 'generateTechnicalComplianceReport'],
            'budget' => ['reports.budget-pdf', 'generateBudgetReport'],
            'project' => ['reports.projects-pdf', 'generateProjectReport'],
        };

        $reportData = ReportService::{$reportMethod}();
        $filename = sprintf('admin_%s_report_%s.pdf', $type, now()->format('Y-m-d_His'));

        $report = Report::create([
            'report_type' => $type,
            'title' => $reportData['title'] ?? ucfirst($type) . ' Report',
            'generated_by_user_id' => Auth::id(),
            'generated_by_username' => Auth::user()?->username,
            'status' => 'pending',
            'snapshot' => $reportData,
        ]);

        $pdf = Pdf::loadView($reportView, $reportData);
        $path = 'reports/' . $filename;
        Storage::disk('local')->put($path, $pdf->output());

        $report->update([
            'status' => 'completed',
            'pdf_path' => $path,
        ]);

        return redirect()->route('admin.reports.index')->with('status', 'Report generated and saved to history.');
    }

    public function download(Report $report)
    {
        $this->authorize('viewAny', \App\Models\Project::class);

        if (! $report->pdf_path || ! Storage::disk('local')->exists($report->pdf_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($report->pdf_path, basename($report->pdf_path));
    }
}
