@extends('layouts.city-official')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Citywide Analytics</h1>
        <p style="color: #6B7280;">Complete overview of all city projects and budgets.</p>
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
            <p class="text-xs text-gray-500 uppercase">Total Budget</p>
            <p class="text-2xl font-bold text-black mt-2">₱{{ number_format($stats['total_budget'] / 1000000, 1) }}M</p>
        </div>
        <div class="bg-white rounded-lg p-4" style="border: 1px solid #B2BEB5;">
            <p class="text-xs text-gray-500 uppercase">Budget Used</p>
            <p class="text-2xl font-bold text-black mt-2">₱{{ number_format($stats['total_spent'] / 1000000, 1) }}M</p>
        </div>
    </div>

    <!-- Budget by Barangay -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-4">Budget by Status</h3>
            <div class="space-y-3">
                @foreach($byStatus as $status => $data)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-semibold text-gray-700">{{ $status }} ({{ $data['count'] }})</span>
                            <span class="text-sm font-bold text-black">₱{{ number_format($data['budget'] / 1000000, 1) }}M</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_budget'] > 0 ? ($data['budget'] / $stats['total_budget']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-4">Top 10 Barangays by Budget</h3>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($byBarangay as $barangay => $data)
                    <div class="flex justify-between text-sm pb-2 border-b border-gray-100">
                        <span class="text-gray-700 truncate">{{ $barangay }}</span>
                        <div class="text-right">
                            <p class="font-semibold text-black">₱{{ number_format($data['budget'] / 1000000, 1) }}M</p>
                            <p class="text-xs text-gray-500">{{ $data['count'] }} projects</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No barangay data available</p>
                @endforelse
            </div>
        </div>
    </div>

    @if($stats['total_projects'] > 0)
        <div class="bg-green-50 border border-green-300 rounded-lg p-4">
            <p class="text-sm text-green-700"><strong>✓ Success:</strong> All data is current and up-to-date from all departments.</p>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-300 rounded-lg p-4">
            <p class="text-sm text-blue-700">No projects have been created yet. Please contact departments to submit their projects.</p>
        </div>
    @endif
</div>
@endsection