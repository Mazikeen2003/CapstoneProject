@extends('layouts.city')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">{{ $project->project_name }}</h1>
        <p class="text-sm text-slate-500 mt-1">Project Code: {{ $project->project_code }}</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-4">
                <div>
                    <p class="text-xs text-slate-500">Status</p>
                    <p class="text-slate-900 font-semibold">{{ $project->current_status }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Barangay</p>
                    <p class="text-slate-900 font-semibold">{{ $project->barangay?->barangay_name ?? 'Citywide' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Approved Budget</p>
                    <p class="text-slate-900 font-semibold">₱{{ number_format($project->approved_budget ?? 0, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Actual Budget</p>
                    <p class="text-slate-900 font-semibold">₱{{ number_format($project->actual_budget ?? 0, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Start Date</p>
                    <p class="text-slate-900 font-semibold">{{ $project->start_date?->format('M d, Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Target Completion</p>
                    <p class="text-slate-900 font-semibold">{{ $project->target_end_date?->format('M d, Y') ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs text-slate-500">Location</p>
            <p class="text-slate-900 font-semibold">{{ $project->location_description ?? '—' }}</p>

            <div class="mt-6">
                <p class="text-xs text-slate-500">Remarks</p>
                <p class="text-slate-900 font-semibold">{{ $project->remarks ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Project Updates</h2>
        @if ($project->updates->isEmpty())
            <p class="mt-4 text-sm text-slate-500">No updates logged yet.</p>
        @else
            <ul class="mt-4 space-y-3">
                @foreach ($project->updates as $update)
                    <li class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-900">{{ $update->update_date?->format('M d, Y') ?? 'Unknown date' }}</p>
                        <p class="text-sm text-slate-600">{{ $update->progress_percentage ?? '0' }}% — {{ $update->remarks ?? 'No remarks' }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="flex justify-between gap-3">
        <a href="{{ route('city.projects.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100">Back to Projects</a>
    </div>
</div>
@endsection
