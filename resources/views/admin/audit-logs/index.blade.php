@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Audit Logs</h1>
        <p class="text-sm text-gray-500 mt-1">System-wide record of create, update, and delete actions.</p>
    </div>

    <div class="bg-white rounded-lg border border-slate-400 p-6">
        <h2 class="mb-4 text-xl font-semibold text-slate-900">Filter Audit Logs</h2>
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid gap-4 lg:grid-cols-5 items-end">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1" for="user_id">USER</label>
                <select name="user_id" id="user_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1" for="action">ACTION</label>
                <select name="action" id="action" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                    <option value="">All Actions</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1" for="date_from">DATE FROM</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900" />
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600 mb-1" for="date_to">DATE TO</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Filter</button>
                <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Clear filters</a>
            </div>
        </form>

        <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="text-sm text-slate-600">Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} audit logs</div>
            <a href="{{ route('admin.audit-logs.export', request()->except('page')) }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Export to PDF</a>
        </div>

        @if ($logs->isEmpty())
            <p class="text-sm text-gray-500">No audit log entries found.</p>
        @else
            <div class="space-y-4 md:hidden mt-4">
                @foreach ($logs as $log)
                    <div class="rounded-3xl border border-slate-400 bg-white p-2 shadow-sm">
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
                        <details class="mt-2 bg-slate-50 rounded-2xl p-2 text-[11px] text-slate-700">
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
            <div class="hidden max-w-full overflow-x-auto mt-4 rounded-3xl border border-slate-400 md:block">
                <table class="w-full table-fixed text-xs">
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
                                        <div class="mt-2 max-w-full overflow-x-auto break-all rounded bg-gray-50 p-2 text-xs">
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

            <div class="mt-4 flex items-center justify-between gap-4 text-sm text-slate-600">
                <span>Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</span>
                <div class="flex items-center gap-2">
                    @if ($logs->onFirstPage())
                        <span class="cursor-not-allowed rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 opacity-40">Previous</span>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}" class="rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Previous</a>
                    @endif

                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}" class="rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 transition hover:bg-slate-50">Next</a>
                    @else
                        <span class="cursor-not-allowed rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 opacity-40">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
