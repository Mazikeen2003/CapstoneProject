@extends('layouts.department')

@section('content')

<style>
    .dept-proj-container {
        --dp-bg: #f8f7f5;
        --dp-surface: #ffffff;
        --dp-surface-hover: #fafaf9;
        --dp-ink: #1e1b4b;
        --dp-ink-secondary: #374151;
        --dp-muted: #9ca3af;
        --dp-line: rgba(0,0,0,0.06);
        --dp-line-hover: rgba(0,0,0,0.12);
        --dp-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --dp-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --dp-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --dp-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --dp-radius: 16px;
        --dp-radius-sm: 12px;
        --dp-radius-xs: 8px;
    }
    .dark .dept-proj-container {
        --dp-bg: #0f0e1a;
        --dp-surface: #1a1929;
        --dp-surface-hover: #222136;
        --dp-ink: #f8fafc;
        --dp-ink-secondary: #cbd5e1;
        --dp-muted: #64748b;
        --dp-line: rgba(255,255,255,0.06);
        --dp-line-hover: rgba(255,255,255,0.12);
        --dp-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
        --dp-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.4), 0 1px 2px -1px rgb(0 0 0 / 0.4);
        --dp-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
        --dp-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
    }

    .dept-proj-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        background: var(--dp-bg);
        color: var(--dp-ink);
        transition: background 0.3s, color 0.3s;
    }
    @media (min-width: 640px) { .dept-proj-container { padding: 32px; } }
    @media (min-width: 1024px) { .dept-proj-container { padding: 40px; } }

    /* Page header */
    .dept-proj-header {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 28px;
    }
    @media (min-width: 640px) {
        .dept-proj-header { flex-direction: row; align-items: flex-end; justify-content: space-between; }
    }

    .dept-proj-titlewrap {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .dept-proj-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.5);
    }
    .dark .dept-proj-icon { box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.3); }
    .dept-proj-icon svg { width: 26px; height: 26px; }

    .dept-proj-title {
        font-family: "Plus Jakarta Sans", "Inter", sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
        color: var(--dp-ink);
    }
    .dept-proj-subtitle {
        font-size: 0.875rem;
        color: var(--dp-muted);
        margin-top: 4px;
    }

    .dept-proj-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 700;
        border: none;
        border-radius: 100px;
        cursor: pointer;
        box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.5);
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .dept-proj-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -4px rgba(245, 158, 11, 0.6);
    }
    .dept-proj-btn-primary svg { width: 18px; height: 18px; }

    /* Toolbar */
    .dept-proj-toolbar {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
        padding: 16px;
        background: var(--dp-surface);
        border: 1px solid var(--dp-line);
        border-radius: var(--dp-radius-sm);
        box-shadow: var(--dp-shadow-sm);
    }
    @media (min-width: 640px) {
        .dept-proj-toolbar { flex-direction: row; align-items: center; justify-content: space-between; }
    }

    .dept-proj-search {
        position: relative;
        flex: 1;
        max-width: 400px;
    }
    .dept-proj-search svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--dp-muted);
        pointer-events: none;
    }
    .dept-proj-search input {
        width: 100%;
        padding: 10px 14px 10px 42px;
        border: 1px solid var(--dp-line);
        border-radius: 100px;
        background: var(--dp-bg);
        color: var(--dp-ink);
        font-family: inherit;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s;
    }
    .dept-proj-search input:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
    }
    .dept-proj-search input::placeholder { color: var(--dp-muted); }

    .dept-proj-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .dept-proj-filter {
        padding: 8px 16px;
        border: 1px solid var(--dp-line);
        border-radius: 100px;
        background: var(--dp-bg);
        color: var(--dp-ink-secondary);
        font-family: inherit;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .dept-proj-filter:hover { border-color: var(--dp-line-hover); background: var(--dp-surface-hover); }
    .dept-proj-filter.active {
        background: var(--dp-ink);
        color: white;
        border-color: var(--dp-ink);
    }

    html.dark-mode .dept-proj-filter,
    .dark .dept-proj-filter {
        background: rgba(15, 23, 42, 0.72);
        border-color: rgba(148, 163, 184, 0.18);
        color: #e5edf9;
    }

    html.dark-mode .dept-proj-filter:hover,
    .dark .dept-proj-filter:hover {
        background: #243247;
        border-color: rgba(148, 163, 184, 0.35);
    }

    html.dark-mode .dept-proj-filter.active,
    .dark .dept-proj-filter.active {
        background: #f8fafc;
        color: #1e1b4b;
        border-color: #f8fafc;
        box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.08);
    }

    /* Table card */
    .dept-proj-card {
        background: var(--dp-surface);
        border-radius: var(--dp-radius-sm);
        border: 1px solid var(--dp-line);
        box-shadow: var(--dp-shadow-sm);
        overflow: hidden;
        transition: background 0.3s, border-color 0.3s;
    }

    .dept-proj-tablewrap { overflow-x: auto; }
    .dept-proj-table {
        width: 100%;
        min-width: 800px;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }
    .dept-proj-table thead th {
        padding: 14px 20px;
        text-align: left;
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--dp-muted);
        background: var(--dp-surface-hover);
        border-bottom: 1px solid var(--dp-line);
        white-space: nowrap;
    }
    .dept-proj-table thead th:first-child { padding-left: 24px; }
    .dept-proj-table thead th:last-child { padding-right: 24px; text-align: right; }

    .dept-proj-table tbody td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--dp-line);
        color: var(--dp-ink-secondary);
        transition: background 0.15s;
    }
    .dept-proj-table tbody td:first-child { padding-left: 24px; }
    .dept-proj-table tbody td:last-child { padding-right: 24px; }

    .dept-proj-table tbody tr { transition: background 0.15s, transform 0.15s; }
    .dept-proj-table tbody tr:hover { background: var(--dp-surface-hover); }
    .dept-proj-table tbody tr:last-child td { border-bottom: none; }

    /* Project name cell */
    .dept-proj-namecell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .dept-proj-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 0.75rem;
        font-weight: 800;
        color: white;
        flex-shrink: 0;
    }
    .dept-proj-nametext { min-width: 0; }
    .dept-proj-nametext .name {
        font-weight: 700;
        color: var(--dp-ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .dept-proj-nametext .code {
        font-size: 0.75rem;
        color: var(--dp-muted);
        margin-top: 2px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    /* Status badges */
    .dept-proj-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }
    .dept-proj-status::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .dept-proj-status-planning { background: #fef3c7; color: #b45309; }
    .dept-proj-status-ongoing { background: #dbeafe; color: #1d4ed8; }
    .dept-proj-status-on-hold { background: #fee2e2; color: #b91c1c; }
    .dept-proj-status-completed { background: #d1fae5; color: #047857; }
    .dept-proj-status-cancelled { background: #f3f4f6; color: #4b5563; }

    .dark .dept-proj-status-planning { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .dark .dept-proj-status-ongoing { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dark .dept-proj-status-on-hold { background: rgba(239,68,68,0.15); color: #f87171; }
    .dark .dept-proj-status-completed { background: rgba(16,185,129,0.15); color: #34d399; }
    .dark .dept-proj-status-cancelled { background: rgba(107,114,128,0.15); color: #9ca3af; }

    .dept-proj-budget {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-weight: 700;
        color: var(--dp-ink);
        white-space: nowrap;
    }
    .dept-proj-barangay {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8125rem;
    }
    .dept-proj-barangay svg {
        width: 14px;
        height: 14px;
        color: var(--dp-muted);
    }

    /* Actions */
    .dept-proj-actions {
        display: inline-flex;
        gap: 6px;
        justify-content: flex-end;
    }
    .dept-proj-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s;
        border: 1px solid transparent;
        cursor: pointer;
        font-family: inherit;
    }
    .dept-proj-action-view {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: rgba(59,130,246,0.2);
    }
    .dept-proj-action-view:hover { background: #3b82f6; color: white; }
    .dark .dept-proj-action-view { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dark .dept-proj-action-view:hover { background: #3b82f6; color: white; }

    .dept-proj-action-edit {
        background: #fef3c7;
        color: #b45309;
        border-color: rgba(245,158,11,0.2);
    }
    .dept-proj-action-edit:hover { background: #f59e0b; color: white; }
    .dark .dept-proj-action-edit { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .dark .dept-proj-action-edit:hover { background: #f59e0b; color: #1e1b4b; }

    .dept-proj-action-delete {
        background: #ffe4e6;
        color: #be123c;
        border-color: rgba(244,63,94,0.2);
    }
    .dept-proj-action-delete:hover { background: #f43f5e; color: white; }
    .dark .dept-proj-action-delete { background: rgba(244,63,94,0.15); color: #fb7185; }
    .dark .dept-proj-action-delete:hover { background: #f43f5e; color: white; }

    /* Mobile cards */
    .dept-proj-mobile { display: flex; flex-direction: column; gap: 12px; }
    @media (min-width: 768px) { .dept-proj-mobile { display: none; } }
    @media (min-width: 768px) { .dept-proj-desktop { display: block; } }
    @media (max-width: 767.98px) { .dept-proj-desktop { display: none; } }

    .dept-proj-mcard {
        background: var(--dp-surface);
        border: 1px solid var(--dp-line);
        border-radius: var(--dp-radius-sm);
        padding: 20px;
        box-shadow: var(--dp-shadow-sm);
        transition: all 0.2s ease;
    }
    .dept-proj-mcard:hover {
        box-shadow: var(--dp-shadow-md);
        border-color: var(--dp-line-hover);
    }
    .dept-proj-mheader {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }
    .dept-proj-mtitle {
        font-weight: 700;
        font-size: 0.9375rem;
        color: var(--dp-ink);
        line-height: 1.3;
    }
    .dept-proj-mcode {
        font-size: 0.6875rem;
        color: var(--dp-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-top: 4px;
    }
    .dept-proj-mmeta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 16px;
    }
    .dept-proj-mmeta-item { display: flex; flex-direction: column; gap: 4px; }
    .dept-proj-mlabel {
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--dp-muted);
    }
    .dept-proj-mvalue {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--dp-ink-secondary);
    }
    .dept-proj-mactions {
        display: flex;
        gap: 8px;
        padding-top: 14px;
        border-top: 1px solid var(--dp-line);
    }
    .dept-proj-mactions .dept-proj-action { flex: 1; justify-content: center; padding: 9px 12px; font-size: 0.8125rem; }

    /* Empty state */
    .dept-proj-empty {
        text-align: center;
        padding: 64px 24px;
    }
    .dept-proj-empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        border-radius: var(--dp-radius);
        background: linear-gradient(135deg, #f59e0b20, #d9770620);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d97706;
    }
    .dark .dept-proj-empty-icon { background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(217,119,6,0.1)); }
    .dept-proj-empty-icon svg { width: 36px; height: 36px; }
    .dept-proj-empty h3 {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--dp-ink);
        margin-bottom: 6px;
    }
    .dept-proj-empty p {
        font-size: 0.875rem;
        color: var(--dp-muted);
        margin-bottom: 20px;
    }

    /* Pagination */
    .dept-proj-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        border-top: 1px solid var(--dp-line);
    }
    .dept-proj-pageinfo {
        font-size: 0.8125rem;
        color: var(--dp-muted);
    }
    .dept-proj-pageinfo strong { color: var(--dp-ink); }
    .dept-proj-pagebtns { display: flex; gap: 6px; }
    .dept-proj-pagebtn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--dp-line);
        background: var(--dp-surface);
        color: var(--dp-ink-secondary);
        font-family: inherit;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
    }
    .dept-proj-pagebtn:hover { border-color: var(--dp-line-hover); background: var(--dp-surface-hover); }
    .dept-proj-pagebtn.active { background: var(--dp-ink); color: white; border-color: var(--dp-ink); }
    .dept-proj-pagebtn:disabled { opacity: 0.4; cursor: not-allowed; }

    /* Delete modal */
    .dept-proj-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 14, 26, 0.7);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .dept-proj-modal.show { display: flex; opacity: 1; }
    .dept-proj-modalbox {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28), 0 8px 20px rgba(15, 23, 42, 0.12);
        width: 100%;
        max-width: 420px;
        padding: 28px;
        color: #0f172a;
        transform: scale(0.95);
        transition: transform 0.2s ease;
    }
    .dept-proj-modal.show .dept-proj-modalbox { transform: scale(1); }
    html.dark-mode .dept-proj-modalbox {
        background: #0f172a;
        border-color: #334155;
        box-shadow: 0 24px 60px rgba(2, 6, 23, 0.55), 0 8px 20px rgba(2, 6, 23, 0.35);
        color: #f8fafc;
    }

    .dept-proj-modalicon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: #ffe4e6;
        color: #be123c;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    .dark .dept-proj-modalicon { background: rgba(244,63,94,0.15); color: #fb7185; }
    .dept-proj-modalicon svg { width: 26px; height: 26px; }

    .dept-proj-modaltitle {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--dp-ink);
        margin-bottom: 8px;
    }
    .dept-proj-modaldesc {
        font-size: 0.875rem;
        color: var(--dp-muted);
        line-height: 1.6;
        margin-bottom: 24px;
    }
    .dept-proj-modalactions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    .dept-proj-btn-secondary {
        padding: 10px 20px;
        border: 1px solid var(--dp-line);
        border-radius: 10px;
        background: var(--dp-surface);
        color: var(--dp-ink-secondary);
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s;
    }
    .dept-proj-btn-secondary:hover { background: var(--dp-surface-hover); border-color: var(--dp-line-hover); }

    .dept-proj-btn-danger {
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
        color: white;
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s;
        box-shadow: 0 4px 12px -4px rgba(244, 63, 94, 0.5);
    }
    .dept-proj-btn-danger:hover { transform: translateY(-1px); box-shadow: 0 6px 16px -4px rgba(244, 63, 94, 0.6); }
    .dept-proj-btn-danger:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    @keyframes deptFadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dept-proj-animate {
        animation: deptFadeUp 0.4s ease forwards;
        opacity: 0;
    }
    .dept-proj-animate:nth-child(1) { animation-delay: 0.03s; }
    .dept-proj-animate:nth-child(2) { animation-delay: 0.06s; }
    .dept-proj-animate:nth-child(3) { animation-delay: 0.09s; }

    @media (prefers-reduced-motion: reduce) {
        .dept-proj-animate { animation: none; opacity: 1; }
    }
</style>

<div class="dept-proj-container">

    <!-- PAGE HEADER -->
    <div class="dept-proj-header dept-proj-animate">
        <div class="dept-proj-titlewrap">
            <div class="dept-proj-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/>
                </svg>
            </div>
            <div>
                <h1 class="dept-proj-title">Department Projects</h1>
                <p class="dept-proj-subtitle">Manage and track all department projects across Cabuyao City.</p>
            </div>
        </div>
        <a href="{{ route('department.projects.create') }}" class="dept-proj-btn-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add New Project
        </a>
    </div>

    <!-- TOOLBAR -->
    <div class="dept-proj-toolbar dept-proj-animate">
        <div class="dept-proj-search">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="text" id="projectSearch" placeholder="Search projects by name or code...">
        </div>
        <div class="dept-proj-filters">
            <button class="dept-proj-filter active" data-filter="all">All</button>
            <button class="dept-proj-filter" data-filter="Planning">Planning</button>
            <button class="dept-proj-filter" data-filter="On Going">On Going</button>
            <button class="dept-proj-filter" data-filter="Completed">Completed</button>
            <button class="dept-proj-filter" data-filter="On Hold">On Hold</button>
        </div>
    </div>

    <!-- PROJECTS CARD -->
    <div class="dept-proj-card dept-proj-animate">

        @if ($projects->isEmpty())
            <div class="dept-proj-empty">
                <div class="dept-proj-empty-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <h3>No projects yet</h3>
                <p>Get started by creating your first department project.</p>
                <a href="{{ route('department.projects.create') }}" class="dept-proj-btn-primary">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add New Project
                </a>
            </div>
        @else

            <!-- Desktop Table -->
            <div class="dept-proj-desktop">
                <div class="dept-proj-tablewrap">
                    <table class="dept-proj-table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Barangay</th>
                                <th>Budget</th>
                                <th style="text-align:right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                @php
                                    $statusClass = match($project->current_status) {
                                        'Planning' => 'dept-proj-status-planning',
                                        'On Going' => 'dept-proj-status-ongoing',
                                        'On Hold' => 'dept-proj-status-on-hold',
                                        'Completed' => 'dept-proj-status-completed',
                                        'Cancelled' => 'dept-proj-status-cancelled',
                                        default => 'dept-proj-status-planning',
                                    };
                                    $words = explode(' ', $project->project_name);
                                    $initials = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
                                    $avatarGradients = [
                                        'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                                        'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
                                        'linear-gradient(135deg, #10b981 0%, #047857 100%)',
                                        'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)',
                                        'linear-gradient(135deg, #f43f5e 0%, #be123c 100%)',
                                    ];
                                    $avatarBg = $avatarGradients[$loop->index % count($avatarGradients)];
                                @endphp
                                <tr class="project-row" data-status="{{ $project->current_status }}" data-name="{{ strtolower($project->project_name) }}" data-code="{{ strtolower($project->project_code) }}">
                                    <td>
                                        <div class="dept-proj-namecell">
                                            <div class="dept-proj-avatar" style="background: {{ $avatarBg }}">{{ $initials }}</div>
                                            <div class="dept-proj-nametext">
                                                <div class="name">{{ $project->project_name }}</div>
                                                <div class="code">{{ $project->project_code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="dept-proj-status {{ $statusClass }}">{{ $project->current_status }}</span></td>
                                    <td>
                                        <span class="dept-proj-barangay">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                            {{ $project->barangay?->barangay_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="dept-proj-budget">₱{{ number_format($project->approved_budget ?? 0, 2) }}</td>
                                    <td style="text-align:right">
                                        <div class="dept-proj-actions">
                                            <a href="{{ route('department.projects.show', $project->project_id) }}" class="dept-proj-action dept-proj-action-view">View</a>
                                            <a href="{{ route('department.projects.edit', $project->project_id) }}" class="dept-proj-action dept-proj-action-edit">Edit</a>
                                            <button type="button" class="dept-proj-action dept-proj-action-delete delete-trigger"
                                                data-name="{{ $project->project_name }}"
                                                data-code="{{ $project->project_code }}"
                                                data-action="{{ route('department.projects.destroy', $project->project_id) }}"
                                                data-token="{{ csrf_token() }}">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($projects, 'links'))
                <div class="dept-proj-pagination">
                    <div class="dept-proj-pageinfo">Showing <strong>{{ $projects->firstItem() ?? 1 }}-{{ $projects->lastItem() ?? $projects->count() }}</strong> of <strong>{{ $projects->total() ?? $projects->count() }}</strong> projects</div>
                    <div class="dept-proj-pagebtns">
                        {{ $projects->links('vendor.pagination.custom') }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Mobile Cards -->
            <div class="dept-proj-mobile">
                @foreach ($projects as $project)
                    @php
                        $statusClass = match($project->current_status) {
                            'Planning' => 'dept-proj-status-planning',
                            'On Going' => 'dept-proj-status-ongoing',
                            'On Hold' => 'dept-proj-status-on-hold',
                            'Completed' => 'dept-proj-status-completed',
                            'Cancelled' => 'dept-proj-status-cancelled',
                            default => 'dept-proj-status-planning',
                        };
                        $words = explode(' ', $project->project_name);
                        $initials = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
                        $avatarGradients = [
                            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                            'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
                            'linear-gradient(135deg, #10b981 0%, #047857 100%)',
                            'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)',
                            'linear-gradient(135deg, #f43f5e 0%, #be123c 100%)',
                        ];
                        $avatarBg = $avatarGradients[$loop->index % count($avatarGradients)];
                    @endphp
                    <div class="dept-proj-mcard dept-proj-animate project-row" data-status="{{ $project->current_status }}" data-name="{{ strtolower($project->project_name) }}" data-code="{{ strtolower($project->project_code) }}">
                        <div class="dept-proj-mheader">
                            <div style="min-width:0">
                                <div class="dept-proj-mtitle">{{ $project->project_name }}</div>
                                <div class="dept-proj-mcode">{{ $project->project_code }}</div>
                            </div>
                            <span class="dept-proj-status {{ $statusClass }}">{{ $project->current_status }}</span>
                        </div>
                        <div class="dept-proj-mmeta">
                            <div class="dept-proj-mmeta-item">
                                <span class="dept-proj-mlabel">Barangay</span>
                                <span class="dept-proj-mvalue">{{ $project->barangay?->barangay_name ?? 'N/A' }}</span>
                            </div>
                            <div class="dept-proj-mmeta-item">
                                <span class="dept-proj-mlabel">Budget</span>
                                <span class="dept-proj-mvalue" style="font-family:'Plus Jakarta Sans',sans-serif; color:var(--dp-ink)">₱{{ number_format($project->approved_budget ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="dept-proj-mactions">
                            <a href="{{ route('department.projects.show', $project->project_id) }}" class="dept-proj-action dept-proj-action-view">View</a>
                            <a href="{{ route('department.projects.edit', $project->project_id) }}" class="dept-proj-action dept-proj-action-edit">Edit</a>
                            <button type="button" class="dept-proj-action dept-proj-action-delete delete-trigger"
                                data-name="{{ $project->project_name }}"
                                data-code="{{ $project->project_code }}"
                                data-action="{{ route('department.projects.destroy', $project->project_id) }}"
                                data-token="{{ csrf_token() }}">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif
    </div>
</div>

<!-- DELETE MODAL -->
<div class="dept-proj-modal" id="deleteConfirmModal">
    <div class="dept-proj-modalbox">
        <div class="dept-proj-modalicon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
        </div>
        <h3 class="dept-proj-modaltitle">Delete Project?</h3>
        <p class="dept-proj-modaldesc" id="deleteModalDescription">This action cannot be undone. The project and all associated data will be permanently removed.</p>
        <form id="deleteProjectForm" method="POST" class="dept-proj-modalactions">
            @csrf
            @method('DELETE')
            <button type="button" class="dept-proj-btn-secondary" id="cancelDeleteBtn">Cancel</button>
            <button type="submit" class="dept-proj-btn-danger" id="confirmDeleteBtn">
                <span class="delete-label">Delete Project</span>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('deleteConfirmModal');
        const modalDesc = document.getElementById('deleteModalDescription');
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        const deleteForm = document.getElementById('deleteProjectForm');
        const searchInput = document.getElementById('projectSearch');
        const filterBtns = document.querySelectorAll('.dept-proj-filter');
        const projectRows = document.querySelectorAll('.project-row');

        // Delete modal logic
        document.querySelectorAll('.delete-trigger').forEach(function (button) {
            button.addEventListener('click', function () {
                const projectName = button.dataset.name || 'this project';
                const projectCode = button.dataset.code ? ' (Code: ' + button.dataset.code + ')' : '';
                modalDesc.textContent = 'Delete "' + projectName + '"' + projectCode + '? This action cannot be undone. The project and all associated data will be permanently removed.';
                deleteForm.action = button.dataset.action;

                // Inject CSRF token dynamically
                let tokenInput = deleteForm.querySelector('input[name=\"_token\"]');
                if (!tokenInput) {
                    tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    deleteForm.prepend(tokenInput);
                }
                tokenInput.value = button.dataset.token;

                modal.classList.add('show');
            });
        });

        function closeModal() {
            modal.classList.remove('show');
            confirmBtn.disabled = false;
            confirmBtn.querySelector('.delete-label').textContent = 'Delete Project';
        }

        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.classList.contains('show')) closeModal(); });

        deleteForm.addEventListener('submit', function () {
            confirmBtn.disabled = true;
            confirmBtn.querySelector('.delete-label').textContent = 'Deleting...';
        });

        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                projectRows.forEach(row => {
                    const name = row.dataset.name || '';
                    const code = row.dataset.code || '';
                    row.style.display = (name.includes(query) || code.includes(query)) ? '' : 'none';
                });
            });
        }

        // Filter functionality
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.filter;

                projectRows.forEach(row => {
                    if (filter === 'all' || row.dataset.status === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

@endsection