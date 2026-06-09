@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Admin Overview</h1>
        <p class="text-sm text-gray-500 mt-1">Manage access and monitor system activity.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Login Accounts</p>
            <p class="text-4xl font-bold text-black mt-2">4</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Active Sessions</p>
            <p class="text-4xl font-bold text-black mt-2">0</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Reports</p>
            <p class="text-4xl font-bold text-black mt-2">0</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Audit Logs</p>
            <p class="text-4xl font-bold text-black mt-2">0</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-black">System Data</h2>
            <p class="text-sm text-gray-500 mt-1">No project, report, or activity data has been added yet.</p>
        </div>
        <div class="p-6 text-sm text-gray-500">
            The application is ready for live records.
        </div>
    </div>
</div>
@endsection
