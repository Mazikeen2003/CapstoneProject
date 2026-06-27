@extends('layouts.barangay')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Barangay Project Analytics</h1>
        <p style="color: #6B7280;">Projects in your barangay and their status.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg p-4" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Total Projects</p>
            <p class="text-3xl font-bold text-black mt-2">{{ $stats['total_projects'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Completed</p>
            <p class="text-3xl font-bold" style="color: #10b981;">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Ongoing</p>
            <p class="text-3xl font-bold" style="color: #3b82f6;">{{ $stats['ongoing'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">On Hold</p>
            <p class="text-3xl font-bold" style="color: #ef4444;">{{ $stats['on_hold'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Total Budget</p>
            <p class="text-2xl font-bold text-black mt-2">₱{{ number_format($stats['total_budget'], 0) }}</p>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <h3 class="text-lg font-bold text-black mb-4">Project Status Breakdown</h3>
        <div class="space-y-3">
            @foreach(['Planning' => '#fbbf24', 'On Going' => '#3b82f6', 'On Hold' => '#ef4444', 'Completed' => '#10b981'] as $status => $color)
                @php
                    $count = $byStatus[$status]['count'] ?? 0;
                    $budget = $byStatus[$status]['budget'] ?? 0;
                    $percentage = $stats['total_projects'] > 0 ? round(($count / $stats['total_projects']) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-semibold text-gray-700">{{ $status }} <span class="text-gray-500">({{ $count }})</span></span>
                        <span class="text-sm text-gray-700">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full" style="background-color: {{ $color }}; width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Budget Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-4">Budget Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-700">Total Allocated</span>
                    <span class="font-bold text-black">₱{{ number_format($stats['total_budget'], 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Total Spent</span>
                    <span class="font-bold text-black">₱{{ number_format($stats['total_spent'], 2) }}</span>
                </div>
                <div class="border-t border-gray-200 pt-3 flex justify-between">
                    <span class="font-semibold text-gray-700">Remaining Balance</span>
                    <span class="font-bold" style="color: #10b981;">₱{{ number_format($stats['total_budget'] - $stats['total_spent'], 2) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-4">Quick Stats</h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li>✓ {{ $stats['completed'] }} project(s) completed</li>
                <li>→ {{ $stats['ongoing'] }} project(s) currently ongoing</li>
                <li>⏸ {{ $stats['on_hold'] }} project(s) on hold</li>
                <li>📋 {{ $stats['planning'] }} project(s) in planning phase</li>
            </ul>
        </div>
    </div>

    @if($stats['total_projects'] == 0)
        <div class="bg-blue-50 border border-blue-300 rounded-lg p-4">
            <p class="text-sm text-blue-700">No projects assigned to your barangay yet. Check back soon!</p>
        </div>
    @endif
</div>
@endsection