@extends('layouts.department')

@section('content')
<div class="engineering-page-enter max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="flex items-end justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Engineering Reports</h1>
            <p class="mt-1 text-sm text-slate-500">Generate project, financial, and compliance reports for field monitoring.</p>
        </div>
        <span class="hidden rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500 sm:inline-flex">ENGINEERING PORTAL</span>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <x-report-card eyebrow="Project overview" title="Projects Report" description="Complete list of all current projects with implementation details and funding information." icon="document" :route="route('engineering.reports.projects-pdf')" />
        <x-report-card eyebrow="Financial overview" title="Budget Analysis" description="Detailed budget review by funding allocation, expenditure, and implementation status." icon="budget" :route="route('engineering.reports.budget-pdf')" />
        <x-report-card eyebrow="Compliance overview" title="SGLG Compliance" description="Documentation and transparency checks aligned with compliance and assessment reporting." icon="document" :route="route('engineering.reports.sglg-pdf')" />
    </div>
</div>
@endsection
