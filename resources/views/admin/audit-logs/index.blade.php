@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: #1F2937;">Audit Logs</h1>
        <p style="color: #6B7280;">Track and monitor data modifications made within the system.</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #E5E7EB;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Log ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">User</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Role</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Action</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="py-6 px-4 text-sm text-gray-500">No audit logs have been recorded yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
