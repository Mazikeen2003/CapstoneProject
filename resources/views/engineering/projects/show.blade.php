@extends('layouts.department')

@section('content')
@php
    $currentRole = auth()->user()?->role_slug ?? 'public';
@endphp
<style>
    .engineering-project-details {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
        overflow: hidden;
    }
    .engineering-project-details-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }
    .engineering-project-details-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dbeafe;
        color: #2563eb;
        flex-shrink: 0;
    }
    .engineering-project-details-header h2 {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.3;
    }
    .engineering-project-details-header p {
        color: #6b7280;
        font-size: 0.75rem;
        margin-top: 2px;
    }
    .engineering-project-details-body { padding: 24px; }
    .engineering-project-details-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 14px;
    }
    .engineering-project-detail-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 10px;
        background: #fafaf9;
    }
    .engineering-project-detail-item.full-width { grid-column: 1 / -1; }
    .engineering-project-detail-label {
        color: #9ca3af;
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .engineering-project-detail-value {
        color: #374151;
        font-size: 0.9375rem;
        font-weight: 700;
        line-height: 1.4;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }
    .dark .engineering-project-details,
    html.dark-mode .engineering-project-details {
        background: #1a1929;
        border-color: rgba(255, 255, 255, 0.06);
    }
    .dark .engineering-project-details-header,
    html.dark-mode .engineering-project-details-header {
        border-color: rgba(255, 255, 255, 0.06);
    }
    .dark .engineering-project-details-header h2,
    html.dark-mode .engineering-project-details-header h2 { color: #f8fafc; }
    .dark .engineering-project-detail-item,
    html.dark-mode .engineering-project-detail-item {
        background: #222136;
        border-color: rgba(255, 255, 255, 0.06);
    }
    .dark .engineering-project-detail-value,
    html.dark-mode .engineering-project-detail-value { color: #cbd5e1; }
    @media (min-width: 768px) {
        .engineering-project-details-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
</style>
<div class="space-y-6">
    <div>
        <h1 class="department-project-title text-3xl font-bold" style="color: black;">{{ $project->project_name }}</h1>
        <p class="text-sm text-gray-500 mt-1">Code: {{ $project->project_code }} &middot; Type: {{ $project->project_type }}</p>
    </div>

    @if (session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 rounded-md p-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @include('components.project-stepper', ['project' => $project])

    <div class="engineering-project-details">
        <div class="engineering-project-details-header">
            <div class="engineering-project-details-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 00.063.853l.041.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            </div>
            <div>
                <h2>Project Details</h2>
                <p>Key information about this project.</p>
            </div>
        </div>
        <div class="engineering-project-details-body">
            <div class="engineering-project-details-grid">
                @foreach ([
                    'Status' => $project->current_status,
                    'Barangay' => $project->barangay->barangay_name ?? 'Citywide',
                    'Approved Budget' => '₱' . number_format($project->approved_budget ?? 0, 2),
                    'Actual Budget' => '₱' . number_format($project->actual_budget ?? 0, 2),
                    'Start Date' => $project->start_date?->format('M d, Y') ?? '—',
                    'Target Completion' => $project->target_end_date?->format('M d, Y') ?? '—',
                    'Location' => $project->location_description ?? '—',
                    'Public Description' => $project->public_description ?? 'No public description available.',
                    'Internal Remarks (Private)' => $project->remarks ?? '—',
                ] as $label => $value)
                    <div class="engineering-project-detail-item {{ in_array($label, ['Location', 'Public Description', 'Internal Remarks (Private)'], true) ? 'full-width' : '' }}">
                        <div>
                            <div class="engineering-project-detail-label">{{ $label }}</div>
                            <div class="engineering-project-detail-value">{{ $value }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-black">Progress Updates</h3>
                <p class="mt-1 text-xs text-gray-500">Record the latest field progress for this project.</p>
            </div>
            <form method="POST" action="{{ route('engineering.projects.progress', $project->project_id) }}" class="grid w-full gap-3 sm:max-w-2xl sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                <div>
                    <label for="update_date" class="block text-xs font-semibold text-gray-600">Update date</label>
                    <input id="update_date" type="date" name="update_date" value="{{ old('update_date', now()->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label for="progress_percentage" class="block text-xs font-semibold text-gray-600">Progress %</label>
                    <input id="progress_percentage" type="number" name="progress_percentage" min="0" max="100" value="{{ old('progress_percentage', $project->latestUpdate?->progress_percentage ?? 0) }}" required class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-600">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm" style="border-color: #B2BEB5; color: black;">
                        <option value="">Keep current</option>
                        @foreach (['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed', 'Planning', 'On Going', 'On Hold', 'Cancelled', 'Bidding - Success', 'Bidding - Failed', 'Procurement'] as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-md px-4 py-2 text-sm font-semibold" style="background-color: #c9a84c; color: #0f1e3d;">Update</button>
                </div>
                <div class="sm:col-span-2 lg:col-span-4">
                    <label for="remarks" class="block text-xs font-semibold text-gray-600">Remarks</label>
                    <textarea id="remarks" name="remarks" rows="2" maxlength="2000" placeholder="Add a progress note (optional)" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm" style="border-color: #B2BEB5; color: black;">{{ old('remarks') }}</textarea>
                </div>
            </form>
        </div>

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
        <p class="text-xs text-gray-500 mb-4">Review the official documentation forms for this project (Form 1–11).</p>

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
                    $formsEnabled = $project->hasReachedImplementationStage();
                @endphp
                <div class="flex items-start gap-3 p-3 rounded border {{ ! $formsEnabled ? 'opacity-60' : '' }}" style="border-color: #B2BEB5;">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-black break-words">{{ $label }}</p>
                        <p class="text-xs {{ $existingForm ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $existingForm ? 'Available — last updated ' . $existingForm->updated_at->format('M d, Y') : (! $formsEnabled ? 'Available at Implementation stage' : ($isAvailable ? 'Not filled out yet' : 'Coming soon')) }}
                        </p>
                    </div>
                    @if ($isAvailable && $formsEnabled)
                        <div class="flex shrink-0 gap-2">
                            <a href="{{ route('engineering.projects.forms.edit', [$project->project_id, $type]) }}" class="px-3 py-1.5 text-xs font-semibold rounded" style="background-color: #c9a84c; color: #0f1e3d;">{{ $existingForm ? 'Edit' : 'Fill Out' }}</a>
                            @if ($existingForm)
                                <a href="{{ route('engineering.projects.forms.pdf', [$project->project_id, $type]) }}" title="Download PDF" class="px-2 py-1.5 text-xs font-semibold rounded" style="background-color: #162347; color: #f2f3f7;">PDF</a>
                            @endif
                        </div>
                    @else
                        <span class="shrink-0 whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded bg-gray-100 text-gray-400 cursor-not-allowed">Unavailable</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('engineering.projects.index') }}" class="department-project-back px-4 py-2 rounded" style="background-color: #e5e7eb;">Back to List</a>
    </div>
</div>
@endsection
