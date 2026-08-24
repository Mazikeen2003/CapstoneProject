@extends('layouts.department')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="flex items-end justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Reports & Exports</h1>
            <p class="mt-1 text-sm text-slate-500">Generate official project, financial, and compliance reports.</p>
        </div>
        <span class="hidden rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500 sm:inline-flex">DEPARTMENT PORTAL</span>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-md p-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <x-report-card eyebrow="Project overview" title="Projects Report" description="Complete list of all your projects with details and budget information." icon="document" :route="route('department.reports.projects-pdf')" />
        <x-report-card eyebrow="Financial overview" title="Budget Analysis" description="Detailed budget breakdown by status and spending analysis." icon="budget" :route="route('department.reports.budget-pdf')" />
        <x-report-card eyebrow="Compliance overview" title="SGLG Compliance" description="Documentation, transparency, and monitoring compliance summary for DILG SGLG assessment." icon="document" :route="route('department.reports.sglg-pdf')" />
    </div>

    <!-- Info -->
    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">✓</span>
            <h3 class="text-lg font-bold text-slate-900">About These Reports</h3>
        </div>
        <ul class="mt-5 grid gap-3 text-sm text-slate-700 sm:grid-cols-2">
            <li>✓ Reports are generated in PDF format for easy viewing and printing</li>
            <li>✓ All reports include your projects based on your department role</li>
            <li>✓ Reports are timestamped and suitable for official documentation</li>
            <li>✓ PDF format preserves formatting on any device</li>
        </ul>
    </div>
</div>
@endsection