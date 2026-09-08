@extends('layouts.admin')

@section('content')
<style>
.admin-audit-logs {
    --audit-bg: #f4f4f5;
    --audit-surface: #ffffff;
    --audit-ink: #0f0d1f;
    --audit-muted: #6b7280;
    --audit-line: rgba(0, 0, 0, 0.06);
    --audit-line-strong: rgba(0, 0, 0, 0.1);
    --audit-card-border: rgba(0, 0, 0, 0.06);
    --audit-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.04);
    --audit-indigo: #4338ca;
    --audit-gold: #f59e0b;
    --audit-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
html.dark-mode .admin-audit-logs {
    --audit-bg: #0f0e1a;
    --audit-surface: #141321;
    --audit-ink: #f8f7f5;
    --audit-muted: #94a3b8;
    --audit-line: rgba(255, 255, 255, 0.06);
    --audit-line-strong: rgba(255, 255, 255, 0.1);
    --audit-card-border: #0f172a;
    --audit-shadow: 0 1px 3px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1);
}
.admin-audit-logs > * {
    opacity: 0;
    animation: adminAuditFadeUp 0.5s ease forwards;
}
.admin-audit-logs > *:nth-child(1) { animation-delay: 0.05s; }
.admin-audit-logs > *:nth-child(2) { animation-delay: 0.1s; }
@keyframes adminAuditFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
.admin-audit-logs .admin-audit-card {
    position: relative;
    overflow: hidden;
    background: var(--audit-surface) !important;
    border-color: var(--audit-card-border) !important;
    border-radius: 20px !important;
    box-shadow: var(--audit-shadow);
}
.admin-audit-logs .admin-audit-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--audit-gold), var(--audit-indigo));
}
html.dark-mode .admin-audit-logs .admin-audit-card,
html.dark-mode .admin-audit-logs .admin-audit-mobile-card,
html.dark-mode .admin-audit-logs .admin-audit-table-wrap {
    border: 1px solid #0f172a !important;
    box-shadow: var(--audit-shadow);
}
.admin-audit-logs h1,
.admin-audit-logs h2,
.admin-audit-logs .text-black,
.admin-audit-logs .text-slate-900,
.admin-audit-logs .text-slate-700 { color: var(--audit-ink) !important; }
.admin-audit-logs .text-gray-500,
.admin-audit-logs .text-slate-600,
.admin-audit-logs .text-slate-500 { color: var(--audit-muted) !important; }
.admin-audit-control {
    background-color: var(--audit-bg) !important;
    color: var(--audit-ink) !important;
    border-color: var(--audit-line-strong) !important;
    transition: var(--audit-transition);
}
.admin-audit-control:focus {
    border-color: var(--audit-gold) !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    outline: none;
}
.admin-audit-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1e1b4b, var(--audit-indigo)) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 16px -4px rgba(67, 56, 202, 0.3);
    transition: var(--audit-transition);
}
.admin-audit-action:hover {
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px -4px rgba(67, 56, 202, 0.4);
    filter: brightness(1.08);
}
.admin-audit-ghost {
    background: var(--audit-surface) !important;
    color: var(--audit-ink) !important;
    border-color: var(--audit-line-strong) !important;
    transition: var(--audit-transition);
}
.admin-audit-ghost:hover {
    background: var(--audit-bg) !important;
    color: var(--audit-ink) !important;
}
.admin-audit-mobile-card,
.admin-audit-table-wrap {
    background: var(--audit-surface) !important;
    border-color: var(--audit-card-border) !important;
    box-shadow: var(--audit-shadow);
}
.admin-audit-table-wrap { overflow: hidden; }
.admin-audit-table {
    width: 100%;
    border-collapse: collapse;
    color: var(--audit-ink);
}
.admin-audit-table thead th {
    padding: 18px 12px;
    color: var(--audit-muted) !important;
    background: var(--audit-bg) !important;
    border-bottom: 1px solid var(--audit-line) !important;
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}
.admin-audit-table tbody tr {
    border-bottom: 1px solid var(--audit-line) !important;
    transition: background 0.2s;
}
.admin-audit-table tbody tr:hover { background: var(--audit-bg); }
.admin-audit-table tbody td {
    padding: 18px 12px;
    color: var(--audit-ink) !important;
}
.admin-audit-table details > div { background: var(--audit-bg) !important; color: var(--audit-ink) !important; }
.admin-audit-table a { color: var(--audit-indigo) !important; }
.admin-audit-details {
    background: var(--audit-bg) !important;
    color: var(--audit-ink) !important;
    border: 1px solid var(--audit-line) !important;
}
.admin-audit-details summary { color: var(--audit-ink) !important; }
.admin-audit-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    color: var(--audit-muted);
}
.admin-audit-page-btns { display: flex; gap: 8px; }
.admin-audit-page-btn {
    display: inline-flex;
    align-items: center;
    padding: 9px 18px;
    border-radius: 12px;
    background: var(--audit-surface);
    color: var(--audit-ink) !important;
    font-size: 0.8125rem;
    font-weight: 700;
    text-decoration: none;
    transition: var(--audit-transition);
}
.admin-audit-page-btn:not(.disabled):hover {
    background: var(--audit-bg);
    transform: translateY(-1px);
    box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.08), 0 4px 8px -4px rgba(0, 0, 0, 0.04);
}
.admin-audit-page-btn.disabled {
    background: var(--audit-bg);
    color: var(--audit-muted) !important;
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<div class="admin-audit-logs space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Audit Logs</h1>
        <p class="text-sm text-gray-500 mt-1">System-wide record of create, update, and delete actions.</p>
    </div>

    <div class="admin-audit-card rounded-lg border border-slate-400 p-6">
        <h2 class="mb-4 text-xl font-semibold text-slate-900">Filter Audit Logs</h2>
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid gap-4 lg:grid-cols-5 items-end">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1" for="user_id">USER</label>
                <select name="user_id" id="user_id" class="admin-audit-control w-full rounded-lg border px-3 py-2 text-sm">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1" for="action">ACTION</label>
                <select name="action" id="action" class="admin-audit-control w-full rounded-lg border px-3 py-2 text-sm">
                    <option value="">All Actions</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1" for="date_from">DATE FROM</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="admin-audit-control w-full rounded-lg border px-3 py-2 text-sm" />
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1" for="date_to">DATE TO</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="admin-audit-control w-full rounded-lg border px-3 py-2 text-sm" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button type="submit" class="admin-audit-action rounded-full px-5 py-2 text-sm font-semibold">Filter</button>
                <a href="{{ route('admin.audit-logs.index') }}" class="admin-audit-ghost inline-flex items-center justify-center rounded-full border px-5 py-2 text-sm font-semibold">Clear filters</a>
            </div>
        </form>

        <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-slate-600">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} audit logs</div>
            <a href="{{ route('admin.audit-logs.export', request()->except('page')) }}" class="admin-audit-action rounded-full px-5 py-2 text-sm font-semibold">Export to PDF</a>
        </div>

        @if ($logs->isEmpty())
            <p class="text-sm text-gray-500">No audit log entries found.</p>
        @else
            <div class="space-y-4 md:hidden mt-4">
                @foreach ($logs as $log)
                    <div class="admin-audit-mobile-card rounded-3xl border p-2 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-900">{{ $log->created_at?->format('M d, Y h:i A') }}</p>
                                <p class="text-xs text-slate-500">{{ $log->user->username ?? 'Unknown' }}</p>
                            </div>
                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($log->action) }}</span>
                        </div>
                        <div class="mt-1 text-xs text-slate-600 space-y-1">
                            <p><span class="font-semibold text-slate-700">Table:</span> {{ $log->table_name }}</p>
                            <p><span class="font-semibold text-slate-700">Record ID:</span> {{ $log->record_id }}</p>
                        </div>
                        <details class="admin-audit-details mt-2 rounded-2xl p-2 text-[11px]">
                            <summary class="cursor-pointer font-semibold">View changes</summary>
                            <div class="mt-2 space-y-2">
                                @if(!empty($log->old_values))
                                    <div>
                                            <div class="font-semibold">Old Values</div>
                                        <ul class="list-disc list-inside">
                                            @foreach((array) $log->old_values as $key => $value)
                                                @php
                                                    $displayValue = is_array($value) ? json_encode($value) : $value;
                                                    if (is_string($displayValue) && preg_match('/^\d{4}-\d{2}-\d{2}T.*(?:Z|[+-]\d{2}:\d{2})$/', $displayValue)) {
                                                        $displayValue = \Carbon\Carbon::parse($displayValue)->format('M d, Y h:i A');
                                                    }
                                                @endphp
                                                <li><span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ $displayValue }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if(!empty($log->new_values))
                                    <div>
                                            <div class="font-semibold">New Values</div>
                                        <ul class="list-disc list-inside">
                                            @foreach((array) $log->new_values as $key => $value)
                                                @php
                                                    $displayValue = is_array($value) ? json_encode($value) : $value;
                                                    if (is_string($displayValue) && preg_match('/^\d{4}-\d{2}-\d{2}T.*(?:Z|[+-]\d{2}:\d{2})$/', $displayValue)) {
                                                        $displayValue = \Carbon\Carbon::parse($displayValue)->format('M d, Y h:i A');
                                                    }
                                                @endphp
                                                <li><span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ $displayValue }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
            <div class="admin-audit-table-wrap hidden max-w-full overflow-x-auto mt-4 rounded-3xl border md:block">
                <table class="admin-audit-table w-full table-fixed text-xs">
                    <thead class="admin-card-header">
                        <tr style="border-bottom: 1px solid #B2BEB5;">
                            <th class="text-left py-1.5 px-2 font-semibold text-black">
                                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('page'), ['sort' => request('sort') === 'created_at_asc' ? 'created_at_desc' : 'created_at_asc'])) }}" class="inline-flex items-center gap-2">
                                    Date
                                    @if(request('sort') === 'created_at_asc')
                                        ▲
                                    @elseif(request('sort') === 'created_at_desc')
                                        ▼
                                    @endif
                                </a>
                            </th>
                            <th class="text-left py-1.5 px-2 font-semibold text-black">
                                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('page'), ['sort' => request('sort') === 'user_asc' ? 'user_desc' : 'user_asc'])) }}" class="inline-flex items-center gap-2">
                                    User
                                    @if(request('sort') === 'user_asc')
                                        ▲
                                    @elseif(request('sort') === 'user_desc')
                                        ▼
                                    @endif
                                </a>
                            </th>
                            <th class="whitespace-nowrap text-left py-1.5 px-2 text-[11px] font-semibold text-black">IP Address</th>
                            <th class="text-left py-1.5 px-2 font-semibold text-black">Full Name</th>
                            <th class="text-left py-1.5 px-2 font-semibold text-black">
                                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('page'), ['sort' => request('sort') === 'action_asc' ? 'action_desc' : 'action_asc'])) }}" class="inline-flex items-center gap-2">
                                    Action
                                    @if(request('sort') === 'action_asc')
                                        ▲
                                    @elseif(request('sort') === 'action_desc')
                                        ▼
                                    @endif
                                </a>
                            </th>
                            <th class="text-left py-1.5 px-2 font-semibold text-black">Table</th>
                            <th class="text-left py-1.5 px-2 font-semibold text-black">Record ID</th>
                            <th class="text-left py-1.5 px-2 font-semibold text-black">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr style="border-bottom: 1px solid #B2BEB5;">
                                <td class="break-words py-1.5 px-2 text-black">{{ $log->created_at?->format('M d, Y h:i A') }}</td>
                                <td class="break-words py-1.5 px-2 text-black">{{ $log->user->username ?? 'Unknown' }}</td>
                                <td class="whitespace-nowrap py-1.5 px-2 text-[11px] text-black">{{ $log->ip_address ?: 'N/A' }}</td>
                                <td class="break-words py-1.5 px-2 text-black">{{ $log->full_name ?: ($log->user->full_name ?? 'Unknown') }}</td>
                                <td class="break-words py-1.5 px-2 text-black capitalize">{{ $log->action }}</td>
                                <td class="break-words py-1.5 px-2 text-black">{{ $log->table_name }}</td>
                                <td class="break-words py-1.5 px-2 text-black">{{ $log->record_id }}</td>
                                <td class="py-1.5 px-2">
                                    <details>
                                        <summary class="cursor-pointer text-blue-600 text-xs">View changes</summary>
                                        <div class="admin-audit-details mt-2 max-w-full overflow-x-auto break-all rounded p-2 text-xs">
                                            @if(!empty($log->old_values))
                                                <div class="mb-2">
                                                    <div class="font-semibold">Old Values</div>
                                                    <ul class="list-disc list-inside text-xs text-slate-700">
                                                        @foreach((array) $log->old_values as $key => $value)
                                                            @php
                                                                $displayValue = is_array($value) ? json_encode($value) : $value;
                                                                if (is_string($displayValue) && preg_match('/^\d{4}-\d{2}-\d{2}T.*(?:Z|[+-]\d{2}:\d{2})$/', $displayValue)) {
                                                                    $displayValue = \Carbon\Carbon::parse($displayValue)->format('M d, Y h:i A');
                                                                }
                                                            @endphp
                                                            <li><span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ $displayValue }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if(!empty($log->new_values))
                                                <div>
                                                    <div class="font-semibold">New Values</div>
                                                    <ul class="list-disc list-inside text-xs text-slate-700">
                                                        @foreach((array) $log->new_values as $key => $value)
                                                            @php
                                                                $displayValue = is_array($value) ? json_encode($value) : $value;
                                                                if (is_string($displayValue) && preg_match('/^\d{4}-\d{2}-\d{2}T.*(?:Z|[+-]\d{2}:\d{2})$/', $displayValue)) {
                                                                    $displayValue = \Carbon\Carbon::parse($displayValue)->format('M d, Y h:i A');
                                                                }
                                                            @endphp
                                                            <li><span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ $displayValue }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-audit-pagination mt-4 flex items-center justify-between gap-4 text-sm">
                <span>Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</span>
                <div class="admin-audit-page-btns">
                    @if ($logs->onFirstPage())
                        <span class="admin-audit-page-btn disabled">Previous</span>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}" class="admin-audit-page-btn">Previous</a>
                    @endif

                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}" class="admin-audit-page-btn">Next</a>
                    @else
                        <span class="admin-audit-page-btn disabled">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
