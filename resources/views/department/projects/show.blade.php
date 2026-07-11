@extends('layouts.department')

@section('content')
@php
    $currentRole = auth()->user()?->role_slug ?? 'public';
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentRole = window.__currentRole || @json($currentRole);
        const pendingNotification = {{ Js::from(session('pending_notification') ?? null) }};
        const roleKeys = ['admin', 'department', 'city', 'barangay', 'public'];

        if (pendingNotification) {
            try {
                roleKeys.forEach((role) => {
                    const storageKey = 'projectTrackerNotifications:' + role;
                    const existing = JSON.parse(localStorage.getItem(storageKey) || '[]');

                    if (!existing.some(item => item.id === pendingNotification.id)) {
                        existing.unshift(pendingNotification);
                        localStorage.setItem(storageKey, JSON.stringify(existing));
                    }
                });

                window.dispatchEvent(new Event('notifications:updated'));
            } catch (error) {
                console.warn('Unable to store notification', error);
            }
        }
    });
</script>
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: black;">{{ $project->project_name }}</h1>
        <p class="text-sm text-gray-500 mt-1">Code: {{ $project->project_code }} &middot; Type: {{ $project->project_type }}</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-md p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-4" style="border: 1px solid #B2BEB5;">
        <div>
            <p class="text-xs text-gray-500">Status</p>
            <p class="text-black font-medium">{{ $project->current_status }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Barangay</p>
            <p class="text-black font-medium">{{ $project->barangay->barangay_name ?? 'Citywide' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Approved Budget</p>
            <p class="text-black font-medium">₱{{ number_format($project->approved_budget ?? 0, 2) }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Actual Budget</p>
            <p class="text-black font-medium">₱{{ number_format($project->actual_budget ?? 0, 2) }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Start Date</p>
            <p class="text-black font-medium">{{ $project->start_date?->format('M d, Y') ?? '—' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Target Completion</p>
            <p class="text-black font-medium">{{ $project->target_end_date?->format('M d, Y') ?? '—' }}</p>
        </div>
        <div class="md:col-span-2">
            <p class="text-xs text-gray-500">Location</p>
            <p class="text-black font-medium">{{ $project->location_description ?? '—' }}</p>
        </div>
        <div class="md:col-span-2">
            <p class="text-xs text-gray-500">Remarks</p>
            <p class="text-black font-medium">{{ $project->remarks ?? '—' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <h3 class="text-lg font-bold text-black mb-3">Progress Updates</h3>
        @if ($project->updates->isEmpty())
            <p class="text-sm text-gray-500">No updates logged yet.</p>
        @else
            <ul class="space-y-2">
                @foreach ($project->updates as $update)
                    <li class="text-sm text-black border-b border-gray-100 pb-2">
                        {{ $update->update_date?->format('M d, Y') }} — {{ $update->progress_percentage }}% — {{ $update->remarks ?? '' }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('department.projects.edit', $project->project_id) }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Edit Project</a>
        <a href="{{ route('department.projects.index') }}" class="px-4 py-2 rounded" style="background-color: #e5e7eb;">Back to List</a>
    </div>
</div>
@endsection