@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-white" style="color: black">Department Projects</h1>
        <p style="color: #c9a84c;">Manage and track all department projects</p>
    </div>

    <div class="bg-white rounded-lg p-6" style="background-color: #white; border: 1px solid #B2BEB5;">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-white" style="color: black;">All Projects</h2>
            <a href="{{ url('/department/projects/create') }}" class="px-4 py-2 rounded" style="background-color: #c9a84c; color: #0f1e3d;">Add New Project</a>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 rounded" style="background-color: #white; border: 1px solid #B2BEB5;">
                <div>
                    <h3 class="font-semibold text-white" style="color: black;">Barangay Road Improvement</h3>
                    <p style="color: #c9a84c;">Barangay 1 - Infrastructure</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-sm" style="background-color: #c9a84c; color: #0f1e3d;">In Progress</span>
                    <a href="{{ url('/department/projects/1/edit') }}" class="text-sm" style="color: #c9a84c;">Edit</a>
                    <a href="{{ url('/department/projects/1') }}" class="text-sm" style="color: #c9a84c;">View</a>
                </div>
            </div>
               <div class="flex items-center justify-between p-4 rounded" style="background-color: #white; border: 1px solid #B2BEB5;">
                <div>
                    <h3 class="font-semibold text-white" style="color: black;">Barangay Road Improvement</h3>
                    <p style="color: #c9a84c;">Barangay 1 - Infrastructure</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-sm" style="background-color: #c9a84c; color: #0f1e3d;">Planning</span>
                    <a href="{{ url('/department/projects/1/edit') }}" class="text-sm" style="color: #c9a84c;">Edit</a>
                    <a href="{{ url('/department/projects/1') }}" class="text-sm" style="color: #c9a84c;">View</a>
                </div>
            </div>
             <div class="flex items-center justify-between p-4 rounded" style="background-color: #white; border: 1px solid #B2BEB5;">
                <div>
                    <h3 class="font-semibold text-white" style="color: black;">Pagawaan ng brief ni renz</h3>
                    <p style="color: #c9a84c;">Baranggay sala</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-sm" style="background-color: #c9a84c; color: #0f1e3d;">In Progress</span>
                    <a href="{{ url('/department/projects/1/edit') }}" class="text-sm" style="color: #c9a84c;">Edit</a>
                    <a href="{{ url('/department/projects/1') }}" class="text-sm" style="color: #c9a84c;">View</a>
                </div>
            </div>
                      <div class="flex items-center justify-between p-4 rounded" style="background-color: #white; border: 1px solid #B2BEB5;">
                <div>
                    <h3 class="font-semibold text-white" style="color: black;">Clinic </h3>
                    <p style="color: #c9a84c;">Barangay banlic - Infrastructure</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-sm" style="background-color: #c9a84c; color: #0f1e3d;">Completed</span>
                    <a href="{{ url('/department/projects/1/edit') }}" class="text-sm" style="color: #c9a84c;">Edit</a>
                    <a href="{{ url('/department/projects/1') }}" class="text-sm" style="color: #c9a84c;">View</a>
                </div>
            </div>
            <!-- More projects -->
        </div>
    </div>
</div>
@endsection
