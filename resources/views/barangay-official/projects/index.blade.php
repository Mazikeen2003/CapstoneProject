@extends('layouts.barangay')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Barangay Projects</h1>
        <p class="text-sm text-gray-500 mt-1">Projects assigned to this barangay will appear here.</p>
    </div>

    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Project Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-500 text-xs uppercase">Budget</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="py-6 px-4 text-sm text-gray-500">No projects have been added yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
