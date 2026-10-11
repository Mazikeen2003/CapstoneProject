@extends('layouts.department')

@section('content')
@php
    $data = old() ?: ($form->form_data ?? []);
    $location = ($project->location_description ?? ($project->barangay->barangay_name ?? 'Citywide')) . ', Cabuyao City, Laguna';
@endphp

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
</style>

<div class="{{ ($formRoutePrefix ?? 'department') === 'department' ? 'department-form-ux ' : '' }}form-premium max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center mb-8">
        <a href="{{ route(($formRoutePrefix ?? 'department') . '.projects.index') }}" class="breadcrumb-text text-gray-400 hover:text-indigo-600 transition-colors">Projects</a>
        <svg class="w-3.5 h-3.5 mx-2.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route(($formRoutePrefix ?? 'department') . '.projects.show', $project->project_id) }}" class="breadcrumb-text text-gray-400 hover:text-indigo-600 transition-colors">{{ $project->project_code }}</a>
        <svg class="w-3.5 h-3.5 mx-2.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        <span class="breadcrumb-text text-gray-900">Form 4</span>
    </nav>

    {{-- Header Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-8 mb-8 card-premium relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 header-glow rounded-full -mr-32 -mt-32 pointer-events-none"></div>
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center shadow-xl shadow-indigo-200 flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="pt-1">
                        <h1 class="heading-xl text-gray-900">Form 4 — Project Results</h1>
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

    <form method="POST" action="{{ route(($formRoutePrefix ?? 'department') . '.projects.forms.update', [$project->project_id, 'form_4']) }}" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Section: Project Results --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden card-premium">
            <div class="bg-gray-50/60 px-8 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="heading-lg text-gray-900">Project Results</h2>
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
                <div>
                    <label class="label-text mb-2 block">Location</label>
                    <div class="relative">
                        <input type="text" value="{{ $location }}" readonly class="form-input block w-full rounded-xl border-gray-200 bg-gray-50/80 py-3 px-4 border cursor-not-allowed">
                        <span class="auto-badge absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Auto</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="label-text mb-2 block">Program Objectives</label>
                        @php $objective = old('program_objectives', $data['program_objectives'] ?? ''); @endphp
                        <div class="relative">
                            <select name="program_objectives" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border appearance-none pr-10">
                                <option value="">-- Select objective --</option>
                                @foreach (['Flood Control Managed', 'reduced travel time and lessen the traffic congestion', 'Enhanced Road Durability and Smoothness', 'Enhanced Mobility'] as $option)
                                    <option value="{{ $option }}" @selected($objective === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div>
                        <label class="label-text mb-2 block">Results/Outcome Target</label>
                        <input type="text" name="results_outcome_target" value="{{ old('results_outcome_target', $data['results_outcome_target'] ?? '') }}" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border" placeholder="Enter results/outcome target">
                    </div>
                </div>
                <div>
                    <label class="label-text mb-2 block">Observed Results</label>
                    <textarea name="observed_results" rows="4" class="form-input block w-full rounded-xl border-gray-300 shadow-sm bg-white py-3 px-4 border resize-y" placeholder="Describe the observed results of the project...">{{ old('observed_results', $data['observed_results'] ?? '') }}</textarea>
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
                <a href="{{ route(($formRoutePrefix ?? 'department') . '.projects.forms.pdf', [$project->project_id, 'form_4']) }}" class="inline-flex justify-center items-center px-6 py-3 rounded-xl text-sm font-bold text-purple-700 bg-purple-50 border border-purple-200 hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-200 transition-all duration-200 shadow-sm hover:shadow" style="font-family: 'Plus Jakarta Sans', sans-serif;">
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
@endsection
