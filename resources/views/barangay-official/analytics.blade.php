@extends('layouts.barangay')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Barangay Analytics</h1>
        <p class="text-sm text-gray-500 mt-1">Barangay project analytics will appear here after records are added.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card title="Total Projects" value="0" iconColor="#3b82f6" />
        <x-stat-card title="Completed" value="0" iconColor="#10b981" />
        <x-stat-card title="Ongoing" value="0" iconColor="#f59e0b" />
        <x-stat-card title="Budget Used" value="₱0" iconColor="#ef4444" />
    </div>

    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-black">Recent Projects</h3>
        <p class="text-sm text-gray-500 mt-4">No projects have been added yet.</p>
    </div>
</div>
@endsection
