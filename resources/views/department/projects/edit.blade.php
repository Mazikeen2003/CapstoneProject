@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: black;">Edit Project</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $project->project_name }}</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-700 rounded-md p-3 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <form method="POST" action="{{ route('department.projects.update', $project->project_id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-black">Project Code</label>
                    <input type="text" name="project_code" value="{{ old('project_code', $project->project_code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Project Type</label>
                    <input type="text" name="project_type" value="{{ old('project_type', $project->project_type) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-black">Project Name</label>
                <input type="text" name="project_name" value="{{ old('project_name', $project->project_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-black">Barangay</label>
                <select name="barangay_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                    <option value="">-- None / Citywide --</option>
                    @foreach ($barangays as $barangay)
                        <option value="{{ $barangay->barangay_id }}" @selected(old('barangay_id', $project->barangay_id) == $barangay->barangay_id)>
                            {{ $barangay->barangay_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mt-3">
                <div>
                    <label class="block text-sm font-medium text-black">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Target Completion</label>
                    <input type="date" name="target_end_date" value="{{ old('target_end_date', $project->target_end_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mt-3">
                <div>
                    <label class="block text-sm font-medium text-black">Approved Budget</label>
                    <input type="number" step="0.01" name="approved_budget" value="{{ old('approved_budget', $project->approved_budget) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Actual Budget</label>
                    <input type="number" step="0.01" name="actual_budget" value="{{ old('actual_budget', $project->actual_budget) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                </div>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-black">Status</label>
                @php $status = old('current_status', $project->current_status); @endphp
                <select name="current_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">
                    <option value="Planning" @selected($status == 'Planning')>Planning</option>
                    <option value="On Going" @selected($status == 'On Going')>On Going</option>
                    <option value="On Hold" @selected($status == 'On Hold')>On Hold</option>
                    <option value="Completed" @selected($status == 'Completed')>Completed</option>
                    <option value="Cancelled" @selected($status == 'Cancelled')>Cancelled</option>
                    <option value="Bidding - Success" @selected($status == 'Bidding - Success')>Bidding - Success</option>
                    <option value="Bidding - Failed" @selected($status == 'Bidding - Failed')>Bidding - Failed</option>
                    <option value="Procurement" @selected($status == 'Procurement')>Procurement</option>
                </select>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-black">Remarks</label>
                <textarea name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="border-color: #B2BEB5; color: black;">{{ old('remarks', $project->remarks) }}</textarea>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-black">Replace Project Image</label>
                <input type="file" name="project_image" accept="image/*" class="mt-1 block w-full rounded-md border border-gray-300 text-sm shadow-sm">
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('department.projects.show', $project->project_id) }}" class="px-4 py-2 rounded" style="background-color: #e5e7eb;">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection