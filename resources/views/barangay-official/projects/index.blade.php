@extends('layouts.barangay')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Barangay Projects</h1>
        <p class="text-sm text-gray-500 mt-1">Projects assigned to this barangay will appear here.</p>
    </div>

    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <div class="lg:hidden space-y-4">
            @forelse($projects as $project)
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $project->project_name }}</p>
                            <p class="text-sm text-slate-600 mt-1">{{ $project->current_status }}</p>
                        </div>
                        <a href="{{ route('barangay.projects.show', $project->project_id) }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-slate-100">View</a>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2 text-sm text-slate-600">
                        <div><span class="block text-xs text-slate-500">Budget</span><span class="font-semibold text-slate-900">₱{{ number_format($project->approved_budget ?? 0, 2) }}</span></div>
                        <div><span class="block text-xs text-slate-500">Status</span><span class="font-semibold text-slate-900">{{ $project->current_status }}</span></div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-gray-300 bg-slate-50 p-6 text-sm text-gray-500 text-center">No projects have been added yet.</div>
            @endforelse
        </div>

        <div class="overflow-x-auto hidden lg:block">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Project Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Budget</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 text-black font-medium">{{ $project->project_name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                    style="background-color:
                                        @if($project->current_status === 'Completed') #d1fae5; color: #065f46;
                                        @elseif($project->current_status === 'On Going') #dbeafe; color: #0c2340;
                                        @elseif($project->current_status === 'On Hold') #fee2e2; color: #7f1d1d;
                                        @else #fef3c7; color: #78350f;
                                        @endif">
                                    {{ $project->current_status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-700">₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('barangay.projects.show', $project->project_id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 px-4 text-sm text-gray-500 text-center">No projects have been added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection