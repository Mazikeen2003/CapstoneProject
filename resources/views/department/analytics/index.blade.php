@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Project Analytics</h1>
        <p style="color: #6B7280;">Overview of your department's projects and budget allocation.</p>
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
            <p class="text-xs text-gray-500 uppercase">Planning</p>
            <p class="text-3xl font-bold" style="color: #fbbf24;">{{ $stats['planning'] }}</p>
        </div>
    </div>

    <!-- Budget Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-4">Budget Allocation</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Total Budget</span>
                        <span class="font-bold text-black">₱{{ number_format($stats['total_budget'], 2) }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Total Spent</span>
                        <span class="font-bold text-black">₱{{ number_format($stats['total_spent'], 2) }}</span>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-semibold text-gray-700">Remaining</span>
                        <span class="font-bold" style="color: #10b981;">₱{{ number_format($stats['total_budget'] - $stats['total_spent'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- By Status -->
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-4">Budget by Status</h3>
            <div class="space-y-2">
                @foreach($byStatus as $status => $data)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $status }}</span>
                        <span class="font-semibold text-black">₱{{ number_format($data['budget'], 0) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Barangays -->
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-4">Top Barangays by Budget</h3>
            <div class="space-y-2">
                @forelse($byBarangay->take(5) as $barangay => $data)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 truncate">{{ $barangay }}</span>
                        <span class="font-semibold text-black">{{ $data['count'] }} proj</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No barangay data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Project Status Breakdown -->
    @if($stats['total_projects'] > 0)
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-4">Project Breakdown</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach(['Planning' => '#fbbf24', 'On Going' => '#3b82f6', 'On Hold' => '#ef4444', 'Completed' => '#10b981'] as $status => $color)
                    @php
                        $count = $byStatus[$status]['count'] ?? 0;
                        $percentage = $stats['total_projects'] > 0 ? round(($count / $stats['total_projects']) * 100) : 0;
                    @endphp
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background-color: {{ $color }}; opacity: 0.2; display: flex; align-items: center; justify-content: center;">
                                <span class="font-bold" style="color: {{ $color }};">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-black">{{ $status }}</p>
                        <p class="text-xs text-gray-500">{{ $count }} projects</p>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-300 text-blue-700 rounded-lg p-4">
            No projects created yet. <a href="{{ route('department.projects.create') }}" class="font-semibold">Create your first project</a>
        </div>
    @endif
</div>
@endsection