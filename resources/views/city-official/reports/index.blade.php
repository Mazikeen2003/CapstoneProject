@extends('layouts.city')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <div class="flex items-end justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Reports & Exports</h1>
            <p class="mt-1 text-sm text-slate-500">Generate official citywide project, financial, and compliance reports.</p>
        </div>
        <span class="hidden rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500 sm:inline-flex">CITY PORTAL</span>
    </div>

    @if (session('success'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <x-report-card eyebrow="Citywide overview" title="Citywide Projects" description="Complete list of all projects across all departments with full details." icon="document" :route="route('city.reports.projects-pdf')" />
        <x-report-card eyebrow="Financial overview" title="Budget Analysis" description="Citywide budget breakdown by status and barangay with spending analysis." icon="budget" :route="route('city.reports.budget-pdf')" />
        <x-report-card eyebrow="Compliance overview" title="SGLG Compliance" description="Documentation, transparency, and monitoring compliance summary for DILG SGLG assessment." icon="document" :route="route('city.reports.sglg-pdf')" />
    </div>

    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">✓</span>
            <h3 class="text-lg font-bold text-slate-900">About These Reports</h3>
        </div>
        <ul class="mt-5 grid gap-3 text-sm text-slate-700 sm:grid-cols-2">
            <li>✓ Reports show all projects from all departments in the city.</li>
            <li>✓ PDF format is ideal for official distribution and archiving.</li>
            <li>✓ All reports include generation timestamp and your name.</li>
            <li>✓ Formatted for easy printing and sharing.</li>
        </ul>
    </div>
</div>
@endsection
