@extends('layouts.city')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Reports & Exports</h1>
        <p style="color: #6B7280;">Generate and download citywide reports in PDF format.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-md p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Projects Report -->
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-2">📋 Citywide Projects</h3>
            <p class="text-sm text-gray-500 mb-4">Complete list of all projects across all departments with full details.</p>
            <a href="{{ route('city.reports.projects-pdf') }}" class="block w-full px-4 py-3 rounded text-center font-medium" style="background-color: #c9a84c; color: #0f1e3d; text-decoration: none;">Download PDF</a>
        </div>

        <!-- Budget Report -->
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-2">💰 Budget Analysis</h3>
            <p class="text-sm text-gray-500 mb-4">Citywide budget breakdown by status and barangay with spending analysis.</p>
            <a href="{{ route('city.reports.budget-pdf') }}" class="block w-full px-4 py-3 rounded text-center font-medium" style="background-color: #c9a84c; color: #0f1e3d; text-decoration: none;">Download PDF</a>
        </div>

        <!-- SGLG Compliance Report -->
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-2">🏅 SGLG Compliance</h3>
            <p class="text-sm text-gray-500 mb-4">Documentation, transparency, and monitoring compliance summary for DILG SGLG assessment.</p>
            <a href="{{ route('city.reports.sglg-pdf') }}" class="block w-full px-4 py-3 rounded text-center font-medium" style="background-color: #c9a84c; color: #0f1e3d; text-decoration: none;">Download PDF</a>
        </div>
    </div>

    <!-- Info -->
    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <h3 class="text-lg font-bold text-black mb-4">About These Reports</h3>
        <ul class="text-sm text-gray-700 space-y-2">
            <li>✓ Reports show all projects from all departments in the city</li>
            <li>✓ PDF format is ideal for official distribution and archiving</li>
            <li>✓ All reports include generation timestamp and your name</li>
            <li>✓ Formatted for easy printing and sharing</li>
        </ul>
    </div>
</div>
@endsection