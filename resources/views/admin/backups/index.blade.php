@extends('layouts.admin')

@section('content')
<style>
/* ===== DATABASE BACKUPS - RICH REDESIGN ===== */
.bu-wrap {
    --bu-bg: #f4f4f5;
    --bu-surface: #ffffff;
    --bu-ink: #0f0d1f;
    --bu-muted: #6b7280;
    --bu-line: rgba(0,0,0,0.06);
    --bu-line-strong: rgba(0,0,0,0.1);
    --bu-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.04);
    --bu-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.08), 0 4px 8px -4px rgba(0,0,0,0.04);
    --bu-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.14), 0 12px 24px -12px rgba(0,0,0,0.08);
    --bu-gold: #f59e0b;
    --bu-indigo: #4338ca;
    --bu-radius-xl: 28px;
    --bu-radius: 20px;
    --bu-radius-sm: 16px;
    --bu-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
}
.dark .bu-wrap {
    --bu-bg: #0f0e1a;
    --bu-surface: #141321;
    --bu-ink: #f8f7f5;
    --bu-muted: #94a3b8;
    --bu-line: rgba(255,255,255,0.06);
    --bu-line-strong: rgba(255,255,255,0.1);
    --bu-shadow: 0 1px 3px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.1);
    --bu-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.2), 0 4px 8px -4px rgba(0,0,0,0.15);
    --bu-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.35), 0 12px 24px -12px rgba(0,0,0,0.25);
}

.bu-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 24px 20px 48px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}
@media (min-width: 640px) {
    .bu-wrap { padding: 32px 32px 56px; }
}

/* ===== HERO ===== */
.bu-hero {
    position: relative;
    border-radius: var(--bu-radius-xl);
    padding: 36px 28px;
    overflow: hidden;
    background: linear-gradient(135deg, #0c0a1f 0%, #1e1b4b 30%, #312e81 65%, #4338ca 100%);
    box-shadow: var(--bu-shadow-lg), inset 0 1px 0 rgba(255,255,255,0.08), 0 0 100px -20px rgba(99,102,241,0.15);
}
@media (min-width: 640px) {
    .bu-hero { padding: 44px 40px; }
}
.bu-hero::before {
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
.bu-hero::after {
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
.bu-hero-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
}
.bu-hero-inner {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
@media (min-width: 768px) {
    .bu-hero-inner {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
    }
}
.bu-hero-label {
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
.bu-hero-label::before {
    content: '';
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #fbbf24;
    box-shadow: 0 0 0 3px rgba(251,191,36,0.2);
}
.bu-hero-title {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.03em;
    line-height: 1.1;
    margin-top: 12px;
}
@media (min-width: 640px) {
    .bu-hero-title { font-size: 2.75rem; }
}
.bu-hero-desc {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    color: rgba(255,255,255,0.55);
    line-height: 1.65;
    margin-top: 10px;
    max-width: 560px;
}
.bu-hero-btn {
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
    transition: var(--bu-transition);
    flex-shrink: 0;
    border: none;
    cursor: pointer;
}
.bu-hero-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px -6px rgba(245,158,11,0.45);
    filter: brightness(1.08);
}
.bu-hero-btn svg { width: 18px; height: 18px; }

/* ===== ALERT ===== */
.bu-alert {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 18px 24px;
    border-radius: var(--bu-radius-sm);
    background: rgba(16,185,129,0.04);
    border: 1px solid rgba(16,185,129,0.08);
    animation: buFadeUp 0.4s ease forwards;
}
.dark .bu-alert {
    background: rgba(16,185,129,0.03);
    border-color: rgba(16,185,129,0.06);
}
.bu-alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(16,185,129,0.08);
    color: #059669;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.dark .bu-alert-icon { background: rgba(16,185,129,0.06); color: #34d399; }
.bu-alert-icon svg { width: 20px; height: 20px; }
.bu-alert-text {
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    color: #059669;
    line-height: 1.5;
    padding-top: 10px;
}
.dark .bu-alert-text { color: #34d399; }

/* ===== FILTER CARD ===== */
.bu-filter {
    background: var(--bu-surface);
    border: 1px solid var(--bu-line);
    border-radius: var(--bu-radius);
    padding: 28px;
    box-shadow: var(--bu-shadow);
    position: relative;
    overflow: hidden;
    animation: buFadeUp 0.45s ease 0.05s forwards;
    opacity: 0;
}
.bu-filter::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, #f59e0b);
    border-radius: 3px 3px 0 0;
}
.bu-filter-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    position: relative;
    z-index: 1;
}
@media (min-width: 768px) {
    .bu-filter-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (min-width: 1280px) {
    .bu-filter-grid { grid-template-columns: repeat(5, 1fr); align-items: end; }
}
.bu-field label {
    display: block;
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--bu-muted);
    margin-bottom: 10px;
}
.bu-input {
    width: 100%;
    padding: 12px 16px;
    border-radius: 14px;
    border: 1px solid var(--bu-line-strong);
    background: var(--bu-bg);
    color: var(--bu-ink);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    transition: var(--bu-transition);
    box-sizing: border-box;
}
.bu-input:focus {
    outline: none;
    border-color: var(--bu-gold);
    box-shadow: 0 0 0 4px rgba(245,158,11,0.08);
    background: var(--bu-surface);
}
select.bu-input {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19 9-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px;
    padding-right: 44px;
}
.bu-filter-actions {
    display: flex;
    gap: 10px;
}
@media (min-width: 1280px) {
    .bu-filter-actions { justify-content: flex-end; }
}
.bu-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--bu-transition);
    border: none;
    text-decoration: none;
}
.bu-btn-primary {
    background: linear-gradient(135deg, #1e1b4b, #4338ca);
    color: #ffffff;
    box-shadow: 0 4px 16px -4px rgba(67,56,202,0.3);
}
.bu-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px -4px rgba(67,56,202,0.4);
    filter: brightness(1.1);
}
.bu-btn-ghost {
    background: var(--bu-bg);
    color: var(--bu-muted);
    border: 1px solid var(--bu-line-strong);
}
.bu-btn-ghost:hover {
    background: var(--bu-line);
    color: var(--bu-ink);
    transform: translateY(-1px);
}

/* ===== MANAGEMENT CARD ===== */
.bu-mgmt {
    background: var(--bu-surface);
    border: 1px solid var(--bu-line);
    border-radius: var(--bu-radius);
    padding: 28px;
    box-shadow: var(--bu-shadow);
    display: flex;
    flex-direction: column;
    gap: 16px;
    animation: buFadeUp 0.45s ease 0.1s forwards;
    opacity: 0;
}
@media (min-width: 1024px) {
    .bu-mgmt {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}
.bu-mgmt-title {
    font-family: var(--font-display);
    font-size: 1.125rem;
    font-weight: 800;
    color: var(--bu-ink);
    letter-spacing: -0.01em;
}
.bu-mgmt-desc {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--bu-muted);
    margin-top: 4px;
    line-height: 1.5;
}

/* ===== MOBILE CARDS ===== */
.bu-mobile { display: flex; flex-direction: column; gap: 16px; }
@media (min-width: 768px) { .bu-mobile { display: none; } }

.bu-card {
    background: var(--bu-surface);
    border: 1px solid var(--bu-line);
    border-radius: var(--bu-radius-sm);
    padding: 24px;
    box-shadow: var(--bu-shadow);
    transition: var(--bu-transition);
    position: relative;
    overflow: hidden;
}
.bu-card::before {
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
.bu-card:hover {
    box-shadow: var(--bu-shadow-md);
    border-color: var(--bu-line-strong);
    transform: translateX(3px);
}
.bu-card:hover::before { opacity: 1; }
.bu-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}
.bu-card-user {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--bu-ink);
}
.bu-card-type {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--bu-muted);
    margin-top: 4px;
}
.bu-card-meta {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}
.bu-card-meta-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--bu-muted);
}
.bu-card-meta-label {
    font-weight: 700;
    color: var(--bu-ink);
    min-width: 60px;
}
.bu-card-actions { display: flex; gap: 10px; }

/* ===== BADGES ===== */
.bu-badge {
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
.bu-badge-completed {
    background: rgba(16,185,129,0.06);
    color: #059669;
    border: 1px solid rgba(16,185,129,0.08);
}
.dark .bu-badge-completed { background: rgba(16,185,129,0.05); color: #34d399; }
.bu-badge-pending {
    background: rgba(245,158,11,0.06);
    color: #d97706;
    border: 1px solid rgba(245,158,11,0.08);
}
.dark .bu-badge-pending { background: rgba(245,158,11,0.05); color: #fbbf24; }
.bu-badge-failed {
    background: rgba(239,68,68,0.06);
    color: #dc2626;
    border: 1px solid rgba(239,68,68,0.08);
}
.dark .bu-badge-failed { background: rgba(239,68,68,0.05); color: #f87171; }
.bu-badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.bu-badge-completed .bu-badge-dot { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,0.12); }
.bu-badge-pending .bu-badge-dot { background: #f59e0b; box-shadow: 0 0 0 2px rgba(245,158,11,0.12); }
.bu-badge-failed .bu-badge-dot { background: #ef4444; box-shadow: 0 0 0 2px rgba(239,68,68,0.12); }

/* ===== TABLE ===== */
.bu-table-wrap {
    display: none;
    background: var(--bu-surface);
    border: 1px solid var(--bu-line);
    border-radius: var(--bu-radius);
    box-shadow: var(--bu-shadow);
    overflow: hidden;
    position: relative;
    animation: buFadeUp 0.45s ease 0.15s forwards;
    opacity: 0;
}
.bu-table-wrap::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, #f59e0b);
    border-radius: 3px 3px 0 0;
}
@media (min-width: 768px) { .bu-table-wrap { display: block; } }

.bu-table {
    width: 100%;
    border-collapse: collapse;
    font-family: var(--font-body);
}
.bu-table thead th {
    padding: 18px 24px;
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--bu-muted);
    background: var(--bu-bg);
    border-bottom: 1px solid var(--bu-line);
    white-space: nowrap;
}
.bu-table tbody tr {
    border-bottom: 1px solid var(--bu-line);
    transition: background 0.2s;
}
.bu-table tbody tr:hover { background: var(--bu-bg); }
.bu-table tbody td {
    padding: 18px 24px;
    font-size: 0.875rem;
    color: var(--bu-ink);
    white-space: nowrap;
}
.bu-table-empty td {
    text-align: center;
    color: var(--bu-muted);
    padding: 56px 24px;
    font-weight: 600;
}
.bu-error-msg {
    font-family: var(--font-body);
    font-size: 0.75rem;
    color: #ef4444;
    margin-top: 8px;
    font-weight: 600;
}
.dark .bu-error-msg { color: #f87171; }

/* ===== PAGINATION ===== */
.bu-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 4px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--bu-muted);
    animation: buFadeUp 0.45s ease 0.2s forwards;
    opacity: 0;
}
.bu-page-btns { display: flex; gap: 8px; }
.bu-page-btn {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    text-decoration: none;
    transition: var(--bu-transition);
}
.bu-page-btn:not(.disabled) {
    background: var(--bu-surface);
    color: var(--bu-ink);
    border: 1px solid var(--bu-line-strong);
    box-shadow: var(--bu-shadow);
}
.bu-page-btn:not(.disabled):hover {
    background: var(--bu-bg);
    border-color: var(--bu-gold);
    transform: translateY(-1px);
    box-shadow: var(--bu-shadow-md);
}
.bu-page-btn.disabled {
    background: var(--bu-bg);
    color: var(--bu-muted);
    border: 1px solid var(--bu-line);
    opacity: 0.5;
    cursor: not-allowed;
}

/* ===== ANIMATIONS ===== */
@keyframes buFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

<div class="bu-wrap">
    <!-- Hero -->
    <div class="bu-hero">
        <div class="bu-hero-grid"></div>
        <div class="bu-hero-inner">
            <div>
                <span class="bu-hero-label">Data Protection</span>
                <h1 class="bu-hero-title">Database Backups</h1>
                <p class="bu-hero-desc">Automated backups are created after significant data changes (throttled to once every 5 minutes) and can also be generated manually.</p>
            </div>
            <form action="{{ route('admin.backups.manual') }}" method="POST" class="inline-flex">
                @csrf
                <button type="submit" class="bu-hero-btn">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Create Manual Backup
                </button>
            </form>
        </div>
    </div>

    <!-- Alert -->
    @if(session('status'))
        <div class="bu-alert">
            <div class="bu-alert-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="bu-alert-text">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.backups.index') }}" class="bu-filter">
        <div class="bu-filter-grid">
            <div class="bu-field">
                <label for="status">Status</label>
                <select id="status" name="status" class="bu-input">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bu-field">
                <label for="trigger_type">Trigger type</label>
                <select id="trigger_type" name="trigger_type" class="bu-input">
                    <option value="">All trigger types</option>
                    @foreach ($triggerTypes as $triggerType)
                        <option value="{{ $triggerType }}" @selected(request('trigger_type') === $triggerType)>{{ ucfirst(str_replace('_', ' ', $triggerType)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bu-field">
                <label for="created_date">Created date</label>
                <input id="created_date" name="created_date" type="date" value="{{ request('created_date') }}" class="bu-input">
            </div>
            <div class="bu-filter-actions">
                <button type="submit" class="bu-btn bu-btn-primary">Filter</button>
                <a href="{{ route('admin.backups.index') }}" class="bu-btn bu-btn-ghost">Clear</a>
            </div>
        </div>
    </form>

    <!-- Mobile Cards -->
    <div class="bu-mobile">
        @forelse($backups as $backup)
            <div class="bu-card">
                <div class="bu-card-header">
                    <div>
                        <div class="bu-card-user">{{ $backup->triggeredBy?->username ?? 'System' }}</div>
                        <div class="bu-card-type">{{ match ($backup->trigger_type) {
                            'manual' => 'Manual',
                            'project_create' => 'Project Create',
                            'project_update' => 'Project Update',
                            'project_delete' => 'Project Delete',
                            'user_create' => 'User Create',
                            'user_update' => 'User Update',
                            'user_delete' => 'User Delete',
                            default => ucfirst(str_replace('_', ' ', $backup->trigger_type)),
                        } }}</div>
                    </div>
                    @php
                        $badgeClass = match ($backup->status) {
                            'completed' => 'bu-badge-completed',
                            'pending' => 'bu-badge-pending',
                            'failed' => 'bu-badge-failed',
                            default => 'bu-badge-pending',
                        };
                    @endphp
                    <span class="bu-badge {{ $badgeClass }}"><span class="bu-badge-dot"></span>{{ ucfirst($backup->status) }}</span>
                </div>
                <div class="bu-card-meta">
                    <div class="bu-card-meta-row">
                        <span class="bu-card-meta-label">Date</span>
                        <span>{{ $backup->created_at->timezone(config('app.timezone'))->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="bu-card-meta-row">
                        <span class="bu-card-meta-label">Size</span>
                        <span>{{ $backup->file_size ? number_format($backup->file_size / 1024, 2) . ' KB' : '-' }}</span>
                    </div>
                </div>
                <div class="bu-card-actions">
                    @if($backup->status === 'completed' && $backup->file_path)
                        <a href="{{ route('admin.backups.download', $backup) }}" class="bu-btn bu-btn-primary" style="padding:9px 18px; font-size:0.75rem;">Download</a>
                    @endif
                    <form action="{{ route('admin.backups.destroy', $backup) }}" method="POST" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bu-btn" style="padding:9px 18px; font-size:0.75rem; background:rgba(239,68,68,0.06); color:#dc2626; border:1px solid rgba(239,68,68,0.1);">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bu-card" style="text-align:center; padding:48px 24px; color:var(--bu-muted); font-weight:600;">
                No backups have been created yet.
            </div>
        @endforelse
    </div>

    <!-- Desktop Table -->
    <div class="bu-table-wrap">
        <table class="bu-table">
            <thead>
                <tr>
                    <th>Triggered By</th>
                    <th>Trigger Type</th>
                    <th>Date</th>
                    <th>File Size</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                    <tr>
                        <td>{{ $backup->triggeredBy?->username ?? 'System' }}</td>
                        <td style="text-transform:uppercase; letter-spacing:0.06em; font-size:0.75rem; font-weight:700; color:var(--bu-muted);">
                            {{ match ($backup->trigger_type) {
                                'manual' => 'Manual',
                                'project_create' => 'Project Create',
                                'project_update' => 'Project Update',
                                'project_delete' => 'Project Delete',
                                'user_create' => 'User Create',
                                'user_update' => 'User Update',
                                'user_delete' => 'User Delete',
                                default => ucfirst(str_replace('_', ' ', $backup->trigger_type)),
                            } }}
                        </td>
                        <td>{{ $backup->created_at->timezone(config('app.timezone'))->format('M d, Y H:i') }}</td>
                        <td>{{ $backup->file_size ? number_format($backup->file_size / 1024, 2) . ' KB' : '-' }}</td>
                        <td>
                            @php
                                $badgeClass = match ($backup->status) {
                                    'completed' => 'bu-badge-completed',
                                    'pending' => 'bu-badge-pending',
                                    'failed' => 'bu-badge-failed',
                                    default => 'bu-badge-pending',
                                };
                            @endphp
                            <span class="bu-badge {{ $badgeClass }}"><span class="bu-badge-dot"></span>{{ ucfirst($backup->status) }}</span>
                            @if($backup->status === 'failed' && $backup->error_message)
                                <div class="bu-error-msg">{{ $backup->error_message }}</div>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; gap:10px; align-items:center;">
                                @if($backup->status === 'completed' && $backup->file_path)
                                    <a href="{{ route('admin.backups.download', $backup) }}" class="bu-btn bu-btn-primary" style="padding:8px 18px; font-size:0.75rem;">Download</a>
                                @endif
                                <form action="{{ route('admin.backups.destroy', $backup) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bu-btn" style="padding:8px 18px; font-size:0.75rem; background:rgba(239,68,68,0.06); color:#dc2626; border:1px solid rgba(239,68,68,0.1);">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="bu-table-empty">No backups have been created yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bu-pagination">
        <span>Page {{ $backups->currentPage() }} of {{ $backups->lastPage() }}</span>
        <div class="bu-page-btns">
            @if ($backups->onFirstPage())
                <span class="bu-page-btn disabled">Previous</span>
            @else
                <a href="{{ $backups->previousPageUrl() }}" class="bu-page-btn">Previous</a>
            @endif
            @if ($backups->hasMorePages())
                <a href="{{ $backups->nextPageUrl() }}" class="bu-page-btn">Next</a>
            @else
                <span class="bu-page-btn disabled">Next</span>
            @endif
        </div>
    </div>
</div>
@endsection