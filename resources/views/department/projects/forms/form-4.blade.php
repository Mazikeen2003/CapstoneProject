@extends('layouts.department')

@section('content')
@php $data = old() ?: ($form->form_data ?? []); $location = ($project->location_description ?? ($project->barangay->barangay_name ?? 'Citywide')) . ', Cabuyao City, Laguna'; @endphp
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold text-black">Form 4 — Project Results</h1><p class="text-sm text-gray-500 mt-1">{{ $project->project_name }} ({{ $project->project_code }})</p><p class="text-xs text-gray-400 mt-1">@if ($form) Form created on {{ $form->created_at->format('M d, Y h:i A') }} &middot; Last updated {{ $form->updated_at->format('M d, Y h:i A') }} @else Not yet filled out @endif</p></div>
    @if ($errors->any())<div class="bg-red-50 border border-red-300 text-red-700 rounded-md p-3 text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('department.projects.forms.update', [$project->project_id, 'form_4']) }}" class="space-y-6">@csrf @method('PUT')
        <div class="bg-white rounded-lg p-6 space-y-4" style="border: 1px solid #B2BEB5;"><h2 class="text-lg font-bold text-black">Project Results</h2><div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-black">Implementing Agency</label><input type="text" name="implementing_agency" value="{{ old('implementing_agency', $data['implementing_agency'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;"></div>
            <div><label class="block text-sm font-medium text-black">Program/Project Title</label><input type="text" value="{{ $project->project_name }}" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" style="border-color: #B2BEB5; color: black;"><p class="text-xs text-gray-500 mt-1">Auto-filled from project record.</p></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-black">Location</label><input type="text" value="{{ $location }}" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" style="border-color: #B2BEB5; color: black;"><p class="text-xs text-gray-500 mt-1">Auto-filled from project record.</p></div>
            <div><label class="block text-sm font-medium text-black">Program Objectives</label>@php $objective = old('program_objectives', $data['program_objectives'] ?? ''); @endphp<select name="program_objectives" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;"><option value="">-- Select --</option>@foreach (['Flood Control Managed', 'reduced travel time and lessen the traffic congestion', 'Enhanced Road Durability and Smoothness', 'Enhanced Mobility'] as $option)<option value="{{ $option }}" @selected($objective === $option)>{{ $option }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium text-black">Results/Outcome Target</label><input type="text" name="results_outcome_target" value="{{ old('results_outcome_target', $data['results_outcome_target'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;"></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-black">Observed Results</label><textarea name="observed_results" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">{{ old('observed_results', $data['observed_results'] ?? '') }}</textarea></div>
        </div></div>
        @include('department.projects.forms.signatories', ['project' => $project, 'data' => $data])
        <div class="flex justify-end space-x-3"><a href="{{ route('department.projects.show', $project->project_id) }}" class="px-4 py-2 rounded" style="background-color: #e5e7eb;">Cancel</a><button type="submit" class="px-4 py-2 rounded" style="background-color: #162347; color: #f2f3f7;">Save Form</button></div>
    </form>
</div>
@endsection
