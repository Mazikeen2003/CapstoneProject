@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<style>
    .dept-create-container {
        --dc-bg: #f8f7f5;
        --dc-surface: #ffffff;
        --dc-raised: #fafaf9;
        --dc-ink: #1e1b4b;
        --dc-ink-secondary: #374151;
        --dc-muted: #9ca3af;
        --dc-line: rgba(0,0,0,0.06);
        --dc-line-strong: rgba(0,0,0,0.12);
        --dc-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --dc-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --dc-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --dc-radius: 16px;
        --dc-radius-sm: 12px;
        --dc-radius-xs: 10px;
    }
    .dark .dept-create-container {
        --dc-bg: #0f0e1a;
        --dc-surface: #1a1929;
        --dc-raised: #222136;
        --dc-ink: #f8fafc;
        --dc-ink-secondary: #cbd5e1;
        --dc-muted: #64748b;
        --dc-line: rgba(255,255,255,0.06);
        --dc-line-strong: rgba(255,255,255,0.12);
        --dc-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
        --dc-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.4), 0 1px 2px -1px rgb(0 0 0 / 0.4);
        --dc-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
    }

    .dept-create-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
        background: var(--dc-bg);
        color: var(--dc-ink);
        transition: background 0.3s, color 0.3s;
    }
    @media (min-width: 640px) { .dept-create-container { padding: 32px; } }
    @media (min-width: 1024px) { .dept-create-container { padding: 40px; } }

    /* Page header */
    .dept-create-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 32px;
    }
    .dept-create-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 8px 24px -8px rgba(245, 158, 11, 0.5);
    }
    .dark .dept-create-icon { box-shadow: 0 8px 24px -8px rgba(245, 158, 11, 0.3); }
    .dept-create-icon svg { width: 28px; height: 28px; }
    .dept-create-title {
        font-family: "Plus Jakarta Sans", "Inter", sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
        color: var(--dc-ink);
    }
    .dept-create-subtitle {
        font-size: 0.875rem;
        color: var(--dc-muted);
        margin-top: 4px;
    }

    /* Grid */
    .dept-create-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }
    @media (min-width: 1024px) {
        .dept-create-grid {
            grid-template-columns: 1fr 380px;
            align-items: start;
        }
    }

    /* Card */
    .dept-create-card {
        background: var(--dc-surface);
        border-radius: var(--dc-radius-sm);
        border: 1px solid var(--dc-line);
        box-shadow: var(--dc-shadow-sm);
        overflow: hidden;
        transition: background 0.3s, border-color 0.3s;
    }
    .dept-create-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--dc-line);
    }
    .dept-create-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dept-create-card-icon.blue { background: #dbeafe; color: #2563eb; }
    .dept-create-card-icon.emerald { background: #d1fae5; color: #059669; }
    .dept-create-card-icon.purple { background: #ede9fe; color: #7c3aed; }
    .dept-create-card-icon.rose { background: #ffe4e6; color: #e11d48; }
    .dark .dept-create-card-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dark .dept-create-card-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
    .dark .dept-create-card-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
    .dark .dept-create-card-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }

    .dept-create-card-header h2 {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--dc-ink);
    }
    .dept-create-card-header p {
        font-size: 0.75rem;
        color: var(--dc-muted);
        margin-top: 2px;
    }
    .dept-create-card-body { padding: 24px; }

    /* Form rows */
    .dept-create-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    .dept-create-row:last-child { margin-bottom: 0; }
    @media (min-width: 640px) {
        .dept-create-row.cols-2 { grid-template-columns: 1fr 1fr; }
    }

    .dept-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .dept-field-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--dc-ink);
    }
    .dept-field-label .required { color: #ef4444; font-weight: 700; }
    .dept-field-hint {
        font-size: 0.75rem;
        color: var(--dc-muted);
        line-height: 1.4;
    }

    .dept-input-wrap { position: relative; }
    .dept-input-wrap .dept-input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--dc-muted);
        pointer-events: none;
        transition: color 0.2s;
    }
    .dept-input-wrap.has-icon input,
n    .dept-input-wrap.has-icon select { padding-left: 42px; }

    .dept-create-container input[type="text"],
    .dept-create-container input[type="number"],
    .dept-create-container input[type="date"],
    .dept-create-container input[type="file"],
    .dept-create-container select,
    .dept-create-container textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid var(--dc-line-strong);
        border-radius: var(--dc-radius-xs);
        background: var(--dc-raised);
        color: var(--dc-ink);
        font-family: inherit;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s ease;
    }
    .dept-create-container input::placeholder,
    .dept-create-container textarea::placeholder { color: var(--dc-muted); opacity: 0.7; }
    .dept-create-container input:focus,
    .dept-create-container select:focus,
    .dept-create-container textarea:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.12);
        background: var(--dc-surface);
    }
    .dept-create-container input[type="file"] {
        padding: 8px 14px;
        font-size: 0.8125rem;
        cursor: pointer;
    }
    .dept-create-container input[type="file"]::file-selector-button {
        margin-right: 12px;
        padding: 6px 14px;
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        font-family: inherit;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .dept-create-container input[type="file"]::file-selector-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px -2px rgba(245, 158, 11, 0.4);
    }
    .dept-create-container select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 20px;
        padding-right: 40px;
    }
    .dept-create-container textarea {
        resize: vertical;
        min-height: 100px;
        line-height: 1.6;
    }

    /* Readonly field */
    .dept-field-readonly {
        padding: 11px 14px;
        border-radius: var(--dc-radius-xs);
        background: var(--dc-raised);
        border: 1px solid var(--dc-line);
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dc-ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dept-field-readonly .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #dbeafe;
        color: #1d4ed8;
    }
    .dark .dept-field-readonly .badge { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dept-field-readonly .badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Sidebar */
    .dept-create-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    @media (min-width: 1024px) {
        .dept-create-sidebar { position: sticky; top: 24px; }
    }

    .dept-map-card {
        background: var(--dc-surface);
        border-radius: var(--dc-radius-sm);
        border: 1px solid var(--dc-line);
        box-shadow: var(--dc-shadow-sm);
        overflow: hidden;
    }
    .dept-map-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--dc-line);
    }
    .dept-map-header h3 {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--dc-ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dept-map-header p {
        font-size: 0.75rem;
        color: var(--dc-muted);
        margin-top: 2px;
    }
    .dept-map-wrap {
        position: relative;
        height: 320px;
    }
    #project-location-map {
        width: 100%;
        height: 100%;
        background: #e5e7eb;
    }
    .dept-map-hint {
        position: absolute;
        bottom: 12px;
        left: 12px;
        right: 12px;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px);
        border: 1px solid var(--dc-line);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.6875rem;
        color: var(--dc-ink-secondary);
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 400;
    }
    .dark .dept-map-hint {
        background: rgba(26,25,41,0.95);
        border-color: rgba(255,255,255,0.1);
    }
    .dept-map-hint svg {
        width: 14px;
        height: 14px;
        color: #d97706;
        flex-shrink: 0;
    }
    .dept-map-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--dc-line);
    }
    .dept-address-field {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .dept-address-field label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--dc-muted);
    }
    .dept-address-field input[readonly] {
        background: var(--dc-raised);
        border-style: dashed;
        color: var(--dc-muted);
        font-size: 0.8125rem;
    }

    /* Upload zone */
    .dept-upload-zone {
        border: 2px dashed var(--dc-line-strong);
        border-radius: var(--dc-radius-xs);
        padding: 32px 24px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
    }
    .dept-upload-zone:hover {
        border-color: #f59e0b;
        background: rgba(245, 158, 11, 0.03);
    }
    .dept-upload-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        border-radius: 12px;
        background: #fef3c7;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .dark .dept-upload-icon { background: rgba(251,191,36,0.1); }
    .dept-upload-icon svg { width: 22px; height: 22px; }
    .dept-upload-zone h4 {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--dc-ink);
        margin-bottom: 4px;
    }
    .dept-upload-zone p {
        font-size: 0.75rem;
        color: var(--dc-muted);
    }
    .dept-upload-zone input[type="file"] { display: none; }

    /* Actions */
    .dept-create-actions {
        display: flex;
        flex-direction: column-reverse;
        gap: 12px;
        padding-top: 8px;
    }
    @media (min-width: 480px) {
        .dept-create-actions { flex-direction: row; justify-content: flex-end; }
    }

    .dept-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 28px;
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        border: none;
    }
    .dept-btn-primary {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.5);
    }
    .dept-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -4px rgba(245, 158, 11, 0.6);
    }
    .dept-btn-secondary {
        background: var(--dc-raised);
        color: var(--dc-ink-secondary);
        border: 1px solid var(--dc-line-strong);
    }
    .dept-btn-secondary:hover {
        background: var(--dc-surface);
        border-color: var(--dc-line-strong);
        box-shadow: var(--dc-shadow-sm);
    }
    .dept-btn svg { width: 18px; height: 18px; }

    /* Error alert */
    .dept-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 20px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--dc-radius-xs);
        margin-bottom: 24px;
    }
    .dark .dept-alert { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.2); }
    .dept-alert-icon {
        width: 20px;
        height: 20px;
        color: #dc2626;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .dept-alert ul {
        list-style: none;
        font-size: 0.8125rem;
        color: #991b1b;
    }
    .dark .dept-alert ul { color: #fca5a5; }
    .dept-alert li {
        position: relative;
        padding-left: 14px;
        margin-bottom: 4px;
    }
    .dept-alert li:last-child { margin-bottom: 0; }
    .dept-alert li::before {
        content: "•";
        position: absolute;
        left: 0;
        color: #dc2626;
        font-weight: 700;
    }

    /* Others wrapper */
    .dept-others-wrap {
        margin-top: 8px;
        padding: 16px;
        background: var(--dc-raised);
        border: 1px solid var(--dc-line);
        border-radius: var(--dc-radius-xs);
    }
    .dept-others-wrap label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--dc-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
        display: block;
    }

    @keyframes dcFadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dept-animate {
        animation: dcFadeUp 0.4s ease forwards;
        opacity: 0;
    }
    .dept-animate:nth-child(1) { animation-delay: 0.03s; }
    .dept-animate:nth-child(2) { animation-delay: 0.06s; }
    .dept-animate:nth-child(3) { animation-delay: 0.09s; }
    .dept-animate:nth-child(4) { animation-delay: 0.12s; }

    @media (prefers-reduced-motion: reduce) {
        .dept-animate { animation: none; opacity: 1; }
    }
</style>

<div class="dept-create-container">

    <!-- PAGE HEADER -->
    <div class="dept-create-header dept-animate">
        <div class="dept-create-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        </div>
        <div>
            <h1 class="dept-create-title">Create New Project</h1>
            <p class="dept-create-subtitle">Set up a new department project with location, budget, and timeline details.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="dept-alert dept-animate">
            <svg class="dept-alert-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('department.projects.store') }}" enctype="multipart/form-data" class="dept-create-grid">
        @csrf
        @php
            $standardTypes = ['Bridges', 'Buildings and Facilities', 'Flood Control and Drainage', 'Roads', 'Septage and Sewerage Plants', 'Water Provision and Storage'];
            $oldType = old('project_type');
            $isOtherType = $oldType && !in_array($oldType, $standardTypes);
        @endphp

        <!-- LEFT: Form Fields -->
        <div class="dept-create-main">

            <!-- Project Information -->
            <div class="dept-create-card dept-animate">
                <div class="dept-create-card-header">
                    <div class="dept-create-card-icon blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                    </div>
                    <div>
                        <h2>Project Information</h2>
                        <p>Basic details to identify and categorize the project.</p>
                    </div>
                </div>
                <div class="dept-create-card-body">
                    <div class="dept-create-row cols-2">
                        <div class="dept-field">
                            <label class="dept-field-label">Project Code <span class="required">*</span></label>
                            <div class="dept-input-wrap has-icon">
                                <input type="text" name="project_code" value="{{ old('project_code') }}" placeholder="e.g. PROJ-2024-001">
                                <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 3.75h15m-16.5 3.75h15m-16.5 3.75h15"/></svg>
                            </div>
                        </div>
                        <div class="dept-field">
                            <label class="dept-field-label">Project Type <span class="required">*</span></label>
                            <select id="project_type_select" required>
                                <option value="">-- Select Project Type --</option>
                                <option value="Bridges" @selected($oldType == 'Bridges')>Bridges</option>
                                <option value="Buildings and Facilities" @selected($oldType == 'Buildings and Facilities')>Buildings and Facilities</option>
                                <option value="Flood Control and Drainage" @selected($oldType == 'Flood Control and Drainage')>Flood Control and Drainage</option>
                                <option value="Roads" @selected($oldType == 'Roads')>Roads</option>
                                <option value="Septage and Sewerage Plants" @selected($oldType == 'Septage and Sewerage Plants')>Septage and Sewerage Plants</option>
                                <option value="Water Provision and Storage" @selected($oldType == 'Water Provision and Storage')>Water Provision and Storage</option>
                                <option value="Others" @selected($isOtherType)>Others</option>
                            </select>
                            <input type="hidden" id="project_type" name="project_type" value="{{ $oldType }}">
                            <div id="project_type_other_wrapper" class="dept-others-wrap" style="{{ $isOtherType ? '' : 'display: none;' }}">
                                <label>Please specify</label>
                                <input type="text" id="project_type_other" value="{{ $isOtherType ? $oldType : '' }}" placeholder="Enter project type">
                            </div>
                        </div>
                    </div>
                    <div class="dept-create-row">
                        <div class="dept-field">
                            <label class="dept-field-label">Project Name <span class="required">*</span></label>
                            <div class="dept-input-wrap has-icon">
                                <input type="text" name="project_name" value="{{ old('project_name') }}" placeholder="Enter the full project name">
                                <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="dept-create-row">
                        <div class="dept-field">
                            <label class="dept-field-label">Barangay</label>
                            <div class="dept-input-wrap has-icon">
                                <select id="barangay_id" name="barangay_id">
                                    <option value="">-- None / Citywide --</option>
                                    @foreach ($barangays as $barangay)
                                        <option value="{{ $barangay->barangay_id }}" data-name="{{ $barangay->barangay_name }}" @selected(old('barangay_id') == $barangay->barangay_id)>
                                            {{ $barangay->barangay_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <p class="dept-field-hint">Selecting a barangay will auto-center the map to that area.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline & Budget -->
            <div class="dept-create-card dept-animate">
                <div class="dept-create-card-header">
                    <div class="dept-create-card-icon emerald">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h2>Timeline & Budget</h2>
                        <p>Schedule and financial allocation for the project.</p>
                    </div>
                </div>
                <div class="dept-create-card-body">
                    <div class="dept-create-row cols-2">
                        <div class="dept-field">
                            <label class="dept-field-label">Start Date</label>
                            <div class="dept-input-wrap has-icon">
                                <input type="date" name="start_date" value="{{ old('start_date') }}">
                                <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                        </div>
                        <div class="dept-field">
                            <label class="dept-field-label">Target Completion</label>
                            <div class="dept-input-wrap has-icon">
                                <input type="date" name="target_end_date" value="{{ old('target_end_date') }}">
                                <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="dept-create-row cols-2">
                        <div class="dept-field">
                            <label class="dept-field-label">Proposed Budget</label>
                            <div class="dept-input-wrap has-icon">
                                <input type="number" step="0.01" name="approved_budget" value="{{ old('approved_budget') }}" placeholder="0.00">
                                <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="dept-field">
                            <label class="dept-field-label">Initial Status</label>
                            <div class="dept-field-readonly">
                                <span class="badge">Proposed</span>
                                <span style="font-size:0.75rem;color:var(--dc-muted);margin-left:4px;">Locked — changes after creation</span>
                            </div>
                            <input type="hidden" name="current_status" value="Proposed">
                            <p class="dept-field-hint">All new projects start at the Proposed stage. Other stages can be selected when editing the project.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descriptions -->
            <div class="dept-create-card dept-animate">
                <div class="dept-create-card-header">
                    <div class="dept-create-card-icon purple">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                    </div>
                    <div>
                        <h2>Project Descriptions</h2>
                        <p>Public-facing and internal notes about the project.</p>
                    </div>
                </div>
                <div class="dept-create-card-body">
                    <div class="dept-create-row">
                        <div class="dept-field">
                            <label class="dept-field-label">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Public Description
                            </label>
                            <p class="dept-field-hint">Shown on the transparency portal. Keep this general and non-sensitive.</p>
                            <textarea name="public_description" rows="3" placeholder="Describe the project in terms the public can understand...">{{ old('public_description') }}</textarea>
                        </div>
                    </div>
                    <div class="dept-create-row">
                        <div class="dept-field">
                            <label class="dept-field-label">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                Internal Remarks
                            </label>
                            <p class="dept-field-hint">Encrypted and only visible to authorized staff — not shown publicly.</p>
                            <textarea name="remarks" rows="3" placeholder="Add private notes, concerns, or internal context...">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="dept-create-actions dept-animate">
                <a href="{{ route('department.projects.index') }}" class="dept-btn dept-btn-secondary">Cancel</a>
                <button type="submit" id="createProjectButton" class="dept-btn dept-btn-primary">
                    <span id="createProjectSpinner" class="hidden inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        Saving...
                    </span>
                    <span id="createProjectLabel">Create Project</span>
                </button>
            </div>
        </div>

        <!-- RIGHT: Sidebar -->
        <div class="dept-create-sidebar">

            <!-- Map -->
            <div class="dept-map-card dept-animate">
                <div class="dept-map-header">
                    <h3>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        Project Location
                    </h3>
                    <p>Click on the map or drag the pin to set coordinates.</p>
                </div>
                <div class="dept-map-wrap">
                    <div id="project-location-map"></div>
                    <div class="dept-map-hint">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.817-3.345-1.734zm0 0V3.75m0 12.75h.008v.008H13.5v-.008z"/></svg>
                        Click anywhere on the map or drag the pin to update the location.
                    </div>
                </div>
                <div class="dept-map-footer">
                    <div class="dept-address-field">
                        <label>Selected Address</label>
                        <input id="project-address" type="text" readonly placeholder="Choose a location on the map..." value="{{ old('location_description') }}">
                    </div>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="dept-create-card dept-animate">
                <div class="dept-create-card-header">
                    <div class="dept-create-card-icon rose">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M18 13.5V9.75A2.25 2.25 0 0015.75 7.5H1.5M18 13.5l1.409 1.409a2.25 2.25 0 013.182 0l2.909-2.909M3 19.5h18M4.5 15.75h.008v.008H4.5v-.008z"/></svg>
                    </div>
                    <div>
                        <h2>Project Image</h2>
                        <p>Optional photo or rendering of the project.</p>
                    </div>
                </div>
                <div class="dept-create-card-body">
                    <div class="dept-upload-zone" onclick="document.getElementById('projectImageInput').click()">
                        <div class="dept-upload-icon">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        </div>
                        <h4>Click to upload an image</h4>
                        <p>PNG, JPG, or WEBP up to 5MB</p>
                        <input type="file" id="projectImageInput" name="project_image" accept="image/*">
                    </div>
                </div>
            </div>

            <input id="project-address-value" type="hidden" name="location_description" value="{{ old('location_description') }}">
            <input id="project-latitude" type="hidden" name="latitude" value="{{ old('latitude') }}">
            <input id="project-longitude" type="hidden" name="longitude" value="{{ old('longitude') }}">
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route('department.projects.store') }}"]');
        const submitButton = document.getElementById('createProjectButton');
        const submitSpinner = document.getElementById('createProjectSpinner');
        const submitLabel = document.getElementById('createProjectLabel');

        if (form && submitButton) {
            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-60', 'cursor-not-allowed');
                submitSpinner.classList.remove('hidden');
                submitSpinner.classList.add('inline-flex');
                submitLabel.classList.add('hidden');
            });
        }

        // Project type "Others" toggle logic
        const projectTypeSelect = document.getElementById('project_type_select');
        const projectTypeHidden = document.getElementById('project_type');
        const projectTypeOtherWrapper = document.getElementById('project_type_other_wrapper');
        const projectTypeOtherInput = document.getElementById('project_type_other');

        function syncProjectType() {
            if (projectTypeSelect.value === 'Others') {
                projectTypeOtherWrapper.style.display = 'block';
                projectTypeHidden.value = projectTypeOtherInput.value;
            } else {
                projectTypeOtherWrapper.style.display = 'none';
                projectTypeHidden.value = projectTypeSelect.value;
            }
        }

        projectTypeSelect.addEventListener('change', syncProjectType);
        projectTypeOtherInput.addEventListener('input', function() {
            projectTypeHidden.value = projectTypeOtherInput.value;
        });
        syncProjectType();

        const latitudeInput = document.getElementById('project-latitude');
        const longitudeInput = document.getElementById('project-longitude');
        const addressDisplay = document.getElementById('project-address');
        const addressInput = document.getElementById('project-address-value');
        const barangaySelect = document.getElementById('barangay_id');
        const initialBarangayId = @json(old('barangay_id', ''));
        const initialLatitude = @json(old('latitude', ''));
        const initialLongitude = @json(old('longitude', ''));
        const initialAddress = @json(old('location_description', ''));

        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(function(response) {
                if (!response.ok) { throw new Error('Unable to load Cabuyao GeoJSON'); }
                return response.json();
            })
            .then(function(geojson) {
                const cabuyaoLayer = L.geoJSON(geojson);
                const cabuyaoBounds = cabuyaoLayer.getBounds();
                const defaultLocation = cabuyaoBounds.getCenter();

                const locationMap = L.map('project-location-map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(locationMap);

                L.geoJSON(geojson, {
                    style: { color: '#162347', weight: 1, fillOpacity: 0.05 }
                }).addTo(locationMap);

                locationMap.fitBounds(cabuyaoBounds, { padding: [16, 16] });
                locationMap.setMinZoom(locationMap.getZoom());

                const marker = L.marker(defaultLocation, { draggable: true }).addTo(locationMap);

                const barangayLookup = {};
                geojson.features.forEach(function(feature) {
                    if (!feature.properties || !feature.properties.name) return;
                    const layer = L.geoJSON(feature);
                    barangayLookup[feature.properties.name] = {
                        bounds: layer.getBounds(),
                        center: layer.getBounds().getCenter(),
                    };
                });

                function setAddress(value, persist = true) {
                    addressDisplay.value = value;
                    if (persist) addressInput.value = value;
                }

                function updateProjectAddress(latlng) {
                    setAddress('Finding address...', false);
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
                        .then(r => { if (!r.ok) throw new Error('Address lookup failed'); return r.json(); })
                        .then(data => setAddress(data.display_name || 'Address unavailable for this location'))
                        .catch(() => setAddress('Address unavailable. Location pin has still been saved.'));
                }

                function setProjectLocation(latlng, label = null) {
                    if (!cabuyaoBounds.contains(latlng)) return;
                    marker.setLatLng(latlng);
                    latitudeInput.value = latlng.lat.toFixed(6);
                    longitudeInput.value = latlng.lng.toFixed(6);
                    if (label) setAddress(label);
                    else updateProjectAddress(latlng);
                }

                function applyBarangaySelection(barangayId) {
                    const selectedOption = barangaySelect.options[barangaySelect.selectedIndex];
                    const selectedName = selectedOption?.dataset.name || selectedOption?.text || '';

                    if (!barangayId) {
                        setProjectLocation(defaultLocation);
                        if (initialAddress) setAddress(initialAddress);
                        else addressDisplay.value = 'Use map or choose a barangay';
                        return;
                    }

                    const match = barangayLookup[selectedName];
                    if (match) {
                        locationMap.fitBounds(match.bounds, { padding: [24, 24] });
                        setProjectLocation(match.center, `${selectedName}, Cabuyao City`);
                    } else {
                        setAddress(selectedName || 'Selected barangay');
                    }
                }

                barangaySelect?.addEventListener('change', function() {
                    applyBarangaySelection(this.value);
                });

                if (initialLatitude && initialLongitude) {
                    setProjectLocation(L.latLng(parseFloat(initialLatitude), parseFloat(initialLongitude)), initialAddress || null);
                } else if (initialBarangayId) {
                    applyBarangaySelection(initialBarangayId);
                } else {
                    setProjectLocation(defaultLocation);
                    setAddress(initialAddress || 'Use map or choose a barangay');
                }

                locationMap.on('click', e => setProjectLocation(e.latlng));
                marker.on('dragend', e => setProjectLocation(e.target.getLatLng()));
                setTimeout(() => locationMap.invalidateSize(), 100);
            })
            .catch(() => { addressDisplay.value = 'Unable to load Cabuyao map data.'; });
    });
</script>

@endsection