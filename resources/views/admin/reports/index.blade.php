@extends('layouts.admin')

@section('content')
<style>
.admin-reports {
    --report-bg: #f4f4f5;
    --report-card-body: #ffffff;
    --report-card-border: rgba(0, 0, 0, 0.06);
    --report-surface: #ffffff;
    --report-ink: #0f0d1f;
    --report-muted: #6b7280;
    --report-line: rgba(0, 0, 0, 0.06);
    --report-line-strong: rgba(0, 0, 0, 0.1);
    --report-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.04);
    --report-indigo: #4338ca;
    --report-gold: #f59e0b;
    --report-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
html.dark-mode .admin-reports {
    --report-bg: #0f0e1a;
    --report-card-body: #141321;
    --report-card-border: rgba(255, 255, 255, 0.06);
    --report-surface: #141321;
    --report-ink: #f8f7f5;
    --report-muted: #94a3b8;
    --report-line: rgba(255, 255, 255, 0.06);
    --report-line-strong: rgba(255, 255, 255, 0.1);
    --report-shadow: 0 1px 3px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1);
}
.admin-reports .admin-reports-card {
    position: relative;
    overflow: hidden;
    background-color: var(--report-card-body) !important;
    border-color: var(--report-card-border) !important;
    border-radius: 20px !important;
    box-shadow: var(--report-shadow) !important;
}
.admin-reports-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--report-gold), var(--report-indigo));
}
.admin-reports-subcard {
    background: var(--report-card-body) !important;
    border-color: var(--report-line) !important;
    color: var(--report-muted) !important;
}
.admin-reports .text-slate-900 { color: var(--report-ink) !important; }
.admin-reports .text-slate-700 { color: var(--report-ink) !important; }
.admin-reports .text-slate-500,
.admin-reports .text-slate-400 { color: var(--report-muted) !important; }
.admin-reports .border-slate-200 { border-color: var(--report-line) !important; }
.admin-reports .border-slate-400 { border-color: var(--report-card-border) !important; }
html.dark-mode .admin-reports .border-slate-400 { border-color: var(--report-card-border) !important; }
.admin-reports .bg-slate-50 { background: var(--report-bg) !important; }
.admin-reports .admin-reports-subcard { background: var(--report-card-body) !important; }
.admin-reports .admin-reports-subcard .font-semibold,
.admin-reports .admin-reports-subcard .text-3xl { color: var(--report-indigo) !important; }
html.dark-mode .admin-reports .admin-reports-subcard .font-semibold,
html.dark-mode .admin-reports .admin-reports-subcard .text-3xl { color: #818cf8 !important; }
.admin-reports-history-table-wrap {
    position: relative;
    overflow: hidden;
    background: var(--report-card-body);
    border: 1px solid var(--report-line);
    border-radius: 20px;
    box-shadow: var(--report-shadow);
}
.admin-reports-history-table-wrap::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, var(--report-indigo));
}
.admin-reports-history-table {
    width: 100%;
    border-collapse: collapse;
    color: var(--report-ink);
}
.admin-reports-history-table thead th {
    padding: 18px 24px;
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--report-muted);
    background: var(--report-bg);
    border-bottom: 1px solid var(--report-line);
    white-space: nowrap;
}
.admin-reports-history-table tbody tr {
    border-bottom: 1px solid var(--report-line);
    transition: background 0.2s;
}
.admin-reports-history-table tbody tr:hover { background: var(--report-bg); }
.admin-reports-history-table tbody td {
    padding: 18px 24px;
    font-size: 0.875rem;
    color: var(--report-ink);
    white-space: nowrap;
}
.admin-reports-history-table tbody td:first-child { font-weight: 700; }
.admin-reports-history-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 16px;
    color: var(--report-muted);
}
.admin-reports-page-btns {
    display: flex;
    gap: 8px;
}
.admin-reports-page-btn {
    display: inline-flex;
    align-items: center;
    padding: 9px 18px;
    border-radius: 12px;
    font-size: 0.8125rem;
    font-weight: 700;
    text-decoration: none;
    background: var(--report-surface);
    color: var(--report-ink);
    border: none;
    transition: var(--report-transition);
}
.admin-reports-page-btn:not(.disabled):hover {
    background: var(--report-bg);
    color: var(--report-ink);
    transform: translateY(-1px);
    box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.08), 0 4px 8px -4px rgba(0, 0, 0, 0.04);
}
.admin-reports-page-btn.disabled {
    background: var(--report-bg);
    color: var(--report-muted);
    border: none;
    opacity: 0.5;
    cursor: not-allowed;
}
.admin-reports-history-pagination {
    background: var(--report-card-body) !important;
    border-color: var(--report-line) !important;
}
.admin-reports-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1e1b4b, var(--report-indigo)) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 16px -4px rgba(67, 56, 202, 0.3);
    transition: var(--report-transition);
}
.admin-reports-action:hover {
    background: linear-gradient(135deg, #1e1b4b, var(--report-indigo)) !important;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px -4px rgba(67, 56, 202, 0.4);
    filter: brightness(1.08);
}
.admin-reports .admin-reports-select {
    background-color: var(--report-bg) !important;
    color: var(--report-ink) !important;
    border-color: var(--report-line-strong) !important;
    transition: var(--report-transition);
}
.admin-reports .admin-reports-select:focus {
    border-color: var(--report-gold) !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    outline: none;
}
@keyframes adminReportsFadeUp {
    from {
        opacity: 0;
        transform: translateY(12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.admin-reports > * {
    opacity: 0;
    animation: adminReportsFadeUp 0.5s ease forwards;
}
.admin-reports > *:nth-child(1) { animation-delay: 0.05s; }
.admin-reports > *:nth-child(2) { animation-delay: 0.1s; }
.admin-reports > *:nth-child(3) { animation-delay: 0.15s; }
.admin-reports > *:nth-child(4) { animation-delay: 0.2s; }
.admin-reports > *:nth-child(5) { animation-delay: 0.25s; }
.admin-reports > .grid {
    opacity: 1;
    animation: none;
}
.admin-reports > .grid > * {
    opacity: 0;
    animation: adminReportsFadeUp 0.5s ease forwards;
}
.admin-reports > .grid > *:nth-child(1) { animation-delay: 0.1s; }
.admin-reports > .grid > *:nth-child(2) { animation-delay: 0.15s; }
.admin-reports > .grid > *:nth-child(3) { animation-delay: 0.2s; }
.admin-reports > .grid > *:nth-child(4) { animation-delay: 0.25s; }
.admin-reports > .grid > *:nth-child(5) { animation-delay: 0.3s; }
</style>

<div class="admin-reports max-w-7xl mx-auto space-y-6">
    <div class="flex items-end justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Admin Reports</h1>
            <p class="mt-1 text-sm text-slate-500">Monitor system usage, activity audits, data quality, and technical compliance from one place.</p>
        </div>
        <span class="hidden rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500 sm:inline-flex">ADMIN CONTROL CENTER</span>
    </div>

    @if(session('status'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="admin-reports-card order-1 rounded-3xl border border-slate-400 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">System usage</h2>
            <p class="mt-2 text-sm text-slate-500">Login activity and active users by role.</p>
            <div class="mt-5 space-y-3 text-sm text-slate-700">
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>Total users</span>
                    <span class="font-semibold">{{ $totalUsers }}</span>
                </div>
                <div class="admin-reports-subcard rounded-2xl p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Users by role</p>
                    <div class="mt-3 space-y-2">
                        @foreach($usersByRole as $role => $count)
                            <div class="flex items-center justify-between">
                                <span>{{ $role }}</span>
                                <span class="font-semibold">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="admin-reports-subcard rounded-2xl p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Active users by role</p>
                    <div class="mt-3 space-y-2">
                        @forelse($activeUsersByRole as $role => $count)
                            <div class="flex items-center justify-between">
                                <span>{{ $role }}</span>
                                <span class="font-semibold">{{ $count }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No active users recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-reports-card order-2 rounded-3xl border border-slate-400 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Account status and access risks</h2>
            <p class="mt-2 text-sm text-slate-500">Accounts requiring review or recent access attention.</p>
            <div class="mt-5 space-y-3 text-sm text-slate-700">
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>Disabled accounts</span>
                    <span class="font-semibold">{{ $accountRisks['disabled_accounts'] }}</span>
                </div>
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>Unverified accounts</span>
                    <span class="font-semibold">{{ $accountRisks['unverified_accounts'] }}</span>
                </div>
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>Password changes required</span>
                    <span class="font-semibold">{{ $accountRisks['password_changes_required'] }}</span>
                </div>
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>No activity in 30 days</span>
                    <span class="font-semibold">{{ $accountRisks['inactive_accounts'] }}</span>
                </div>
            </div>
        </div>

        <div class="admin-reports-card order-4 rounded-3xl border border-slate-400 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Activity summary</h2>
            <p class="mt-2 text-sm text-slate-500">Audit log activity and top contributors.</p>
            <div class="mt-5 space-y-4 text-sm text-slate-700">
                <div class="admin-reports-subcard rounded-2xl p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Top users by activity</p>
                    <div class="mt-3 space-y-2">
                        @foreach($topUsers as $entry)
                            <div class="flex items-center justify-between">
                                <span>{{ $entry->user?->username ?? 'Unknown user' }}</span>
                                <span class="font-semibold">{{ $entry->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="admin-reports-subcard rounded-2xl p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Actions recorded</p>
                    <div class="mt-3 space-y-2">
                        @foreach($auditStats as $stat)
                            <div class="flex items-center justify-between">
                                <span>{{ ucfirst($stat->action) }}</span>
                                <span class="font-semibold">{{ $stat->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-reports-card order-5 rounded-3xl border border-slate-400 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Data quality</h2>
            <p class="mt-2 text-sm text-slate-500">Validation issues and orphaned records.</p>
            <div class="mt-5 space-y-3 text-sm text-slate-700">
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>Incomplete project records</span>
                    <span class="font-semibold">{{ $dataQuality['incomplete_projects'] }}</span>
                </div>
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>Missing coordinates</span>
                    <span class="font-semibold">{{ $dataQuality['missing_coordinates'] }}</span>
                </div>
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>Missing budget data</span>
                    <span class="font-semibold">{{ $dataQuality['missing_budget'] }}</span>
                </div>
                <div class="admin-reports-subcard flex items-center justify-between rounded-2xl px-4 py-3">
                    <span>Potential orphaned records</span>
                    <span class="font-semibold">{{ $dataQuality['orphaned_projects'] }}</span>
                </div>
            </div>
        </div>

        <div class="admin-reports-card order-4 rounded-3xl border border-slate-400 p-6 shadow-sm xl:col-span-2">
            <h2 class="text-xl font-semibold text-slate-900">Technical / compliance</h2>
            <p class="mt-2 text-sm text-slate-500">Checks for synchronization and audit readiness.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="admin-reports-subcard rounded-2xl p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Audit log total</div>
                    <div class="mt-2 text-3xl font-bold">{{ $technicalMetrics['total_audit_logs'] }}</div>
                </div>
                <div class="admin-reports-subcard rounded-2xl p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Validation issues</div>
                    <div class="mt-2 text-3xl font-bold">{{ $technicalMetrics['projects_with_validation_issues'] }}</div>
                </div>
                <div class="admin-reports-subcard rounded-2xl p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Recent audit count</div>
                    <div class="mt-2 text-3xl font-bold">{{ $technicalMetrics['recent_audit_count'] }}</div>
                </div>
                <div class="admin-reports-subcard rounded-2xl p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Project updates (30d)</div>
                    <div class="mt-2 text-3xl font-bold">{{ $technicalMetrics['recent_project_updates'] }}</div>
                </div>
            </div>
        </div>

    </div>

    <div class="admin-reports-card rounded-3xl border border-slate-400 p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Generate stored report</h2>
                <p class="mt-1 text-sm text-slate-500">Save a snapshot of the latest report and keep it in admin history.</p>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @csrf
                <select name="report_type" class="admin-reports-select rounded-2xl border border-slate-200 px-4 py-3 text-sm shadow-sm outline-none">
                    <option value="project">System-Wide Project Report</option>
                    <option value="budget">Budget Analysis Report</option>
                    <option value="system_usage">System Usage Report</option>
                    <option value="activity_summary">Activity Summary Report</option>
                    <option value="data_quality">Data Quality Report</option>
                    <option value="technical_compliance">Technical / Compliance Report</option>
                </select>
                <button type="submit" class="admin-reports-action rounded-full px-5 py-3 text-sm font-semibold">Generate report</button>
            </form>
        </div>
    </div>

    <div class="admin-reports-card rounded-3xl border border-slate-400 p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Report history</h2>
        <p class="mt-2 text-sm text-slate-500">Previously generated reports with persisted snapshots and downloadable PDF files.</p>

        @if($reportHistory->isEmpty())
            <div class="admin-reports-subcard mt-6 rounded-2xl p-6 text-sm text-slate-600">
                No generated reports have been stored yet.
            </div>
        @else
            <div class="admin-reports-history-table-wrap mt-6 overflow-x-auto">
                <table class="admin-reports-history-table min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Generated</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">By</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportHistory as $report)
                            <tr>
                                <td>{{ $report->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ ucfirst($report->report_type) }}</td>
                                <td>{{ $report->title }}</td>
                                <td>{{ $report->generated_by_username ?? 'System' }}</td>
                                <td>{{ ucfirst($report->status) }}</td>
                                <td>
                                    @if($report->status === 'completed' && $report->pdf_path)
                                        <a href="{{ route('admin.reports.download', $report) }}" class="admin-reports-action rounded-full px-4 py-2 text-xs font-semibold">Download</a>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">Unavailable</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-reports-history-pagination mt-4 flex items-center justify-between rounded-3xl px-4 py-3">
                <div class="text-sm">Page {{ $reportHistory->currentPage() }} of {{ $reportHistory->lastPage() }}</div>
                <div class="admin-reports-page-btns">
                    @if ($reportHistory->onFirstPage())
                        <span class="admin-reports-page-btn disabled">Previous</span>
                    @else
                        <a href="{{ $reportHistory->previousPageUrl() }}" class="admin-reports-page-btn">Previous</a>
                    @endif
                    @if ($reportHistory->hasMorePages())
                        <a href="{{ $reportHistory->nextPageUrl() }}" class="admin-reports-page-btn">Next</a>
                    @else
                        <span class="admin-reports-page-btn disabled">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
