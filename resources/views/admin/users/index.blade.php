@extends('layouts.admin')

@section('content')
<style>
/* ===== USER ACCESS - RICH REDESIGN ===== */
.ua-wrap {
    --ua-bg: #f4f4f5;
    --ua-surface: #ffffff;
    --ua-ink: #0f0d1f;
    --ua-muted: #6b7280;
    --ua-line: rgba(0,0,0,0.06);
    --ua-line-strong: rgba(0,0,0,0.1);
    --ua-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.04);
    --ua-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.08), 0 4px 8px -4px rgba(0,0,0,0.04);
    --ua-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.14), 0 12px 24px -12px rgba(0,0,0,0.08);
    --ua-gold: #f59e0b;
    --ua-indigo: #4338ca;
    --ua-radius-xl: 28px;
    --ua-radius: 20px;
    --ua-radius-sm: 16px;
    --ua-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
}
.dark .ua-wrap {
    --ua-bg: #0f0e1a;
    --ua-surface: #141321;
    --ua-ink: #f8f7f5;
    --ua-muted: #94a3b8;
    --ua-line: rgba(255,255,255,0.06);
    --ua-line-strong: rgba(255,255,255,0.1);
    --ua-shadow: 0 1px 3px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.1);
    --ua-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.2), 0 4px 8px -4px rgba(0,0,0,0.15);
    --ua-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.35), 0 12px 24px -12px rgba(0,0,0,0.25);
}

.ua-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 24px 20px 48px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}
@media (min-width: 640px) {
    .ua-wrap { padding: 32px 32px 56px; }
}

/* ===== HERO ===== */
.ua-hero {
    position: relative;
    border-radius: var(--ua-radius-xl);
    padding: 36px 28px;
    overflow: hidden;
    background: linear-gradient(135deg, #0c0a1f 0%, #1e1b4b 30%, #312e81 65%, #4338ca 100%);
    box-shadow: var(--ua-shadow-lg), inset 0 1px 0 rgba(255,255,255,0.08), 0 0 100px -20px rgba(99,102,241,0.15);
}
@media (min-width: 640px) {
    .ua-hero { padding: 44px 40px; }
}
.ua-hero::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -60px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245,158,11,0.18) 0%, transparent 60%);
    pointer-events: none;
}
.ua-hero::after {
    content: '';
    position: absolute;
    bottom: -60px;
    left: -40px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 60%);
    pointer-events: none;
}
.ua-hero-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
}
.ua-hero-inner {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
@media (min-width: 768px) {
    .ua-hero-inner {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
    }
}
.ua-hero-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: #fbbf24;
    padding: 7px 16px;
    border-radius: 100px;
    background: rgba(245,158,11,0.1);
    border: 1px solid rgba(245,158,11,0.15);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    width: fit-content;
}
.ua-hero-label::before {
    content: '';
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #fbbf24;
    box-shadow: 0 0 0 3px rgba(251,191,36,0.2);
}
.ua-hero-title {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.03em;
    line-height: 1.1;
    margin-top: 12px;
}
@media (min-width: 640px) {
    .ua-hero-title { font-size: 2.75rem; }
}
.ua-hero-desc {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    color: rgba(255,255,255,0.55);
    line-height: 1.65;
    margin-top: 10px;
    max-width: 480px;
}
.ua-hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: 100px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #ffffff;
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 8px 24px -6px rgba(245,158,11,0.35);
    transition: var(--ua-transition);
    flex-shrink: 0;
    border: none;
    cursor: pointer;
}
.ua-hero-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px -6px rgba(245,158,11,0.45);
    filter: brightness(1.08);
}
.ua-hero-btn svg { width: 18px; height: 18px; }

/* ===== ALERTS ===== */
.ua-alert {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 18px 24px;
    border-radius: var(--ua-radius-sm);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.5;
    animation: uaFadeUp 0.4s ease forwards;
}
.ua-alert-success {
    background: rgba(16,185,129,0.06);
    border: 1px solid rgba(16,185,129,0.12);
    color: #059669;
}
.dark .ua-alert-success {
    background: rgba(16,185,129,0.04);
    border-color: rgba(16,185,129,0.1);
    color: #34d399;
}
.ua-alert-warning {
    background: rgba(245,158,11,0.06);
    border: 1px solid rgba(245,158,11,0.12);
    color: #d97706;
}
.dark .ua-alert-warning {
    background: rgba(245,158,11,0.04);
    border-color: rgba(245,158,11,0.1);
    color: #fbbf24;
}
.ua-alert-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ua-alert-success .ua-alert-icon { background: rgba(16,185,129,0.1); }
.ua-alert-warning .ua-alert-icon { background: rgba(245,158,11,0.1); }
.ua-alert-icon svg { width: 18px; height: 18px; }

/* ===== FILTER CARD ===== */
.ua-filter {
    background: var(--ua-surface);
    border: 1px solid var(--ua-line);
    border-radius: var(--ua-radius);
    padding: 24px 28px;
    box-shadow: var(--ua-shadow);
    position: relative;
    overflow: hidden;
}
.ua-filter::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    border-radius: 3px 3px 0 0;
}
.ua-filter-inner {
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: relative;
    z-index: 1;
}
@media (min-width: 640px) {
    .ua-filter-inner {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
    }
}
.ua-filter-field label {
    display: block;
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--ua-muted);
    margin-bottom: 8px;
}
.ua-filter-select {
    width: 100%;
    max-width: 280px;
    padding: 11px 16px;
    border-radius: 12px;
    border: 1px solid var(--ua-line-strong);
    background: var(--ua-bg);
    color: var(--ua-ink);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--ua-transition);
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19 9-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px;
    padding-right: 44px;
}
.ua-filter-select:focus {
    outline: none;
    border-color: var(--ua-gold);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.1);
}
.ua-filter-actions {
    display: flex;
    gap: 10px;
}
.ua-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 22px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--ua-transition);
    border: none;
    text-decoration: none;
}
.ua-btn-primary {
    background: linear-gradient(135deg, #1e1b4b, #4338ca);
    color: #ffffff;
    box-shadow: 0 4px 16px -4px rgba(67,56,202,0.3);
}
.ua-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px -4px rgba(67,56,202,0.4);
    filter: brightness(1.1);
}
.ua-btn-ghost {
    background: var(--ua-bg);
    color: var(--ua-muted);
    border: 1px solid var(--ua-line-strong);
}
.ua-btn-ghost:hover {
    background: var(--ua-line);
    color: var(--ua-ink);
    transform: translateY(-1px);
}

/* ===== MOBILE CARDS ===== */
.ua-mobile { display: flex; flex-direction: column; gap: 16px; }
@media (min-width: 768px) { .ua-mobile { display: none; } }

.ua-card {
    background: var(--ua-surface);
    border: 1px solid var(--ua-line);
    border-radius: var(--ua-radius-sm);
    padding: 24px;
    box-shadow: var(--ua-shadow);
    transition: var(--ua-transition);
    position: relative;
    overflow: hidden;
}
.ua-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #6366f1, #4338ca);
    opacity: 0;
    transition: opacity 0.3s;
}
.ua-card:hover {
    box-shadow: var(--ua-shadow-md);
    border-color: var(--ua-line-strong);
    transform: translateX(3px);
}
.ua-card:hover::before { opacity: 1; }
.ua-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}
.ua-card-name {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: var(--ua-ink);
}
.ua-card-email {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--ua-muted);
    margin-top: 2px;
}
.ua-card-meta {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}
.ua-card-meta-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--ua-muted);
}
.ua-card-meta-label {
    font-weight: 700;
    color: var(--ua-ink);
    min-width: 80px;
}
.ua-card-actions { display: flex; gap: 10px; }

/* ===== BADGES ===== */
.ua-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 100px;
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    white-space: nowrap;
}
.ua-badge-role {
    background: rgba(59,130,246,0.08);
    color: #2563eb;
    border: 1px solid rgba(59,130,246,0.1);
}
.dark .ua-badge-role { background: rgba(59,130,246,0.06); color: #60a5fa; }
.ua-badge-active {
    background: rgba(16,185,129,0.08);
    color: #059669;
    border: 1px solid rgba(16,185,129,0.1);
}
.dark .ua-badge-active { background: rgba(16,185,129,0.06); color: #34d399; }
.ua-badge-disabled {
    background: rgba(244,63,94,0.08);
    color: #e11d48;
    border: 1px solid rgba(244,63,94,0.1);
}
.dark .ua-badge-disabled { background: rgba(244,63,94,0.06); color: #fb7185; }
.ua-badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.ua-badge-active .ua-badge-dot { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,0.15); }
.ua-badge-disabled .ua-badge-dot { background: #f43f5e; box-shadow: 0 0 0 2px rgba(244,63,94,0.15); }

/* ===== TABLE ===== */
.ua-table-wrap {
    display: none;
    background: var(--ua-surface);
    border: 1px solid var(--ua-line);
    border-radius: var(--ua-radius);
    box-shadow: var(--ua-shadow);
    overflow: hidden;
    position: relative;
}
.ua-table-wrap::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, #4338ca);
    border-radius: 3px 3px 0 0;
}
@media (min-width: 768px) { .ua-table-wrap { display: block; } }

.ua-table {
    width: 100%;
    border-collapse: collapse;
    font-family: var(--font-body);
}
.ua-table thead th {
    padding: 18px 24px;
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--ua-muted);
    background: var(--ua-bg);
    border-bottom: 1px solid var(--ua-line);
    white-space: nowrap;
}
.ua-table tbody tr {
    border-bottom: 1px solid var(--ua-line);
    transition: background 0.2s;
}
.ua-table tbody tr:hover { background: var(--ua-bg); }
.ua-table tbody td {
    padding: 18px 24px;
    font-size: 0.875rem;
    color: var(--ua-ink);
    white-space: nowrap;
}
.ua-table tbody td:first-child { font-weight: 700; }
.ua-table-empty td {
    text-align: center;
    color: var(--ua-muted);
    padding: 48px 24px;
    font-weight: 600;
}

/* ===== PAGINATION ===== */
.ua-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 4px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--ua-muted);
}
.ua-page-btns { display: flex; gap: 8px; }
.ua-page-btn {
    display: inline-flex;
    align-items: center;
    padding: 9px 18px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    text-decoration: none;
    transition: var(--ua-transition);
}
.ua-page-btn:not(.disabled) {
    background: var(--ua-surface);
    color: var(--ua-ink);
    border: 1px solid var(--ua-line-strong);
    box-shadow: var(--ua-shadow);
}
.ua-page-btn:not(.disabled):hover {
    background: var(--ua-bg);
    border-color: var(--ua-gold);
    transform: translateY(-1px);
    box-shadow: var(--ua-shadow-md);
}
.ua-page-btn.disabled {
    background: var(--ua-bg);
    color: var(--ua-muted);
    border: 1px solid var(--ua-line);
    opacity: 0.5;
    cursor: not-allowed;
}

/* ===== DELETE MODAL ===== */
.ua-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    background: rgba(15,13,31,0.65);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 20px;
}
.ua-modal-overlay.show { display: flex; }
.ua-modal {
    width: 100%;
    max-width: 480px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 28px;
    padding: 36px;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.28), 0 8px 20px rgba(15, 23, 42, 0.12);
    color: #0f172a;
    position: relative;
    overflow: hidden;
    animation: uaModalIn 0.35s ease forwards;
}
html.dark-mode .ua-modal {
    background: #0f172a;
    border-color: #334155;
    box-shadow: 0 24px 60px rgba(2, 6, 23, 0.55), 0 8px 20px rgba(2, 6, 23, 0.35);
    color: #f8fafc;
}
.ua-modal::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f43f5e, #fb7185);
}
@keyframes uaModalIn {
    from { opacity: 0; transform: scale(0.96) translateY(10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.ua-modal-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: linear-gradient(135deg, #ffe4e6, #fff1f2);
    color: #e11d48;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    box-shadow: 0 8px 24px -6px rgba(225,29,72,0.15);
}
.dark .ua-modal-icon {
    background: rgba(244,63,94,0.08);
    color: #fb7185;
}
.ua-modal-icon svg { width: 28px; height: 28px; }
.ua-modal h2 {
    font-family: var(--font-display);
    font-size: 1.375rem;
    font-weight: 800;
    color: var(--ua-ink);
    letter-spacing: -0.02em;
}
.ua-modal p {
    font-family: var(--font-body);
    font-size: 0.875rem;
    color: var(--ua-muted);
    line-height: 1.65;
    margin-top: 10px;
}
.ua-modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 28px;
    justify-content: flex-end;
}
.ua-btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff;
    box-shadow: 0 4px 16px -4px rgba(220,38,38,0.3);
}
.ua-btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px -4px rgba(220,38,38,0.4);
    filter: brightness(1.08);
}

/* ===== ANIMATIONS ===== */
@keyframes uaFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.ua-animate {
    opacity: 0;
    animation: uaFadeUp 0.5s ease forwards;
}
.ua-animate:nth-child(1) { animation-delay: 0.05s; }
.ua-animate:nth-child(2) { animation-delay: 0.1s; }
.ua-animate:nth-child(3) { animation-delay: 0.15s; }
.ua-animate:nth-child(4) { animation-delay: 0.2s; }
.ua-animate:nth-child(5) { animation-delay: 0.25s; }
</style>

<div class="ua-wrap">
    <!-- Hero -->
    <div class="ua-hero ua-animate">
        <div class="ua-hero-grid"></div>
        <div class="ua-hero-inner">
            <div>
                <span class="ua-hero-label">User Management</span>
                <h1 class="ua-hero-title">User Access</h1>
                <p class="ua-hero-desc">Manage system users, assign roles, and keep your access controls up to date.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="ua-hero-btn">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add New User
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if ($message = Session::get('success'))
        <div class="ua-alert ua-alert-success ua-animate">
            <div class="ua-alert-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span>{{ $message }}</span>
        </div>
    @endif
    @if ($message = Session::get('warning'))
        <div class="ua-alert ua-alert-warning ua-animate">
            <div class="ua-alert-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <span>{{ $message }}</span>
        </div>
    @endif

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="ua-filter ua-animate">
        <div class="ua-filter-inner">
            <div class="ua-filter-field">
                <label for="role_id">Filter by role</label>
                <select id="role_id" name="role_id" class="ua-filter-select">
                    <option value="">All roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->role_id }}" @selected((string) request('role_id') === (string) $role->role_id)>{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ua-filter-actions">
                <button type="submit" class="ua-btn ua-btn-primary">Filter</button>
                <a href="{{ route('admin.users.index') }}" class="ua-btn ua-btn-ghost">Clear</a>
            </div>
        </div>
    </form>

    <!-- Mobile Cards -->
    <div class="ua-mobile">
        @forelse ($users as $user)
            <div class="ua-card ua-animate">
                <div class="ua-card-header">
                    <div>
                        <div class="ua-card-name">{{ $user->username }}</div>
                        <div class="ua-card-email">{{ $user->user_email }}</div>
                    </div>
                    <span class="ua-badge ua-badge-role">{{ $user->role?->role_name ?? 'N/A' }}</span>
                </div>
                <div class="ua-card-meta">
                    <div class="ua-card-meta-row">
                        <span class="ua-card-meta-label">Barangay</span>
                        <span>{{ $user->barangay?->barangay_name ?? 'N/A' }}</span>
                    </div>
                    <div class="ua-card-meta-row">
                        <span class="ua-card-meta-label">Status</span>
                        @if ($user->is_disabled)
                            <span class="ua-badge ua-badge-disabled"><span class="ua-badge-dot"></span>Disabled</span>
                        @else
                            <span class="ua-badge ua-badge-active"><span class="ua-badge-dot"></span>Active</span>
                        @endif
                    </div>
                </div>
                <div class="ua-card-actions">
                    <a href="{{ route('admin.users.edit', $user->user_id) }}" class="ua-btn ua-btn-ghost" style="font-size:0.75rem; padding:9px 16px;">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="inline delete-user-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="ua-btn ua-btn-danger delete-user-button" style="font-size:0.75rem; padding:9px 16px;" data-username="{{ $user->username }}" data-email="{{ $user->user_email }}">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="ua-card ua-animate" style="text-align:center; padding:48px 24px; color:var(--ua-muted); font-weight:600;">
                No users found
            </div>
        @endforelse
    </div>

    <!-- Desktop Table -->
    <div class="ua-table-wrap ua-animate">
        <table class="ua-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Barangay</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->user_email }}</td>
                        <td><span class="ua-badge ua-badge-role">{{ $user->role?->role_name ?? 'N/A' }}</span></td>
                        <td>{{ $user->barangay?->barangay_name ?? 'N/A' }}</td>
                        <td>
                            @if ($user->is_disabled)
                                <span class="ua-badge ua-badge-disabled"><span class="ua-badge-dot"></span>Disabled</span>
                            @else
                                <span class="ua-badge ua-badge-active"><span class="ua-badge-dot"></span>Active</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:16px; align-items:center;">
                                <a href="{{ route('admin.users.edit', $user->user_id) }}" style="font-weight:700; color:var(--ua-ink); text-decoration:none; font-size:0.8125rem; transition:color 0.2s;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='var(--ua-ink)'">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="inline delete-user-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-user-button" style="background:none; border:none; color:#e11d48; font-family:var(--font-body); font-size:0.8125rem; font-weight:700; cursor:pointer; padding:0; transition:color 0.2s;" data-username="{{ $user->username }}" data-email="{{ $user->user_email }}" onmouseover="this.style.color='#be123c'" onmouseout="this.style.color='#e11d48'">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="ua-table-empty">No users found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="ua-pagination ua-animate">
        <span>Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
        <div class="ua-page-btns">
            @if ($users->onFirstPage())
                <span class="ua-page-btn disabled">Previous</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="ua-page-btn">Previous</a>
            @endif
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="ua-page-btn">Next</a>
            @else
                <span class="ua-page-btn disabled">Next</span>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="adminDeleteConfirmModal" class="ua-modal-overlay">
    <div class="ua-modal">
        <div class="ua-modal-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9M9.27 4.998l-.348 1.647a2.25 2.25 0 01-2.196 1.804H4.5m3.75-3.75h10.5m-10.5 0l.9-2.7A2.25 2.25 0 0111.25 2h1.5a2.25 2.25 0 012.196 1.804l.9 2.696"/></svg>
        </div>
        <h2>Confirm User Deletion</h2>
        <p id="adminDeleteModalDescription">Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="ua-modal-actions">
            <button id="cancelAdminDeleteBtn" type="button" class="ua-btn ua-btn-ghost">Cancel</button>
            <button id="confirmAdminDeleteBtn" type="button" class="ua-btn ua-btn-danger">
                <span class="delete-button-label">Delete User</span>
                <span class="delete-loading-indicator" style="display:none; align-items:center; gap:8px;" role="status">
                    <svg class="animate-spin" style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle style="opacity:0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity:0.75;" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    Deleting…
                </span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('adminDeleteConfirmModal');
        var modalDescription = document.getElementById('adminDeleteModalDescription');
        var cancelBtn = document.getElementById('cancelAdminDeleteBtn');
        var confirmBtn = document.getElementById('confirmAdminDeleteBtn');
        var confirmLabel = confirmBtn.querySelector('.delete-button-label');
        var loadingIndicator = confirmBtn.querySelector('.delete-loading-indicator');
        var selectedForm = null;

        if (modal && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }

        document.querySelectorAll('.delete-user-button').forEach(function (button) {
            button.addEventListener('click', function () {
                selectedForm = button.closest('form');
                var username = button.dataset.username || 'this user';
                var email = button.dataset.email ? ' (' + button.dataset.email + ')' : '';
                modalDescription.textContent = 'Delete user "' + username + '"' + email + '? This action cannot be undone.';
                modal.classList.add('show');
            });
        });

        function closeModal() {
            modal.classList.remove('show');
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.style.opacity = '1';
                confirmBtn.style.cursor = 'pointer';
                confirmLabel.style.display = 'inline';
                loadingIndicator.style.display = 'none';
                confirmBtn.removeAttribute('aria-busy');
            }
            cancelBtn.disabled = false;
            cancelBtn.style.opacity = '1';
            cancelBtn.style.cursor = 'pointer';
            selectedForm = null;
        }

        cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('show')) {
                closeModal();
            }
        });

        confirmBtn.addEventListener('click', function () {
            if (selectedForm) {
                confirmBtn.disabled = true;
                confirmBtn.style.opacity = '0.6';
                confirmBtn.style.cursor = 'not-allowed';
                confirmBtn.setAttribute('aria-busy', 'true');
                confirmLabel.style.display = 'none';
                loadingIndicator.style.display = 'inline-flex';
                cancelBtn.disabled = true;
                cancelBtn.style.opacity = '0.6';
                cancelBtn.style.cursor = 'not-allowed';
                selectedForm.submit();
            }
        });
    });
</script>
@endsection