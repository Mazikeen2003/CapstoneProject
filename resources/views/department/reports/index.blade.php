@extends('layouts.department')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Reports & Exports</h1>
        <p class="mt-1 text-sm text-slate-500">Generate and download project reports in PDF format.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-md p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">📋 Projects Report</h3>
            <p class="text-sm text-slate-500 mb-4">Complete list of all your projects with details and budget information.</p>
            <a href="{{ route('department.reports.projects-pdf') }}" class="block w-full rounded-full bg-amber-400 px-4 py-3 text-center text-sm font-semibold text-slate-900 transition hover:bg-amber-500">Download PDF</a>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">💰 Budget Analysis</h3>
            <p class="text-sm text-slate-500 mb-4">Detailed budget breakdown by status and spending analysis.</p>
            <a href="{{ route('department.reports.budget-pdf') }}" class="block w-full rounded-full bg-amber-400 px-4 py-3 text-center text-sm font-semibold text-slate-900 transition hover:bg-amber-500">Download PDF</a>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">🏅 SGLG Compliance</h3>
            <p class="text-sm text-slate-500 mb-4">Documentation, transparency, and monitoring compliance summary for DILG SGLG assessment.</p>
            <a href="{{ route('department.reports.sglg-pdf') }}" class="block w-full rounded-full bg-amber-400 px-4 py-3 text-center text-sm font-semibold text-slate-900 transition hover:bg-amber-500">Download PDF</a>
        </div>
    </div>

    <!-- Info -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-4">About These Reports</h3>
        <ul class="text-sm text-slate-700 space-y-2">
            <li>✓ Reports are generated in PDF format for easy viewing and printing</li>
            <li>✓ All reports include your projects based on your department role</li>
            <li>✓ Reports are timestamped and suitable for official documentation</li>
            <li>✓ PDF format preserves formatting on any device</li>
        </ul>
    </div>
</div>
@endsection