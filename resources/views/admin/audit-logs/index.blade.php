@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Audit Logs</h1>
        <p class="text-sm text-gray-500 mt-1">System-wide record of create, update, and delete actions.</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        @if ($logs->isEmpty())
            <p class="text-sm text-gray-500">No audit log entries yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid #B2BEB5;">
                            <th class="text-left py-3 px-4 font-semibold text-black">Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">User</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">Action</th>
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