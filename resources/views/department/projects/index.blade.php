@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: black">Department Projects</h1>
        <p style="color: #6B7280;">Manage and track department projects.</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold" style="color: black;">All Projects</h2>
            <a href="{{ url('/department/projects/create') }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Add New Project</a>
        </div>
        <div class="text-sm text-gray-500">
            No projects have been added yet.
        </div>
    </div>
</div>
@endsection
