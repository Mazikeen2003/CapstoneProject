@extends('layouts.city')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">City Official Dashboard</h1>
        <p style="color: #6B7280;">City-wide project monitoring workspace</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card title="Total Projects" value="0" iconColor="#3b82f6" />
        <x-stat-card title="City Budget" value="₱0" iconColor="#06b6d4" />
        <x-stat-card title="Ongoing Projects" value="0" iconColor="#f59e0b" />
        <x-stat-card title="Completed Projects" value="0" iconColor="#10b981" />
    </div>

    <div class="rounded-lg p-6" style="background-color: #ffffff; border: 1px solid #E5E7EB;">
        <h2 class="text-2xl font-semibold text-black mb-6">City Projects</h2>
        <div class="text-sm text-gray-500">
            No projects have been added yet.
        </div>
    </div>
</div>
@endsection
