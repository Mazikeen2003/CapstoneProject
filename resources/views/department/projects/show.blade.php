@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Project Details</h1>
        <p style="color: #c9a84c;">Barangay Road Improvement</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #white; border: 2px solid #B2BEB5;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-black mb-2">Project Information</h3>
                <p style="color: #black;"><strong>Name:</strong> Barangay Road Improvement</p>
                <p style="color: #black;"><strong>Barangay:</strong> Barangay 1</p>
                <p style="color: #black;"><strong>Budget:</strong> ₱500,000</p>
                <p style="color: #black;"><strong>Status:</strong> <span style="background-color: #c9a84c; color: #black; padding: 2px 8px; border-radius: 4px;">In Progress</span></p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-black mb-2">Description</h3>
                <p style="color: #black;">Road improvement project for Barangay 1 to enhance infrastructure and accessibility.</p>
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ url('/department/projects/1/edit') }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Edit Project</a>
            <a href="{{ url('/department/projects') }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Back to Projects</a>
        </div>
    </div>
</div>
@endsection
