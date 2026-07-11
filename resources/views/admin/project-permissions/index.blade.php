@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Project Edit Permissions</h1>
        <p class="text-sm text-gray-500 mt-1">Review department requests to edit critical project fields.</p>
    </div>

    @if (session('success'))
        <div class="rounded-md border border-green-300 bg-green-50 p-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Project</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Requested By</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Fields</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Reason</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($requests as $request)
                    <tr>
                        <td class="px-4 py-3 text-sm text-black">{{ $request->project->project_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-black">{{ $request->requester->username ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-black">
                            @php
                                $fields = $request->fields_requested;
                                if (is_string($fields)) {
                                    $decoded = json_decode($fields, true);
                                    $fields = is_array($decoded) ? $decoded : [$fields];
                                }
                                $fields = is_array($fields) ? $fields : [];
                                $fieldLabels = array_map(function ($field) {
                                    return match ($field) {
                                        'start_date' => 'Start Date',
                                        'target_end_date' => 'Target End Date',
                                        'approved_budget' => 'Approved Budget',
                                        'actual_budget' => 'Actual Budget',
                                        default => ucwords(str_replace('_', ' ', $field)),
                                    };
                                }, $fields);
                                $fieldLabel = $fieldLabels ? implode(', ', $fieldLabels) : '—';
                            @endphp
                            <div class="flex flex-wrap gap-1">
                                @foreach ($fieldLabels as $label)
                                    <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-medium text-amber-800">{{ $label }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-black">
                            {{ $request->reason ? $request->reason : '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $request->status === 'approved' ? 'bg-green-100 text-green-700' : ($request->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if ($request->status === 'pending')
                                <form method="POST" action="{{ route('admin.project-permissions.approve', $request->request_id) }}" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="review_notes" value="Approved by administrator." />
                                    <button type="submit" class="rounded bg-green-600 px-3 py-1 text-white">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.project-permissions.reject', $request->request_id) }}" class="ml-2 inline-block">
                                    @csrf
                                    <input type="hidden" name="review_notes" value="Rejected by administrator." />
                                    <button type="submit" class="rounded bg-red-600 px-3 py-1 text-white">Reject</button>
                                </form>
                            @else
                                <span class="text-gray-500">Reviewed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">No permission requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
