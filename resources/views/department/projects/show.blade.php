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

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-black">Government Forms</h3>
        </div>
        <p class="text-xs text-gray-500 mb-4">Fill out official documentation forms for this project (Form 1–11).</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @php
                $formList = [
                    'form_1' => 'Form 1 — Initial Project Report',
                    'form_2' => 'Form 2 — Physical and Financial Accomplishment Report',
                    'form_3' => 'Form 3 — Project Exception Report',
                    'form_4' => 'Form 4 — Project Results',
                    'form_5' => 'Form 5 — Summary of Financial and Physical Accomplishments',
                    'form_6' => 'Form 6 — Status of Projects with Implementation Problems',
                    'form_7' => 'Form 7 — Project Inspection Report',
                    'form_8' => 'Form 8 — Problem Solving Sessions/Facilitation Meeting',
                    'form_9' => 'Form 9 — Training/Workshop Conducted/Facilitated/Attended by the PMC',
                    'form_10' => 'Form 10 — RPMC and RDC Resolutions Related to RPMES',
                    'form_11' => 'Form 11 — Key Lessons Learned from Issues Resolved and Best Practices',
                ];
            @endphp

            @foreach ($formList as $type => $label)
                @php
                    $existingForm = $project->forms->firstWhere('form_type', $type);
                    $isAvailable = in_array($type, ['form_1', 'form_2', 'form_3', 'form_4', 'form_5', 'form_6', 'form_7', 'form_8', 'form_9', 'form_10', 'form_11'], true);
                @endphp
                <div class="flex items-center justify-between p-3 rounded border" style="border-color: #B2BEB5;">
                    <div>
                        <p class="text-sm font-medium text-black">{{ $label }}</p>
                        <p class="text-xs {{ $existingForm ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $existingForm ? 'Filled out — last updated ' . $existingForm->updated_at->format('M d, Y') : ($isAvailable ? 'Not filled out yet' : 'Coming soon') }}
                        </p>
                    </div>
                    @if ($isAvailable)
                        <a href="{{ route('department.projects.forms.edit', [$project->project_id, $type]) }}" class="px-3 py-1.5 text-xs font-semibold rounded" style="background-color: #c9a84c; color: #0f1e3d;">
                            {{ $existingForm ? 'View / Edit' : 'Fill Out' }}
                        </a>
                    @else
                        <span class="px-3 py-1.5 text-xs font-semibold rounded bg-gray-100 text-gray-400 cursor-not-allowed">Unavailable</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('department.projects.edit', $project->project_id) }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Edit Project</a>
        <a href="{{ route('department.projects.index') }}" class="px-4 py-2 rounded" style="background-color: #e5e7eb;">Back to List</a>
    </div>
</div>
@endsection
