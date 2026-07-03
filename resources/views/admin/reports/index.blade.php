@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Admin Reports</h1>
        <p class="text-sm text-slate-500">Summary reports for system usage, activity audits, data quality, and technical compliance.</p>
    </div>

    @if(session('status'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">System usage</h2>
            <p class="mt-2 text-sm text-slate-500">Login activity and active users by role.</p>
            <div class="mt-5 space-y-3 text-sm text-slate-700">
                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <span>Total users</span>
                    <span class="font-semibold">{{ $totalUsers }}</span>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
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
                <div class="rounded-2xl bg-slate-50 p-4">
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

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Activity summary</h2>
            <p class="mt-2 text-sm text-slate-500">Audit log activity and top contributors.</p>
            <div class="mt-5 space-y-4 text-sm text-slate-700">
                <div class="rounded-2xl bg-slate-50 p-4">
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
                <div class="rounded-2xl bg-slate-50 p-4">
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

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Peak usage</h2>
            <p class="mt-2 text-sm text-slate-500">Hourly activity volume from audit logs.</p>
            <div class="mt-5 space-y-2 text-sm text-slate-700">
                @foreach($peakUsage as $hourStat)
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                        <span>{{ sprintf('%02d:00 - %02d:00', $hourStat->hour, ($hourStat->hour + 1) % 24) }}</span>
                        <span class="font-semibold">{{ $hourStat->total }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Data quality</h2>
            <p class="mt-2 text-sm text-slate-500">Validation issues and orphaned records.</p>
            <div class="mt-5 space-y-3 text-sm text-slate-700">
                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <span>Incomplete project records</span>
                    <span class="font-semibold">{{ $dataQuality['incomplete_projects'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <span>Missing coordinates</span>
                    <span class="font-semibold">{{ $dataQuality['missing_coordinates'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <span>Missing budget data</span>
                    <span class="font-semibold">{{ $dataQuality['missing_budget'] }}</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                    <span>Potential orphaned records</span>
                    <span class="font-semibold">{{ $dataQuality['orphaned_projects'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm xl:col-span-2">
            <h2 class="text-xl font-semibold text-slate-900">Technical / compliance</h2>
            <p class="mt-2 text-sm text-slate-500">Checks for synchronization and audit readiness.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Audit log total</div>
                    <div class="mt-2 text-3xl font-bold">{{ $technicalMetrics['total_audit_logs'] }}</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Validation issues</div>
                    <div class="mt-2 text-3xl font-bold">{{ $technicalMetrics['projects_with_validation_issues'] }}</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Recent audit count</div>
                    <div class="mt-2 text-3xl font-bold">{{ $technicalMetrics['recent_audit_count'] }}</div>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                    <div class="font-semibold text-slate-900">Project updates (30d)</div>
                    <div class="mt-2 text-3xl font-bold">{{ $technicalMetrics['recent_project_updates'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Generate stored report</h2>
                <p class="mt-1 text-sm text-slate-500">Save a snapshot of the latest report and keep it in admin history.</p>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @csrf
                <select name="report_type" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none focus:border-slate-300 focus:ring-1 focus:ring-slate-200">
                    <option value="project">System-Wide Project Report</option>
                    <option value="budget">Budget Analysis Report</option>
                    <option value="system_usage">System Usage Report</option>
                    <option value="activity_summary">Activity Summary Report</option>
                    <option value="data_quality">Data Quality Report</option>
                    <option value="technical_compliance">Technical / Compliance Report</option>
                </select>
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">Generate report</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Report history</h2>
        <p class="mt-2 text-sm text-slate-500">Previously generated reports with persisted snapshots and downloadable PDF files.</p>

        @if($reportHistory->isEmpty())
            <div class="mt-6 rounded-2xl bg-slate-50 p-6 text-sm text-slate-600">
                No generated reports have been stored yet.
            </div>
        @else
            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Generated</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">By</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($reportHistory as $report)
                            <tr>
                                <td class="px-4 py-3 text-slate-700">{{ $report->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ ucfirst($report->report_type) }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $report->title }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $report->generated_by_username ?? 'System' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ ucfirst($report->status) }}</td>
                                <td class="px-4 py-3">
                                    @if($report->status === 'completed' && $report->pdf_path)
                                        <a href="{{ route('admin.reports.download', $report) }}" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">Download</a>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">Unavailable</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between rounded-3xl border border-slate-200 bg-white px-4 py-3">
                <div class="text-sm text-slate-500">Showing {{ $reportHistory->firstItem() ?? 0 }} to {{ $reportHistory->lastItem() ?? 0 }} of {{ $reportHistory->total() }} reports</div>
                <div class="text-sm">
                    {{ $reportHistory->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
