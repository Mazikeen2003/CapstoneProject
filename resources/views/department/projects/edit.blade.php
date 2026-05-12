@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Edit Project</h1>
        <p style="color: #c9a84c;">Modify project details</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #white; border: 1px solid #B2BEB5;">
        <form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-black">Project Name</label>
                    <input type="text" value="Barangay Road Improvement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Barangay</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: black;">
                        <option selected>Barangay 1</option>
                        <option>Barangay 2</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Budget</label>
                    <input type="number" value="500000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: black;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-black">Status</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: black;">
                        <option>Planning</option>
                        <option selected>In Progress</option>
                        <option>Completed</option>
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-sm font-medium text-black">Description</label>
                <textarea rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" style="background-color: #white; border-color: #B2BEB5; color: black;">Road improvement project for Barangay 1</textarea>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ url('/department/projects') }}" class="px-4 py-2 rounded" style="background-color: #c9a84c;">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Update Project</button>
            </div>
        </form>
    </div>
</div>
@endsection
