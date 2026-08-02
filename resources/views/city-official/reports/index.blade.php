@extends('layouts.city')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="space-y-2">
        <h1 class="text-3xl font-bold text-slate-900">Reports & Exports</h1>
        <p class="text-sm text-slate-500">Generate and download citywide reports in PDF format.</p>
    </div>

    @if (session('success'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">📋 Citywide Projects</h3>
            <p class="text-sm text-slate-500 mb-4">Complete list of all projects across all departments with full details.</p>
            <a href="{{ route('city.reports.projects-pdf') }}" class="inline-flex w-full items-center justify-center rounded-full bg-amber-400 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">Download PDF</a>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">💰 Budget Analysis</h3>
            <p class="text-sm text-slate-500 mb-4">Citywide budget breakdown by status and barangay with spending analysis.</p>
            <a href="{{ route('city.reports.budget-pdf') }}" class="inline-flex w-full items-center justify-center rounded-full bg-amber-400 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">Download PDF</a>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-2">🏅 SGLG Compliance</h3>
            <p class="text-sm text-slate-500 mb-4">Documentation, transparency, and monitoring compliance summary for DILG SGLG assessment.</p>
            <a href="{{ route('city.reports.sglg-pdf') }}" class="inline-flex w-full items-center justify-center rounded-full bg-amber-400 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">Download PDF</a>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-4">About These Reports</h3>
        <ul class="space-y-2 text-sm text-slate-700">
            <li>✓ Reports show all projects from all departments in the city.</li>
            <li>✓ PDF format is ideal for official distribution and archiving.</li>
            <li>✓ All reports include generation timestamp and your name.</li>
            <li>✓ Formatted for easy printing and sharing.</li>
        </ul>
    </div>
</div>
@endsection
