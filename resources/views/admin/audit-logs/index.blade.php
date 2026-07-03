@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Audit Logs</h1>
        <p class="text-sm text-gray-500 mt-1">System-wide record of create, update, and delete actions.</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid gap-4 lg:grid-cols-5 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1" for="user_id">User</label>
                <select name="user_id" id="user_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1" for="action">Action</label>
                <select name="action" id="action" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                    <option value="">All Actions</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1" for="date_from">Date From</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1" for="date_to">Date To</label>
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
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid #B2BEB5;">
                            <th class="text-left py-3 px-4 font-semibold text-black">
                                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('page'), ['sort' => request('sort') === 'created_at_asc' ? 'created_at_desc' : 'created_at_asc'])) }}" class="inline-flex items-center gap-2">
                                    Date
                                    @if(request('sort') === 'created_at_asc')
                                        ▲
                                    @elseif(request('sort') === 'created_at_desc')
                                        ▼
                                    @endif
                                </a>
                            </th>
                            <th class="text-left py-3 px-4 font-semibold text-black">
                                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('page'), ['sort' => request('sort') === 'user_asc' ? 'user_desc' : 'user_asc'])) }}" class="inline-flex items-center gap-2">
                                    User
                                    @if(request('sort') === 'user_asc')
                                        ▲
                                    @elseif(request('sort') === 'user_desc')
                                        ▼
                                    @endif
                                </a>
                            </th>
                            <th class="text-left py-3 px-4 font-semibold text-black">
                                <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('page'), ['sort' => request('sort') === 'action_asc' ? 'action_desc' : 'action_asc'])) }}" class="inline-flex items-center gap-2">
                                    Action
                                    @if(request('sort') === 'action_asc')
                                        ▲
                                    @elseif(request('sort') === 'action_desc')
                                        ▼
                                    @endif
                                </a>
                            </th>
                            <th class="text-left py-3 px-4 font-semibold text-black">Table</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">Record ID</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">IP</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr style="border-bottom: 1px solid #B2BEB5;">
                                <td class="py-3 px-4 text-black">{{ $log->created_at?->format('M d, Y h:i A') }}</td>
                                <td class="py-3 px-4 text-black">{{ $log->user->username ?? 'Unknown' }}</td>
                                <td class="py-3 px-4 text-black capitalize">{{ $log->action }}</td>
                                <td class="py-3 px-4 text-black">{{ $log->table_name }}</td>
                                <td class="py-3 px-4 text-black">{{ $log->record_id }}</td>
                                <td class="py-3 px-4 text-black">{{ $log->ip_address }}</td>
                                <td class="py-3 px-4">
                                    <details>
                                        <summary class="cursor-pointer text-blue-600 text-xs">View changes</summary>
                                        <div class="text-xs mt-2 bg-gray-50 p-2 rounded overflow-x-auto">
                                            @if(!empty($log->old_values))
                                                <div class="mb-2">
                                                    <div class="font-semibold">Old Values</div>
                                                    <ul class="list-disc list-inside text-xs text-slate-700">
                                                        @foreach((array) $log->old_values as $key => $value)
                                                            <li><span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ is_array($value) ? json_encode($value) : $value }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if(!empty($log->new_values))
                                                <div>
                                                    <div class="font-semibold">New Values</div>
                                                    <ul class="list-disc list-inside text-xs text-slate-700">
                                                        @foreach((array) $log->new_values as $key => $value)
                                                            <li><span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> {{ is_array($value) ? json_encode($value) : $value }}</li>
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

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
