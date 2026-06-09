@extends('layouts.barangay')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Barangay Official Dashboard</h1>
        <p style="color: #6B7280;">Barangay workspace for community projects</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card title="Local Projects" value="0" iconColor="#1e40af" />
        <x-stat-card title="Community Events" value="0" iconColor="#fbbf24" />
        <x-stat-card title="Residents Served" value="0" iconColor="#10b981" />
        <x-stat-card title="Completion Rate" value="0%" iconColor="#f43f5e" />
    </div>

    <div class="rounded-lg p-6" style="background-color: #ffffff; border: 1px solid #E5E7EB;">
        <h2 class="text-2xl font-semibold text-black mb-6">Community Projects</h2>
        <div class="text-sm text-gray-500">
            No projects have been added yet.
        </div>
    </div>
</div>
@endsection
