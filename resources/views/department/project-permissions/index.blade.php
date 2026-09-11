@extends('layouts.department')

@section('content')
<style>
    .perm-container {
        --pp-bg: #f8f7f5;
        --pp-surface: #ffffff;
        --pp-surface-hover: #fafaf9;
        --pp-ink: #1e1b4b;
        --pp-ink-secondary: #374151;
        --pp-muted: #9ca3af;
        --pp-line: rgba(0,0,0,0.06);
        --pp-line-hover: rgba(0,0,0,0.12);
        --pp-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --pp-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --pp-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --pp-radius: 12px;
        --pp-radius-sm: 12px;
    }

    html:not(.dark-mode) body:has(.perm-container) {
        background: #f8f7f5 !important;
    }

    html.dark-mode body:has(.perm-container) {
        background: #0f172a !important;
    }

    html.dark-mode .perm-container,
    .dark .perm-container {
        --pp-bg: #111827;
        --pp-surface: #1f2937;
        --pp-surface-hover: #263445;
        --pp-ink: #f9fafb;
        --pp-ink-secondary: #d1d5db;
        --pp-muted: #9ca3af;
        --pp-line: rgba(255, 255, 255, 0.08);
        --pp-line-hover: rgba(255, 255, 255, 0.14);
        --pp-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.16);
        --pp-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.2), 0 1px 2px -1px rgb(0 0 0 / 0.2);
        --pp-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.24), 0 2px 4px -2px rgb(0 0 0 / 0.24);
    }

    .perm-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        background: var(--pp-bg);
        color: var(--pp-ink);
        transition: background 0.3s, color 0.3s;
    }

    @keyframes permFadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .perm-animate {
        animation: permFadeUp 0.4s ease forwards;
        opacity: 0;
    }

    .perm-animate:nth-child(1) { animation-delay: 0.03s; }
    .perm-animate:nth-child(2) { animation-delay: 0.06s; }
    .perm-animate:nth-child(3) { animation-delay: 0.09s; }
    .perm-animate:nth-child(4) { animation-delay: 0.12s; }

    @media (prefers-reduced-motion: reduce) {
        .perm-animate { animation: none; opacity: 1; }
    }

    @media (min-width: 640px) {
        .perm-container { padding: 32px; }
    }

    @media (min-width: 1024px) {
        .perm-container { padding: 40px; }
    }

    .perm-header {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 28px;
    }

    @media (min-width: 640px) {
        .perm-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-end;
        }
    }

    .perm-title-wrap {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .perm-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.5);
        flex-shrink: 0;
    }

    .perm-icon svg {
        width: 26px;
        height: 26px;
    }

    .perm-title {
        font-family: "Plus Jakarta Sans", "Inter", sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
        color: var(--pp-ink);
    }

    .perm-subtitle {
        font-size: 0.875rem;
        color: var(--pp-muted);
        margin-top: 4px;
    }

    .perm-toolbar {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
        padding: 16px;
        background: #ffffff;
        border: 1px solid var(--pp-line);
        border-radius: var(--pp-radius);
        box-shadow: var(--pp-shadow-sm);
    }

    @media (min-width: 640px) {
        .perm-toolbar {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .perm-search {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .perm-search svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--pp-muted);
        pointer-events: none;
    }

    .perm-search input {
        width: 100%;
        padding: 10px 14px 10px 42px;
        border: 1px solid var(--pp-line);
        border-radius: 100px;
        background: #f9fafb;
        color: var(--pp-ink);
        font-family: inherit;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s;
    }

    .perm-search input:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
    }

    .perm-search input::placeholder {
        color: var(--pp-muted);
    }

    .perm-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .perm-filter {
        padding: 8px 16px;
        border: 1px solid var(--pp-line);
        border-radius: 100px;
        background: #f9fafb;
        color: var(--pp-ink-secondary);
        font-family: inherit;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .perm-filter:hover {
        border-color: var(--pp-line-hover);
        background: var(--pp-surface-hover);
    }

    .perm-filter.active {
        background: var(--pp-ink);
        color: #ffffff;
        border-color: var(--pp-ink);
        box-shadow: none;
    }

    html.dark-mode .perm-filter,
    .dark .perm-filter {
        background: rgba(15, 23, 42, 0.72);
        border-color: rgba(148, 163, 184, 0.18);
        color: #e5edf9;
    }

    html.dark-mode .perm-filter.active,
    .dark .perm-filter.active {
        background: #f8fafc;
        color: #1e1b4b;
        border-color: #f8fafc;
        box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.08);
    }

    html.dark-mode .perm-toolbar,
    .dark .perm-toolbar {
        background: #1a1929;
    }

    html.dark-mode .perm-search input,
    html.dark-mode .perm-filter,
    .dark .perm-search input,
    .dark .perm-filter {
        background: #0f0e1a;
    }

    .perm-toolbar {
        background: #ffffff;
    }

    .perm-search input,
    .perm-filter {
        background: #f4f4f5;
    }

    html.dark-mode .perm-toolbar,
    .dark .perm-toolbar {
        background: #141321;
    }

    html.dark-mode .perm-search input,
    html.dark-mode .perm-filter,
    .dark .perm-search input,
    .dark .perm-filter {
        background: #0f0e1a;
    }

    html.dark-mode .perm-filter:hover,
    .dark .perm-filter:hover {
        background: #222136;
        border-color: rgba(255, 255, 255, 0.12);
    }

    .perm-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    @media (min-width: 960px) {
        .perm-summary {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    .perm-stat {
        position: relative;
        background: var(--pp-surface);
        border: 1px solid var(--pp-line);
        border-radius: var(--pp-radius);
        padding: 16px 18px;
        box-shadow: var(--pp-shadow-sm);
        overflow: hidden;
    }

    .perm-stat::before {
        content: "";
        position: absolute;
        inset: 0 0 auto;
        height: 3px;
        background: var(--perm-stat-accent, #d97706);
    }

    .perm-stat.pending { --perm-stat-accent: #f59e0b; }
    .perm-stat.approved { --perm-stat-accent: #10b981; }
    .perm-stat.used { --perm-stat-accent: #3b82f6; }
    .perm-stat.rejected { --perm-stat-accent: #f43f5e; }

    .perm-stat.pending { background: #fef3c7; }
    .perm-stat.approved { background: #d1fae5; }
    .perm-stat.used { background: #dbeafe; }
    .perm-stat.rejected { background: #ffe4e6; }

    html.dark-mode .perm-stat.pending,
    .dark .perm-stat.pending { background: rgba(251, 191, 36, 0.15); }
    html.dark-mode .perm-stat.approved,
    .dark .perm-stat.approved { background: rgba(16, 185, 129, 0.15); }
    html.dark-mode .perm-stat.used,
    .dark .perm-stat.used { background: rgba(59, 130, 246, 0.15); }
    html.dark-mode .perm-stat.rejected,
    .dark .perm-stat.rejected { background: rgba(244, 63, 94, 0.15); }

    .perm-stat.pending .perm-stat-label { color: #b45309; }
    .perm-stat.approved .perm-stat-label { color: #047857; }
    .perm-stat.used .perm-stat-label { color: #1d4ed8; }
    .perm-stat.rejected .perm-stat-label { color: #be123c; }

    html.dark-mode .perm-stat.pending .perm-stat-label,
    .dark .perm-stat.pending .perm-stat-label { color: #fbbf24; }
    html.dark-mode .perm-stat.approved .perm-stat-label,
    .dark .perm-stat.approved .perm-stat-label { color: #34d399; }
    html.dark-mode .perm-stat.used .perm-stat-label,
    .dark .perm-stat.used .perm-stat-label { color: #60a5fa; }
    html.dark-mode .perm-stat.rejected .perm-stat-label,
    .dark .perm-stat.rejected .perm-stat-label { color: #fb7185; }

    .perm-stat.pending::after,
    .perm-stat.approved::after,
    .perm-stat.rejected::after {
        content: "";
        position: absolute;
        top: 16px;
        right: 18px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--perm-stat-accent);
        box-shadow: 0 0 0 4px color-mix(in srgb, var(--perm-stat-accent) 16%, transparent);
    }

    .perm-stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--pp-muted);
    }

    .perm-stat-value {
        margin-top: 10px;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--pp-ink);
    }

    .perm-card {
        background: var(--pp-surface);
        border: 1px solid var(--pp-line);
        border-radius: var(--pp-radius);
        box-shadow: var(--pp-shadow-sm);
        overflow: hidden;
    }

    .perm-table-wrap {
        overflow-x: auto;
    }

    .perm-table {
        width: 100%;
        min-width: 900px;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }

    .perm-table thead th {
        padding: 14px 20px;
        text-align: left;
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--pp-muted);
        background: var(--pp-surface-hover);
        border-bottom: 1px solid var(--pp-line);
        white-space: nowrap;
    }

    .perm-table thead th:first-child { padding-left: 24px; }
    .perm-table thead th:last-child { padding-right: 24px; }

    .perm-table tbody td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--pp-line);
        color: var(--pp-ink-secondary);
        vertical-align: top;
    }

    .perm-table tbody td:first-child { padding-left: 24px; }
    .perm-table tbody td:last-child { padding-right: 24px; }

    .perm-table tbody tr:hover {
        box-shadow: inset 3px 0 0 #f59e0b;
    }

    .perm-table tbody tr:hover td {
        background: #f3f4f6 !important;
    }

    html:not(.dark-mode) .perm-table tbody tr:hover td {
        background: #e5e7eb !important;
        color: #1f2937;
    }

    html.dark-mode .perm-table tbody tr:hover,
    .dark .perm-table tbody tr:hover {
        box-shadow: none;
    }

    html.dark-mode .perm-table tbody tr:hover td,
    .dark .perm-table tbody tr:hover td {
        background: var(--pp-surface) !important;
    }

    .perm-table tbody tr:last-child td {
        border-bottom: none;
    }

    .perm-project {
        font-weight: 700;
        color: var(--pp-ink);
    }

    .perm-user {
        font-weight: 600;
        color: var(--pp-ink-secondary);
    }

    .perm-field-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .perm-field-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        background: #fef3c7;
        color: #b45309;
        padding: 6px 10px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    html.dark-mode .perm-field-chip,
    .dark .perm-field-chip {
        background: rgba(251, 191, 36, 0.12);
        color: #fbbf24;
    }

    .perm-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        padding: 6px 10px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .perm-status.pending {
        background: #fef3c7;
        color: #b45309;
    }

    .perm-status.approved {
        background: #d1fae5;
        color: #047857;
    }

    .perm-status.used {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .perm-status.rejected {
        background: #ffe4e6;
        color: #be123c;
    }

    html.dark-mode .perm-status.pending,
    .dark .perm-status.pending {
        background: rgba(251, 191, 36, 0.15);
        color: #fbbf24;
    }

    html.dark-mode .perm-status.approved,
    .dark .perm-status.approved {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
    }

    html.dark-mode .perm-status.used,
    .dark .perm-status.used {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
    }

    html.dark-mode .perm-status.rejected,
    .dark .perm-status.rejected {
        background: rgba(244, 63, 94, 0.15);
        color: #fb7185;
    }

    .perm-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .perm-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid transparent;
        transition: all 0.15s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .perm-btn-approve {
        background: #d1fae5;
        color: #047857;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .perm-btn-approve:hover {
        background: #10b981;
        color: white;
    }

    html.dark-mode .perm-btn-approve,
    .dark .perm-btn-approve {
        background: rgba(16, 185, 129, 0.12);
        color: #34d399;
    }

    .perm-btn-reject {
        background: #ffe4e6;
        color: #be123c;
        border-color: rgba(244, 63, 94, 0.2);
    }

    .perm-btn-reject:hover {
        background: #f43f5e;
        color: white;
    }

    html.dark-mode .perm-btn-reject,
    .dark .perm-btn-reject {
        background: rgba(244, 63, 94, 0.12);
        color: #fb7185;
    }

    .perm-reviewed {
        color: var(--pp-muted);
        font-weight: 600;
    }

    .perm-empty {
        text-align: center;
        padding: 64px 24px;
    }

    .perm-empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        border-radius: var(--pp-radius);
        background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(217,119,6,0.08));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d97706;
    }

    .perm-empty-icon svg {
        width: 36px;
        height: 36px;
    }

    .perm-empty h3 {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pp-ink);
        margin-bottom: 6px;
    }

    .perm-empty p {
        font-size: 0.875rem;
        color: var(--pp-muted);
    }

    .perm-mobile { display: block; }
    .perm-desktop { display: none; }

    @media (min-width: 768px) {
        .perm-mobile { display: none; }
        .perm-desktop { display: block; }
    }

    .perm-mcard {
        background: var(--pp-surface);
        border: 1px solid var(--pp-line);
        border-radius: var(--pp-radius-sm);
        padding: 20px;
        box-shadow: var(--pp-shadow-sm);
        margin-bottom: 14px;
    }

    .perm-mcard:hover {
        background: #f3f4f6 !important;
        border-color: rgba(245, 158, 11, 0.35) !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08) !important;
        transform: translateY(-2px);
    }

    html:not(.dark-mode) .perm-mcard:hover {
        background: #e5e7eb !important;
        border-color: #f59e0b !important;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.14) !important;
    }

    html.dark-mode .perm-mcard:hover,
    .dark .perm-mcard:hover {
        background: var(--pp-surface) !important;
        border-color: var(--pp-line) !important;
        box-shadow: none !important;
    }

    .perm-mheader {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .perm-mtitle {
        font-weight: 700;
        font-size: 0.9375rem;
        color: var(--pp-ink);
        line-height: 1.3;
    }

    .perm-muser {
        margin-top: 2px;
        font-size: 0.75rem;
        color: var(--pp-muted);
    }

    .perm-mbody {
        display: grid;
        gap: 10px;
        margin-bottom: 14px;
    }

    .perm-mrow {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .perm-mlabel {
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--pp-muted);
    }

    .perm-mvalue {
        font-size: 0.8125rem;
        color: var(--pp-ink-secondary);
        line-height: 1.5;
    }

    .perm-mactions {
        display: flex;
        gap: 8px;
        padding-top: 12px;
        border-top: 1px solid var(--pp-line);
        flex-wrap: wrap;
    }

    .perm-mactions .perm-btn {
        flex: 1;
        min-width: 110px;
    }
</style>

<div class="perm-container">
    <div class="perm-header perm-animate">
        <div class="perm-title-wrap">
            <div class="perm-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/>
                </svg>
            </div>
            <div>
                <h1 class="perm-title">Project Edit Permissions</h1>
                <p class="perm-subtitle">Review department requests to edit critical project fields.</p>
            </div>
        </div>
    </div>

    <div class="perm-toolbar perm-animate">
        <div class="perm-search">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text" id="permSearch" placeholder="Search project or requester...">
        </div>
        <div class="perm-filters">
            <button class="perm-filter active" data-filter="all" type="button">All</button>
            <button class="perm-filter" data-filter="pending" type="button">Pending</button>
            <button class="perm-filter" data-filter="approved" type="button">Approved</button>
            <button class="perm-filter" data-filter="used" type="button">Used</button>
            <button class="perm-filter" data-filter="rejected" type="button">Rejected</button>
        </div>
    </div>

    <div class="perm-summary perm-animate">
        <div class="perm-stat pending">
            <div class="perm-stat-label">Pending</div>
            <div class="perm-stat-value">{{ $requests->where('status', 'pending')->count() }}</div>
        </div>
        <div class="perm-stat approved">
            <div class="perm-stat-label">Approved</div>
            <div class="perm-stat-value">{{ $requests->where('status', 'approved')->count() }}</div>
        </div>
        <div class="perm-stat used">
            <div class="perm-stat-label">Used</div>
            <div class="perm-stat-value">{{ $requests->where('status', 'used')->count() }}</div>
        </div>
        <div class="perm-stat rejected">
            <div class="perm-stat-label">Rejected</div>
            <div class="perm-stat-value">{{ $requests->where('status', 'rejected')->count() }}</div>
        </div>
    </div>

    @if ($requests->isEmpty())
        <div class="perm-card perm-empty">
            <div class="perm-empty-icon">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <h3>No permission requests yet</h3>
            <p>There are no project edit requests to review at the moment.</p>
        </div>
    @else
        <div class="perm-card perm-desktop perm-animate">
            <div class="perm-table-wrap">
                <table class="perm-table">
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Requested By</th>
                            <th>Fields</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            @php
                                $fields = $request->fields_requested;
                                if (is_string($fields)) {
                                    $decoded = json_decode($fields, true);
                                    $fields = is_array($decoded) ? $decoded : [$fields];
                                }
                                $fields = is_array($fields) ? $fields : [];
                                $fieldLabels = array_map(function ($field) {
                                    return match ($field) {
                                        'start_date' => 'Start Date',
                                        'target_end_date' => 'Target End Date',
                                        'approved_budget' => 'Approved Budget',
                                        'actual_budget' => 'Actual Budget',
                                        default => ucwords(str_replace('_', ' ', $field)),
                                    };
                                }, $fields);
                            @endphp
                            <tr class="perm-row" data-status="{{ $request->status }}" data-project="{{ strtolower($request->project->project_name ?? '') }}" data-user="{{ strtolower($request->requester->username ?? '') }}">
                                <td>
                                    <div class="perm-project">{{ $request->project->project_name ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="perm-user">{{ $request->requester->username ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="perm-field-list">
                                        @forelse ($fieldLabels as $label)
                                            <span class="perm-field-chip">{{ $label }}</span>
                                        @empty
                                            <span class="perm-reviewed">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>{{ $request->reason ?: '—' }}</td>
                                <td>
                                    <span class="perm-status {{ $request->status }}">{{ ucfirst($request->status) }}</span>
                                </td>
                                <td>
                                    @if ($request->status === 'pending')
                                        <div class="perm-actions">
                                            <form method="POST" action="{{ route('department.project-permissions.approve', $request->request_id) }}">
                                                @csrf
                                                <input type="hidden" name="review_notes" value="Approved by department head." />
                                                <button type="submit" class="perm-btn perm-btn-approve">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('department.project-permissions.reject', $request->request_id) }}">
                                                @csrf
                                                <input type="hidden" name="review_notes" value="Rejected by department head." />
                                                <button type="submit" class="perm-btn perm-btn-reject">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="perm-reviewed">Reviewed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="perm-mobile perm-animate">
            @foreach ($requests as $request)
                @php
                    $fields = $request->fields_requested;
                    if (is_string($fields)) {
                        $decoded = json_decode($fields, true);
                        $fields = is_array($decoded) ? $decoded : [$fields];
                    }
                    $fields = is_array($fields) ? $fields : [];
                    $fieldLabels = array_map(function ($field) {
                        return match ($field) {
                            'start_date' => 'Start Date',
                            'target_end_date' => 'Target End Date',
                            'approved_budget' => 'Approved Budget',
                            'actual_budget' => 'Actual Budget',
                            default => ucwords(str_replace('_', ' ', $field)),
                        };
                    }, $fields);
                @endphp
                <div class="perm-mcard perm-row" data-status="{{ $request->status }}" data-project="{{ strtolower($request->project->project_name ?? '') }}" data-user="{{ strtolower($request->requester->username ?? '') }}">
                    <div class="perm-mheader">
                        <div>
                            <div class="perm-mtitle">{{ $request->project->project_name ?? '—' }}</div>
                            <div class="perm-muser">Requested by {{ $request->requester->username ?? '—' }}</div>
                        </div>
                        <span class="perm-status {{ $request->status }}">{{ ucfirst($request->status) }}</span>
                    </div>
                    <div class="perm-mbody">
                        <div class="perm-mrow">
                            <span class="perm-mlabel">Fields</span>
                            <div class="perm-field-list">
                                @forelse ($fieldLabels as $label)
                                    <span class="perm-field-chip">{{ $label }}</span>
                                @empty
                                    <span class="perm-mvalue">—</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="perm-mrow">
                            <span class="perm-mlabel">Reason</span>
                            <div class="perm-mvalue">{{ $request->reason ?: '—' }}</div>
                        </div>
                    </div>
                    @if ($request->status === 'pending')
                        <div class="perm-mactions">
                            <form method="POST" action="{{ route('department.project-permissions.approve', $request->request_id) }}" style="flex:1;">
                                @csrf
                                <input type="hidden" name="review_notes" value="Approved by department head." />
                                <button type="submit" class="perm-btn perm-btn-approve" style="width:100%;">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('department.project-permissions.reject', $request->request_id) }}" style="flex:1;">
                                @csrf
                                <input type="hidden" name="review_notes" value="Rejected by department head." />
                                <button type="submit" class="perm-btn perm-btn-reject" style="width:100%;">Reject</button>
                            </form>
                        </div>
                    @else
                        <div class="perm-reviewed">Reviewed</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('permSearch');
        const filterButtons = document.querySelectorAll('.perm-filter');
        const rows = document.querySelectorAll('.perm-row');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                const activeFilter = document.querySelector('.perm-filter.active')?.dataset.filter || 'all';

                rows.forEach(function (row) {
                    const project = (row.dataset.project || '').toLowerCase();
                    const user = (row.dataset.user || '').toLowerCase();
                    const status = (row.dataset.status || '').toLowerCase();
                    const matchesQuery = project.includes(query) || user.includes(query);
                    const matchesFilter = activeFilter === 'all' || status === activeFilter;
                    row.style.display = matchesQuery && matchesFilter ? '' : 'none';
                });
            });
        }

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                filterButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                const filter = this.dataset.filter || 'all';
                const query = (searchInput?.value || '').toLowerCase();

                rows.forEach(function (row) {
                    const project = (row.dataset.project || '').toLowerCase();
                    const user = (row.dataset.user || '').toLowerCase();
                    const status = (row.dataset.status || '').toLowerCase();
                    const matchesQuery = project.includes(query) || user.includes(query);
                    const matchesFilter = filter === 'all' || status === filter;
                    row.style.display = matchesQuery && matchesFilter ? '' : 'none';
                });
            });
        });

        setInterval(function () {
            if (document.visibilityState === 'visible') {
                window.location.reload();
            }
        }, 15000);
    });
</script>
@endsection
