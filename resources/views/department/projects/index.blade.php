@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold" style="color: black">Department Projects</h1>
        <p style="color: #6B7280;">Manage and track department projects.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-md p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold" style="color: black;">All Projects</h2>
            <a href="{{ route('department.projects.create') }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Add New Project</a>
        </div>

        @if ($projects->isEmpty())
            <div class="text-sm text-gray-500">
                No projects have been added yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid #B2BEB5;">
                            <th class="text-left py-3 px-4 font-semibold text-black">Code</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">Project Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">Budget</th>
                            <th class="text-left py-3 px-4 font-semibold text-black">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr style="border-bottom: 1px solid #B2BEB5;">
                                <td class="py-3 px-4 text-black">{{ $project->project_code }}</td>
                                <td class="py-3 px-4 text-black">{{ $project->project_name }}</td>
                                <td class="py-3 px-4 text-black">{{ $project->current_status }}</td>
                                <td class="py-3 px-4 text-black">₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                                <td class="py-3 px-4 space-x-2">
                                    <a href="{{ route('department.projects.show', $project->project_id) }}" class="text-blue-600">View</a>
                                    <a href="{{ route('department.projects.edit', $project->project_id) }}" class="text-amber-600">Edit</a>
                                    <form action="{{ route('department.projects.destroy', $project->project_id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection