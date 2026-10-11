@extends('layouts.department')

@section('content')
@php $data = old() ?: ($form->form_data ?? []); @endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap');

    .form-premium {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #111827;
    }

    /* === TYPOGRAPHY === */
    .form-premium .heading-xl {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 2.25rem;
        letter-spacing: -0.03em;
        line-height: 1.1;
    }
    .form-premium .heading-lg {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.125rem;
        letter-spacing: -0.02em;
        line-height: 1.3;
    }
    .form-premium .label-text {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: -0.01em;
        color: #374151;
    }
    .form-premium .breadcrumb-text {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .form-premium .status-badge {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: -0.01em;
    }
    .form-premium .auto-badge {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 0.55rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }
    .form-premium .timestamp-text {
        font-family: 'Inter', monospace;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .form-premium .form-input {
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        color: #111827;
        transition: all 0.2s ease;
    }
    .form-premium .form-input::placeholder {
        color: #b0b8c5;
        font-weight: 400;
        font-size: 0.85rem;
    }
    .form-premium .form-input[readonly] {
        font-weight: 500;
        color: #6b7280;
    }

    /* === INTERACTIONS === */
    .form-premium input:not([readonly]):hover,
    .form-premium textarea:not([readonly]):hover,
    .form-premium select:hover {
        border-color: #a5b4fc;
    }
    .form-premium input:not([readonly]):focus,
    .form-premium textarea:not([readonly]):focus,
    .form-premium select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* === CARD EFFECTS === */
    .form-premium .card-premium {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .form-premium .card-premium:hover {
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    /* === BUTTON === */
    .form-premium .btn-save {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        letter-spacing: -0.01em;
    }
    .form-premium .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px -5px rgba(79, 70, 229, 0.4);
    }
    .form-premium .btn-save:active {
        transform: translateY(0);
    }

    /* === HEADER GLOW === */
    .form-premium .header-glow {
        background: linear-gradient(135deg, rgba(79,70,229,0.08) 0%, rgba(124,58,237,0.04) 100%);
    }

    /* === EMPLOYMENT CARDS === */
    .form-premium .employment-card-blue {
        background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
        border: 1px solid rgba(59, 130, 246, 0.15);
    }
    .form-premium .employment-card-pink {
        background: linear-gradient(135deg, #fdf2f8 0%, #fff1f2 100%);
        border: 1px solid rgba(244, 63, 94, 0.15);
    }

    /* ========================================
       DARK MODE — Global Overrides
       ======================================== */
    html.dark-mode .form-premium,
    .dark .form-premium {
        color: #e2e8f0;
    }
    html.dark-mode .form-premium > .bg-white,
    html.dark-mode .form-premium form > div.bg-white,
    .dark .form-premium > .bg-white,
    .dark .form-premium form > div.bg-white {
        background-color: #141c2b !important;
        border-color: #334155 !important;
        box-shadow: 0 12px 30px rgb(0 0 0 / 0.18);
    }
    html.dark-mode .form-premium [class*="bg-gray-50"],
    html.dark-mode .form-premium [class~="bg-gray-100"],
    .dark .form-premium [class*="bg-gray-50"],
    .dark .form-premium [class~="bg-gray-100"] {
        background-color: #1e293b !important;
    }
    html.dark-mode .form-premium .form-input,
    html.dark-mode .form-premium input:not([type="checkbox"]):not([type="radio"]),
    html.dark-mode .form-premium select,
    html.dark-mode .form-premium textarea,
    .dark .form-premium .form-input,
    .dark .form-premium input:not([type="checkbox"]):not([type="radio"]),
    .dark .form-premium select,
    .dark .form-premium textarea {
        background-color: #0f172a !important;
        border-color: #475569 !important;
        color: #f8fafc !important;
        color-scheme: dark;
    }
    html.dark-mode .form-premium input[readonly],
    .dark .form-premium input[readonly] {
        background-color: #1e293b !important;
        color: #cbd5e1 !important;
    }
    html.dark-mode .form-premium .form-input::placeholder,
    .dark .form-premium .form-input::placeholder {
        color: #94a3b8 !important;
    }
    html.dark-mode .form-premium .text-gray-900,
    html.dark-mode .form-premium .text-gray-800,
    .dark .form-premium .text-gray-900,
    .dark .form-premium .text-gray-800 {
        color: #f8fafc !important;
    }
    html.dark-mode .form-premium .text-gray-700,
    html.dark-mode .form-premium .text-gray-600,
    .dark .form-premium .text-gray-700,
    .dark .form-premium .text-gray-600 {
        color: #cbd5e1 !important;
    }
    html.dark-mode .form-premium .text-gray-500,
    html.dark-mode .form-premium .text-gray-400,
    html.dark-mode .form-premium .text-gray-300,
    .dark .form-premium .text-gray-500,
    .dark .form-premium .text-gray-400,
    .dark .form-premium .text-gray-300 {
        color: #94a3b8 !important;
    }
    html.dark-mode .form-premium .heading-xl,
    html.dark-mode .form-premium .heading-lg,
    .dark .form-premium .heading-xl,
    .dark .form-premium .heading-lg {
        color: #f8fafc;
    }
    html.dark-mode .form-premium .border-gray-100,
    html.dark-mode .form-premium .border-gray-200,
    html.dark-mode .form-premium .border-gray-300,
    .dark .form-premium .border-gray-100,
    .dark .form-premium .border-gray-200,
    .dark .form-premium .border-gray-300 {
        border-color: #334155 !important;
    }
    html.dark-mode .form-premium .header-glow,
    .dark .form-premium .header-glow {
        opacity: 0.35;
    }
    html.dark-mode .form-premium .auto-badge,
    .dark .form-premium .auto-badge {
        background: #1e293b !important;
        color: #94a3b8 !important;
    }
    html.dark-mode .form-premium .status-badge.bg-emerald-50,
    .dark .form-premium .status-badge.bg-emerald-50 {
        background: rgba(16, 185, 129, 0.1) !important;
        border-color: rgba(16, 185, 129, 0.25) !important;
        color: #34d399 !important;
    }
    html.dark-mode .form-premium .status-badge.bg-amber-50,
    .dark .form-premium .status-badge.bg-amber-50 {
        background: rgba(245, 158, 11, 0.1) !important;
        border-color: rgba(245, 158, 11, 0.25) !important;
        color: #fbbf24 !important;
    }
    html.dark-mode .form-premium .breadcrumb-text,
    .dark .form-premium .breadcrumb-text {
        color: #94a3b8;
    }
    html.dark-mode .form-premium .breadcrumb-text:hover,
    .dark .form-premium .breadcrumb-text:hover {
        color: #818cf8;
    }
    html.dark-mode .form-premium nav span.breadcrumb-text,
    .dark .form-premium nav span.breadcrumb-text {
        color: #f8fafc;
    }

    /* Dark mode employment cards */
    html.dark-mode .form-premium .employment-card-blue,
    .dark .form-premium .employment-card-blue {
        background: #141c2b !important;
        border: 1px solid #334155 !important;
        border-left: 3px solid #3b82f6 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2) !important;
    }
    html.dark-mode .form-premium .employment-card-pink,
    .dark .form-premium .employment-card-pink {
        background: #141c2b !important;
        border: 1px solid #334155 !important;
        border-left: 3px solid #f43f5e !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2) !important;
    }
    html.dark-mode .form-premium .employment-card-blue input,
    html.dark-mode .form-premium .employment-card-pink input,
    .dark .form-premium .employment-card-blue input,
    .dark .form-premium .employment-card-pink input {
        background-color: #0f172a !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2) !important;
    }
    html.dark-mode .form-premium .employment-card-blue .indicator-label,
    .dark .form-premium .employment-card-blue .indicator-label {
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.02em;
        text-transform: none;
    }
    html.dark-mode .form-premium .employment-card-pink .indicator-label,
    .dark .form-premium .employment-card-pink .indicator-label {
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.02em;
        text-transform: none;
    }
    html.dark-mode .form-premium .employment-card-blue .icon-wrap,
    .dark .form-premium .employment-card-blue .icon-wrap {
        background: rgba(59, 130, 246, 0.15) !important;
        border-radius: 8px;
    }
    html.dark-mode .form-premium .employment-card-blue .icon-wrap svg,
    .dark .form-premium .employment-card-blue .icon-wrap svg {
        color: #60a5fa !important;
    }
    html.dark-mode .form-premium .employment-card-pink .icon-wrap,
    .dark .form-premium .employment-card-pink .icon-wrap {
        background: rgba(244, 63, 94, 0.15) !important;
        border-radius: 8px;
    }
    html.dark-mode .form-premium .employment-card-pink .icon-wrap svg,
    .dark .form-premium .employment-card-pink .icon-wrap svg {
        color: #fb7185 !important;
    }
</style>

<div class="{{ ($formRoutePrefix ?? 'department') === 'department' ? 'department-form-ux ' : '' }}form-premium max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center mb-8">
        <a href="{{ route(($formRoutePrefix ?? 'department') . '.projects.index') }}" class="breadcrumb-text text-gray-400 hover:text-indigo-600 transition-colors">Projects</a>
        <svg class="w-3.5 h-3.5 mx-2.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route(($formRoutePrefix ?? 'department') . '.projects.show', $project->project_id) }}" class="breadcrumb-text text-gray-400 hover:text-indigo-600 transition-colors">{{ $project->project_code }}</a>
        <svg class="w-3.5 h-3.5 mx-2.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        <span class="breadcrumb-text text-gray-900">Form 2</span>
    </nav>

    {{-- Header Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8 mb-8 card-premium relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 header-glow rounded-full -mr-32 -mt-32 pointer-events-none"></div>
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center shadow-xl shadow-indigo-200 flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 0h6m-6 0H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2m-6 0v2a2 2 0 002 2h2a2 2 0 002-2v-2"></path>
                        </svg>
                    </div>
                    <div class="pt-1">
                        <h1 class="heading-xl text-gray-900">Form 2 — Physical and Financial Accomplishment Report</h1>
                        <p class="text-base text-gray-500 mt-2 font-medium">{{ $project->project_name }} <span class="text-gray-300 mx-2">|</span> <span class="font-mono text-sm text-gray-400 font-semibold">{{ $project->project_code }}</span></p>
                        <div class="flex items-center gap-2.5 mt-3.5 text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @if ($form)
                                <span class="timestamp-text">Created <span class="text-gray-700 font-bold">{{ $form->created_at->format('M d, Y h:i A') }}</span></span>
                                <span class="text-gray-300">&middot;</span>
                                <span class="timestamp-text">Updated <span class="text-gray-700 font-bold">{{ $form->updated_at->format('M d, Y h:i A') }}</span></span>
                            @else
                                <span class="text-sm font-medium">Not yet filled out</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    @if ($form)
                        <span class="status-badge inline-flex items-center px-5 py-2.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2.5 animate-pulse"></span>
                            Saved
                        </span>
                    @else
                        <span class="status-badge inline-flex items-center px-5 py-2.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200/80">
                            <span class="w-2 h-2 rounded-full bg-amber-500 mr-2.5"></span>
                            Draft
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Error Summary --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-800 mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">Please correct the following errors:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route(($formRoutePrefix ?? 'department') . '.projects.forms.update', [$project->project_id, 'form_2']) }}" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Section: Project Information --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden card-premium">
            <div class="bg-gray-50/60 px-8 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="heading-lg text-gray-900">Project Information</h2>
                </div>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="label-text mb-2 block">Implementing Agency</label>
                        <input type="text" name="implementing_agency" value="{{ old('implementing_agency', $data['implementing_agency'] ?? '') }}" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border" placeholder="Enter implementing agency">
                    </div>
                    <div>
                        <label class="label-text mb-2 block">Program/Project Title</label>
                        <div class="relative">
                            <input type="text" value="{{ $project->project_name }}" readonly class="form-input block w-full rounded-xl border-gray-200 bg-gray-50/80 py-3 px-4 border cursor-not-allowed">
                            <span class="auto-badge absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Auto</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="label-text mb-2 block">Implementation Start Date</label>
                        <div class="relative">
                            <input type="date" name="start_date" value="{{ old('start_date', $data['start_date'] ?? $project->start_date?->format('Y-m-d') ?? '') }}" class="form-input block w-full rounded-xl border-gray-200 bg-gray-50/80 text-gray-500 py-3 px-4 border cursor-not-allowed pr-10" readonly>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <label class="label-text mb-2 block">Implementation End Date</label>
                        <div class="relative">
                            <input type="date" name="end_date" value="{{ old('end_date', $data['end_date'] ?? $project->target_end_date?->format('Y-m-d') ?? '') }}" class="form-input block w-full rounded-xl border-gray-200 bg-gray-50/80 text-gray-500 py-3 px-4 border cursor-not-allowed pr-10" readonly>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="label-text mb-2 block">Fund Source</label>
                        @php $fundSource = old('fund_source', $data['fund_source'] ?? ''); @endphp
                        <div class="relative">
                            <select name="fund_source" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border appearance-none pr-10">
                                <option value="">-- Select fund source --</option>
                                @foreach (['ODA Loan', 'ODA Grant', 'ODA Loan and Grant', 'LFP', 'PPP', 'NTA', 'Local Development Fund'] as $option)
                                    <option value="{{ $option }}" @selected($fundSource === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div>
                        <label class="label-text mb-2 block">Funding Agency</label>
                        <input type="text" name="funding_agency" value="{{ old('funding_agency', $data['funding_agency'] ?? '') }}" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border" placeholder="Enter funding agency">
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="label-text mb-2 block">Total Project Cost (PHP)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">&#8369;</span>
                            <input type="text" name="total_project_cost" value="{{ old('total_project_cost', $data['total_project_cost'] ?? '') }}" class="currency-input form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 pl-9 pr-4 border" inputmode="decimal" autocomplete="off" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Financial Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden card-premium">
            <div class="bg-gray-50/60 px-8 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="heading-lg text-gray-900">Financial Status (PHP)</h2>
                </div>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach (['appropriations' => 'Appropriations', 'allotment' => 'Allotment', 'obligations' => 'Obligations', 'disbursements' => 'Disbursements'] as $field => $label)
                        <div>
                            <label class="label-text mb-2 block">{{ $label }}</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">&#8369;</span>
                                <input type="text" name="{{ $field }}" value="{{ old($field, $data[$field] ?? '') }}" class="currency-input form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 pl-9 pr-4 border" inputmode="decimal" autocomplete="off" placeholder="0.00">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Section: Physical Accomplishment --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden card-premium">
            <div class="bg-gray-50/60 px-8 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h2 class="heading-lg text-gray-900">Physical Accomplishment</h2>
                </div>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach (['target_owpa_to_date' => 'Target OWPA to Date', 'actual_owpa_to_date' => 'Actual OWPA to Date', 'slippage' => 'Slippage'] as $field => $label)
                        <div>
                            <label class="label-text mb-2 block">{{ $label }}</label>
                            <input type="text" name="{{ $field }}" value="{{ old($field, $data[$field] ?? '') }}" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border" placeholder="Enter {{ strtolower($label) }}">
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @for ($i = 1; $i <= 5; $i++)
                        <div>
                            <label class="label-text mb-2 block">Output Indicator {{ $i }}</label>
                            <input type="text" name="output_indicator_{{ $i }}" value="{{ old("output_indicator_{$i}", $data["output_indicator_{$i}"] ?? '') }}" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border" placeholder="Enter indicator {{ $i }}">
                        </div>
                    @endfor
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach (['end_of_project_target' => 'End of Project Target', 'target_to_date' => 'Target to Date', 'actual_to_date' => 'Actual to Date'] as $field => $label)
                        <div>
                            <label class="label-text mb-2 block">{{ $label }}</label>
                            <div class="relative">
                                <input type="date" name="{{ $field }}" value="{{ old($field, $data[$field] ?? '') }}" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border pr-10">
                                <svg class="w-4 h-4 text-gray-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Section: Employment and Remarks --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden card-premium">
            <div class="bg-gray-50/60 px-8 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h2 class="heading-lg text-gray-900">Employment and Remarks</h2>
                </div>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="employment-card-blue relative rounded-xl p-5">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="icon-wrap w-9 h-9 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <label class="label-text text-blue-700" style="text-transform: none; letter-spacing: 0.02em;">Employment Generated — Male</label>
                        </div>
                        <input type="number" min="0" name="employment_generated_male" value="{{ old('employment_generated_male', $data['employment_generated_male'] ?? '') }}" class="form-input block w-full rounded-lg border-blue-200 shadow-sm bg-white/80 py-2.5 px-3 border" placeholder="0">
                    </div>
                    <div class="employment-card-pink relative rounded-xl p-5">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="icon-wrap w-9 h-9 rounded-lg bg-pink-500/10 flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <label class="label-text text-pink-700" style="text-transform: none; letter-spacing: 0.02em;">Employment Generated — Female</label>
                        </div>
                        <input type="number" min="0" name="employment_generated_female" value="{{ old('employment_generated_female', $data['employment_generated_female'] ?? '') }}" class="form-input block w-full rounded-lg border-pink-200 shadow-sm bg-white/80 py-2.5 px-3 border" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="label-text mb-2 block">Remarks</label>
                    <textarea name="remarks" rows="3" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border resize-y" placeholder="Enter any additional remarks or notes...">{{ old('remarks', $data['remarks'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Signatories Include --}}
        @include('department.projects.forms.signatories', ['project' => $project, 'data' => $data])

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 pt-6 pb-12">
            <a href="{{ route(($formRoutePrefix ?? 'department') . '.projects.show', $project->project_id) }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all duration-200 shadow-sm hover:shadow" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Cancel
            </a>
            @if ($form)
                <a href="{{ route(($formRoutePrefix ?? 'department') . '.projects.forms.pdf', [$project->project_id, 'form_2']) }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl text-sm font-bold text-purple-700 bg-purple-50 border border-purple-200 hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-200 transition-all duration-200 shadow-sm hover:shadow" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
            @else
                <span class="inline-flex justify-center items-center px-6 py-3 rounded-xl text-sm font-bold text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed" style="font-family: 'Plus Jakarta Sans', sans-serif;" title="Save the form first before generating a PDF">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </span>
            @endif
            <button type="submit" class="btn-save inline-flex justify-center items-center px-8 py-3 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-xl">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Save Form
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formatCurrency = (input) => {
            const raw = input.value.replace(/[^\d.]/g, '');
            const parts = raw.split('.');
            let integer = parts[0];
            const decimal = parts[1] !== undefined ? '.' + parts[1].slice(0, 2) : '';
            integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            input.value = integer + decimal;
        };
        const stripCommasForSubmit = (input) => {
            input.value = input.value.replace(/,/g, '');
        };
        document.querySelectorAll('.currency-input').forEach(input => {
            formatCurrency(input);
            input.addEventListener('input', () => formatCurrency(input));
        });
        document.querySelector('form')?.addEventListener('submit', () => {
            document.querySelectorAll('.currency-input').forEach(stripCommasForSubmit);
        });
    });
</script>
@endsection
