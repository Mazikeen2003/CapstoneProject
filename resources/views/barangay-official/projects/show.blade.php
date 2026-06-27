@extends('layouts.barangay')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('barangay.projects.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">← Back to Projects</a>
        <h1 class="text-3xl font-bold text-black mt-2">{{ $project->project_name }}</h1>
        <p class="text-sm text-gray-500 mt-1">Project Code: {{ $project->project_code }}</p>
    </div>

    <!-- Status Badge -->
    <div class="inline-block px-4 py-2 rounded-lg font-semibold text-white" style="background-color: 
        @if($project->current_status === 'Completed') #10b981;
        @elseif($project->current_status === 'On Going') #3b82f6;
        @elseif($project->current_status === 'On Hold') #ef4444;
        @else #fbbf24;
        @endif">
        {{ $project->current_status }}
    </div>

    <!-- Project Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase">Barangay</p>
            <p class="text-black font-bold mt-2">{{ $project->barangay->barangay_name ?? 'Unknown' }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase">Project Type</p>
            <p class="text-black font-bold mt-2">{{ $project->project_type }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase">Approved Budget</p>
            <p class="text-black font-bold mt-2">₱{{ number_format($project->approved_budget ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase">Actual Budget</p>
            <p class="text-black font-bold mt-2">₱{{ number_format($project->actual_budget ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- Dates -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase">Start Date</p>
            <p class="text-black font-bold mt-2">{{ $project->start_date?->format('M d, Y') ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase">Target End Date</p>
            <p class="text-black font-bold mt-2">{{ $project->target_end_date?->format('M d, Y') ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase">Actual End Date</p>
            <p class="text-black font-bold mt-2">{{ $project->actual_end_date?->format('M d, Y') ?? '—' }}</p>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-black mb-3">Location Description</h3>
        <p class="text-gray-700">{{ $project->location_description ?? '—' }}</p>
    </div>

    <!-- Remarks -->
    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-black mb-3">Remarks</h3>
        <p class="text-gray-700">{{ $project->remarks ?? 'No remarks' }}</p>
    </div>

    <!-- Project Image -->
    @if($project->project_image)
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-black mb-3">Project Image</h3>
            <img src="{{ asset('storage/' . $project->project_image) }}" alt="{{ $project->project_name }}" class="w-full max-w-md rounded-lg">
        </div>
    @endif

    <!-- Updates -->
    @if($project->updates->count() > 0)
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-black mb-4">Project Updates</h3>
            <div class="space-y-3">
                @foreach($project->updates as $update)
                    <div class="border-l-4 border-blue-600 pl-4 py-2">
                        <p class="font-semibold text-black">{{ $update->status }} - {{ $update->progress_percentage }}%</p>
                        <p class="text-sm text-gray-600">{{ $update->update_date?->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-700">{{ $update->remarks }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection