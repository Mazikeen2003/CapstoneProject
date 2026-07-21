@extends('layouts.department')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Reports & Exports</h1>
        <p style="color: #6B7280;">Generate and download project reports in PDF format.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-md p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Projects Report -->
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-2">📋 Projects Report</h3>
            <p class="text-sm text-gray-500 mb-4">Complete list of all your projects with details and budget information.</p>
            <a href="{{ route('department.reports.projects-pdf') }}" class="block w-full px-4 py-3 rounded text-center font-medium" style="background-color: #c9a84c; color: #0f1e3d; text-decoration: none;">Download PDF</a>
        </div>

        <!-- Budget Report -->
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-2">💰 Budget Analysis</h3>
            <p class="text-sm text-gray-500 mb-4">Detailed budget breakdown by status and spending analysis.</p>
            <a href="{{ route('department.reports.budget-pdf') }}" class="block w-full px-4 py-3 rounded text-center font-medium" style="background-color: #c9a84c; color: #0f1e3d; text-decoration: none;">Download PDF</a>
        </div>

        <!-- SGLG Compliance Report -->
        <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
            <h3 class="text-lg font-bold text-black mb-2">🏅 SGLG Compliance</h3>
            <p class="text-sm text-gray-500 mb-4">Documentation, transparency, and monitoring compliance summary for DILG SGLG assessment.</p>
            <a href="{{ route('department.reports.sglg-pdf') }}" class="block w-full px-4 py-3 rounded text-center font-medium" style="background-color: #c9a84c; color: #0f1e3d; text-decoration: none;">Download PDF</a>
        </div>
    </div>

    <!-- Info -->
    <div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
        <h3 class="text-lg font-bold text-black mb-4">About These Reports</h3>
        <ul class="text-sm text-gray-700 space-y-2">
            <li>✓ Reports are generated in PDF format for easy viewing and printing</li>
            <li>✓ All reports include your projects based on your department role</li>
            <li>✓ Reports are timestamped and suitable for official documentation</li>
            <li>✓ PDF format preserves formatting on any device</li>
        </ul>
    </div>
</div>
@endsection