@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="color: black;">Create New Project</h1>
        <p style="color: #c9a84c;">Add a new project for Cabuyao City Government</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #white; border: 2px solid #B2BEB5;">
        <form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-black">Project Name</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: white;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Barangay</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: white;">
                        <option>Barangay 1</option>
                        <option>Barangay 2</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Budget</label>
                    <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: white;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Status</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: black;">
                        <option>Planning</option>
                        <option>In Progress</option>
                        <option>Completed</option>
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-sm font-medium text-black">Description</label>
                <textarea rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: black;"></textarea>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ url('/department/projects') }}" class="px-4 py-2 rounded" style="background-color: #c9a84c;">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Create Project</button>
            </div>
        </form>
    </div>
</div>
@endsection
