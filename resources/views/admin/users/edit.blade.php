@extends('layouts.admin')

@section('content')
<style>
/* ===== EDIT USER v3 - SPLIT LAYOUT / SETTINGS PANEL ===== */
.eu3-wrap {
    --e3-bg: #f4f4f5;
    --e3-surface: #ffffff;
    --e3-ink: #0f0d1f;
    --e3-muted: #6b7280;
    --e3-line: rgba(0,0,0,0.06);
    --e3-line-strong: rgba(0,0,0,0.1);
    --e3-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.04);
    --e3-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.08), 0 4px 8px -4px rgba(0,0,0,0.04);
    --e3-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.14), 0 12px 24px -12px rgba(0,0,0,0.08);
    --e3-gold: #f59e0b;
    --e3-indigo: #4338ca;
    --e3-radius-xl: 24px;
    --e3-radius: 18px;
    --e3-radius-sm: 14px;
    --e3-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
}
.dark .eu3-wrap {
    --e3-bg: #0f0e1a;
    --e3-surface: #141321;
    --e3-ink: #f8f7f5;
    --e3-muted: #94a3b8;
    --e3-line: rgba(255,255,255,0.06);
    --e3-line-strong: rgba(255,255,255,0.1);
    --e3-shadow: 0 1px 3px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.1);
    --e3-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.2), 0 4px 8px -4px rgba(0,0,0,0.15);
    --e3-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.35), 0 12px 24px -12px rgba(0,0,0,0.25);
}

.eu3-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 24px 20px 48px;
}
@media (min-width: 640px) {
    .eu3-wrap { padding: 32px 32px 56px; }
}

/* ===== BREADCRUMB BAR ===== */
.eu3-breadcrumb {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
    animation: e3FadeUp 0.4s ease forwards;
}
.eu3-breadcrumb a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 12px;
    background: var(--e3-surface);
    border: 1px solid var(--e3-line);
    color: var(--e3-muted);
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    text-decoration: none;
    transition: var(--e3-transition);
    box-shadow: var(--e3-shadow);
}
.eu3-breadcrumb a:hover {
    border-color: var(--e3-gold);
    color: var(--e3-gold);
    transform: translateX(-2px);
    box-shadow: var(--e3-shadow-md);
}
.eu3-breadcrumb a svg { width: 16px; height: 16px; }
.eu3-breadcrumb-divider {
    color: var(--e3-muted);
    opacity: 0.4;
    font-size: 0.75rem;
}
.eu3-breadcrumb-current {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--e3-ink);
}

/* ===== SPLIT LAYOUT ===== */
.eu3-split {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    animation: e3FadeUp 0.5s ease 0.1s forwards;
    opacity: 0;
}
@media (min-width: 1024px) {
    .eu3-split {
        grid-template-columns: 320px 1fr;
        gap: 28px;
        align-items: start;
    }
}

/* ===== LEFT SIDEBAR ===== */
.eu3-sidebar {
    position: sticky;
    top: 100px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
/* User identity card */
.eu3-identity {
    background: var(--e3-surface);
    border: 1px solid var(--e3-line);
    border-radius: var(--e3-radius-xl);
    padding: 32px 24px;
    box-shadow: var(--e3-shadow);
    text-align: center;
    position: relative;
    overflow: hidden;
}
.eu3-identity::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 50%, #6366f1 100%);
    border-radius: var(--e3-radius-xl) var(--e3-radius-xl) 50% 50% / var(--e3-radius-xl) var(--e3-radius-xl) 100% 100%;
}
.eu3-identity-inner {
    position: relative;
    z-index: 1;
}
.eu3-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e1b4b, #4338ca);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 800;
    margin: 0 auto;
    box-shadow: 0 0 0 5px var(--e3-surface), 0 8px 24px -6px rgba(67,56,202,0.4);
    position: relative;
}
.eu3-avatar-status {
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #10b981;
    border: 4px solid var(--e3-surface);
    box-shadow: 0 0 0 2px rgba(16,185,129,0.2);
}
.eu3-avatar-status.disabled {
    background: #ef4444;
    box-shadow: 0 0 0 2px rgba(239,68,68,0.2);
}
.eu3-identity-name {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--e3-ink);
    margin-top: 16px;
    letter-spacing: -0.02em;
}
.eu3-identity-email {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--e3-muted);
    margin-top: 4px;
}
.eu3-identity-badges {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin-top: 16px;
}
.eu3-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 100px;
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.eu3-badge-role {
    background: rgba(59,130,246,0.06);
    color: #2563eb;
    border: 1px solid rgba(59,130,246,0.08);
}
.dark .eu3-badge-role { background: rgba(59,130,246,0.05); color: #60a5fa; }
.eu3-badge-active {
    background: rgba(16,185,129,0.06);
    color: #059669;
    border: 1px solid rgba(16,185,129,0.08);
}
.dark .eu3-badge-active { background: rgba(16,185,129,0.05); color: #34d399; }
.eu3-badge-disabled {
    background: rgba(239,68,68,0.06);
    color: #dc2626;
    border: 1px solid rgba(239,68,68,0.08);
}
.dark .eu3-badge-disabled { background: rgba(239,68,68,0.05); color: #f87171; }
.eu3-badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.eu3-badge-active .eu3-badge-dot { background: #10b981; }
.eu3-badge-disabled .eu3-badge-dot { background: #ef4444; }

/* Quick info list */
.eu3-info-list {
    background: var(--e3-surface);
    border: 1px solid var(--e3-line);
    border-radius: var(--e3-radius);
    padding: 8px;
    box-shadow: var(--e3-shadow);
}
.eu3-info-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 12px;
    transition: var(--e3-transition);
}
.eu3-info-item:hover {
    background: var(--e3-bg);
}
.eu3-info-item:not(:last-child) {
    border-bottom: 1px solid var(--e3-line);
}
.eu3-info-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #dbeafe, #eff6ff);
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.dark .eu3-info-icon {
    background: rgba(59,130,246,0.08);
    color: #60a5fa;
}
.eu3-info-icon svg { width: 18px; height: 18px; }
.eu3-info-label {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--e3-muted);
}
.eu3-info-value {
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--e3-ink);
    margin-top: 2px;
}

/* ===== RIGHT FORM AREA ===== */
.eu3-form-area {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Section card */
.eu3-section {
    background: var(--e3-surface);
    border: 1px solid var(--e3-line);
    border-radius: var(--e3-radius);
    padding: 32px;
    box-shadow: var(--e3-shadow);
    position: relative;
    overflow: hidden;
    animation: e3FadeUp 0.5s ease forwards;
    opacity: 0;
}
.eu3-section:nth-child(1) { animation-delay: 0.15s; }
.eu3-section:nth-child(2) { animation-delay: 0.2s; }
.eu3-section:nth-child(3) { animation-delay: 0.25s; }
.eu3-section:nth-child(4) { animation-delay: 0.3s; }
/* Left accent line */
.eu3-section::before {
    content: '';
    position: absolute;
    top: 28px;
    left: 0;
    width: 4px;
    height: 32px;
    border-radius: 0 4px 4px 0;
    background: linear-gradient(180deg, #6366f1, #f59e0b);
}
.eu3-section-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
    margin-left: 8px;
}
.eu3-section-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #1e1b4b, #4338ca);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 16px -4px rgba(67,56,202,0.3);
}
.eu3-section-icon svg { width: 20px; height: 20px; }
.eu3-section-title {
    font-family: var(--font-display);
    font-size: 1.0625rem;
    font-weight: 800;
    color: var(--e3-ink);
    letter-spacing: -0.01em;
}
.eu3-section-desc {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--e3-muted);
    margin-top: 2px;
}

/* Form grid */
.eu3-form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-left: 8px;
}
@media (min-width: 640px) {
    .eu3-form-grid { grid-template-columns: repeat(2, 1fr); }
}
.eu3-form-grid .eu3-field-full { grid-column: 1 / -1; }

/* Field */
.eu3-field label {
    display: block;
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--e3-muted);
    margin-bottom: 10px;
}
.eu3-field label .eu3-required { color: #ef4444; margin-left: 2px; }
.eu3-input {
    width: 100%;
    padding: 13px 18px;
    border-radius: 14px;
    border: 1px solid var(--e3-line-strong);
    background: var(--e3-bg);
    color: var(--e3-ink);
    font-family: var(--font-body);
    font-size: 0.9375rem;
    font-weight: 600;
    transition: var(--e3-transition);
    box-sizing: border-box;
}
.eu3-input::placeholder { color: var(--e3-muted); opacity: 0.5; }
.eu3-input:focus {
    outline: none;
    border-color: var(--e3-gold);
    box-shadow: 0 0 0 4px rgba(245,158,11,0.08);
    background: var(--e3-surface);
}
.eu3-input.has-error {
    border-color: #ef4444;
    box-shadow: 0 0 0 4px rgba(239,68,68,0.06);
}
select.eu3-input {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19 9-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 18px;
    padding-right: 48px;
}
.eu3-error-msg {
    font-family: var(--font-body);
    font-size: 0.75rem;
    color: #ef4444;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
}
.dark .eu3-error-msg { color: #f87171; }

/* Disable checkbox */
.eu3-disable-box {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 20px 24px;
    border-radius: var(--e3-radius-sm);
    background: #fff1f2;
    border: 1px solid #fecdd3;
    cursor: pointer;
    transition: var(--e3-transition);
}
.eu3-disable-box:hover {
    background: #ffe4e6;
    border-color: #fda4af;
}
html.dark-mode .eu3-disable-box {
    background: rgba(127, 29, 29, 0.22);
    border-color: rgba(248, 113, 113, 0.35);
}
html.dark-mode .eu3-disable-box:hover {
    background: rgba(127, 29, 29, 0.35);
    border-color: rgba(248, 113, 113, 0.55);
}
.eu3-disable-box input[type="checkbox"] {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 2px solid var(--e3-line-strong);
    accent-color: var(--e3-gold);
    cursor: pointer;
    flex-shrink: 0;
    margin-top: 2px;
}
.eu3-disable-text {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--e3-ink);
    line-height: 1.5;
}
.eu3-disable-hint {
    font-family: var(--font-body);
    font-size: 0.75rem;
    color: var(--e3-muted);
    margin-top: 6px;
    line-height: 1.5;
}

/* Permissions */
.eu3-permissions-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
    margin-left: 8px;
}
@media (min-width: 640px) {
    .eu3-permissions-grid { grid-template-columns: repeat(2, 1fr); }
}
.eu3-permissions-grid .eu3-field-full { grid-column: 1 / -1; }
.eu3-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 12px;
    background: var(--e3-bg);
    border: 1px solid var(--e3-line);
    cursor: pointer;
    transition: var(--e3-transition);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--e3-ink);
    line-height: 1.5;
}
.eu3-checkbox:hover {
    border-color: rgba(99,102,241,0.2);
    box-shadow: var(--e3-shadow);
    transform: translateX(2px);
}
.eu3-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    border-radius: 6px;
    border: 2px solid var(--e3-line-strong);
    accent-color: var(--e3-indigo);
    cursor: pointer;
    flex-shrink: 0;
    margin-top: 1px;
}

/* Error alert */
.eu3-error {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 20px 24px;
    border-radius: var(--e3-radius-sm);
    background: rgba(239,68,68,0.04);
    border: 1px solid rgba(239,68,68,0.1);
    margin-left: 8px;
    margin-bottom: 8px;
}
.dark .eu3-error {
    background: rgba(239,68,68,0.03);
    border-color: rgba(239,68,68,0.08);
}
.eu3-error-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(239,68,68,0.08);
    color: #ef4444;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.dark .eu3-error-icon { background: rgba(239,68,68,0.06); }
.eu3-error-icon svg { width: 20px; height: 20px; }
.eu3-error-title {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 700;
    color: #dc2626;
    margin-bottom: 8px;
}
.dark .eu3-error-title { color: #f87171; }
.eu3-error-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.eu3-error-list li {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: #ef4444;
    padding: 4px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.dark .eu3-error-list li { color: #f87171; }
.eu3-error-list li::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #ef4444;
    flex-shrink: 0;
}

/* Actions */
.eu3-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    padding-top: 8px;
}
.eu3-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 32px;
    border-radius: 14px;
    font-family: var(--font-body);
    font-size: 0.9375rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--e3-transition);
    border: none;
    text-decoration: none;
}
.eu3-btn-primary {
    background: linear-gradient(135deg, #1e1b4b, #4338ca);
    color: #ffffff;
    box-shadow: 0 8px 24px -6px rgba(67,56,202,0.3);
}
.eu3-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px -6px rgba(67,56,202,0.4);
    filter: brightness(1.1);
}
.eu3-btn-ghost {
    background: var(--e3-bg);
    color: var(--e3-muted);
    border: 1px solid var(--e3-line-strong);
}
.eu3-btn-ghost:hover {
    background: var(--e3-line);
    color: var(--e3-ink);
    transform: translateY(-2px);
}
.eu3-spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: e3Spin 0.8s linear infinite;
    display: none;
}
@keyframes e3Spin {
    to { transform: rotate(360deg); }
}

/* ===== ANIMATIONS ===== */
@keyframes e3FadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

<div class="eu3-wrap">
    <!-- Breadcrumb -->
    <nav class="eu3-breadcrumb">
        <a href="{{ route('admin.users.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Users
        </a>
        <span class="eu3-breadcrumb-divider">/</span>
        <span class="eu3-breadcrumb-current">Edit User</span>
    </nav>

    <!-- Split Layout -->
    <div class="eu3-split">
        <!-- Left Sidebar -->
        <aside class="eu3-sidebar">
            <div class="eu3-identity">
                <div class="eu3-identity-inner">
                    <div class="eu3-avatar">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                        <span class="eu3-avatar-status {{ $user->is_disabled ? 'disabled' : '' }}"></span>
                    </div>
                    <div class="eu3-identity-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="eu3-identity-email">{{ $user->user_email }}</div>
                    <div class="eu3-identity-badges">
                        <span class="eu3-badge eu3-badge-role">{{ $user->role?->role_name ?? 'No Role' }}</span>
                        @if ($user->is_disabled)
                            <span class="eu3-badge eu3-badge-disabled"><span class="eu3-badge-dot"></span>Disabled</span>
                        @else
                            <span class="eu3-badge eu3-badge-active"><span class="eu3-badge-dot"></span>Active</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="eu3-info-list">
                <div class="eu3-info-item">
                    <div class="eu3-info-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <div class="eu3-info-label">Username</div>
                        <div class="eu3-info-value">{{ $user->username }}</div>
                    </div>
                </div>
                <div class="eu3-info-item">
                    <div class="eu3-info-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <div>
                        <div class="eu3-info-label">Barangay</div>
                        <div class="eu3-info-value">{{ $user->barangay?->barangay_name ?? 'Not assigned' }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Right Form -->
        <div class="eu3-form-area">
            @if ($errors->any())
                <div class="eu3-section" style="padding:24px 32px;">
                    <div class="eu3-error">
                        <div class="eu3-error-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75A9 9 0 11 3 12a9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        </div>
                        <div>
                            <div class="eu3-error-title">Please fix the following errors:</div>
                            <ul class="eu3-error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')

                <!-- Personal Info Section -->
                <div class="eu3-section">
                    <div class="eu3-section-header">
                        <div class="eu3-section-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                        <div>
                            <div class="eu3-section-title">Personal Information</div>
                            <div class="eu3-section-desc">Basic details about the user.</div>
                        </div>
                    </div>
                    <div class="eu3-form-grid">
                        <div class="eu3-field">
                            <label for="first_name">First Name <span class="eu3-required">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="eu3-input @error('first_name') has-error @enderror" placeholder="Enter first name" required>
                            @error('first_name')
                                <span class="eu3-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="eu3-field">
                            <label for="last_name">Last Name <span class="eu3-required">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="eu3-input @error('last_name') has-error @enderror" placeholder="Enter last name" required>
                            @error('last_name')
                                <span class="eu3-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="eu3-field">
                            <label for="username">Username <span class="eu3-required">*</span></label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" class="eu3-input @error('username') has-error @enderror" placeholder="Enter username" required>
                            @error('username')
                                <span class="eu3-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="eu3-field">
                            <label for="user_email">Email Address <span class="eu3-required">*</span></label>
                            <input type="email" id="user_email" name="user_email" value="{{ old('user_email', $user->user_email) }}" class="eu3-input @error('user_email') has-error @enderror" placeholder="user@example.com" required>
                            @error('user_email')
                                <span class="eu3-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div class="eu3-section">
                    <div class="eu3-section-header">
                        <div class="eu3-section-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.212 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.184.582-.496.644-.87l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="eu3-section-title">Account Settings</div>
                            <div class="eu3-section-desc">Control access and role assignments.</div>
                        </div>
                    </div>
                    <div class="eu3-form-grid">
                        <div class="eu3-field eu3-field-full">
                            <label class="eu3-disable-box">
                                <input type="checkbox" name="is_disabled" value="1" {{ old('is_disabled', $user->is_disabled ?? false) ? 'checked' : '' }}>
                                <div>
                                    <div class="eu3-disable-text">Disable login access for this user</div>
                                    <div class="eu3-disable-hint">Enable this if the user has resigned, left the organization, or should no longer access the system.</div>
                                </div>
                            </label>
                        </div>
                        <div class="eu3-field">
                            <label for="role_id">Role <span class="eu3-required">*</span></label>
                            <select id="role_id" name="role_id" class="eu3-input @error('role_id') has-error @enderror" required>
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->role_id }}" {{ old('role_id', $user->role_id) == $role->role_id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="eu3-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="eu3-field">
                            <label for="barangay_id">Barangay (Optional)</label>
                            <select id="barangay_id" name="barangay_id" class="eu3-input">
                                <option value="">-- Select Barangay --</option>
                                @foreach ($barangays as $barangay)
                                    <option value="{{ $barangay->barangay_id }}" {{ old('barangay_id', $user->barangay_id) == $barangay->barangay_id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                                @endforeach
                            </select>
                            @error('barangay_id')
                                <span class="eu3-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                @php
                    $userPermissions = old('permissions', $user->permissions ?? []);
                @endphp

                <!-- Department Permissions -->
                <div id="departmentPermissionsSection" class="eu3-section" style="display: none;">
                    <div class="eu3-section-header">
                        <div class="eu3-section-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75H9zm3 0h.008v.008H12V12zm.375 0a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM15.75 12a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M3.75 3h16.5M3.75 12h16.5"/></svg>
                        </div>
                        <div>
                            <div class="eu3-section-title">Planning Permissions</div>
                            <div class="eu3-section-desc">Choose which actions this Planning user is allowed to perform.</div>
                        </div>
                    </div>
                    <div class="eu3-permissions-grid">
                        <label class="eu3-checkbox">
                            <input type="checkbox" name="permissions[can_create_project]" value="1" {{ ($userPermissions['can_create_project'] ?? true) ? 'checked' : '' }}>
                            Create Project
                        </label>
                        <label class="eu3-checkbox">
                            <input type="checkbox" name="permissions[can_edit_project]" value="1" {{ ($userPermissions['can_edit_project'] ?? true) ? 'checked' : '' }}>
                            Edit Project
                        </label>
                        <label class="eu3-checkbox">
                            <input type="checkbox" name="permissions[can_delete_project]" value="1" {{ ($userPermissions['can_delete_project'] ?? true) ? 'checked' : '' }}>
                            Delete Project
                        </label>
                        <label class="eu3-checkbox">
                            <input type="checkbox" name="permissions[can_generate_reports]" value="1" {{ ($userPermissions['can_generate_reports'] ?? true) ? 'checked' : '' }}>
                            Generate/Export Reports
                        </label>
                        <label class="eu3-checkbox eu3-field-full">
                            <input type="checkbox" name="is_department_head" value="1" {{ old('is_department_head', $user->is_department_head ?? false) ? 'checked' : '' }}>
                            Department Head — can approve/reject edit permission requests from department personnel
                        </label>
                    </div>
                </div>

                <!-- Admin Permissions -->
                <div id="adminPermissionsSection" class="eu3-section" style="display: none;">
                    <div class="eu3-section-header">
                        <div class="eu3-section-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m.9-9.69L6.3 4.95m9.45 1.26L17.7 4.95M3 8.25V19.5A2.25 2.25 0 005.25 21.75h13.5A2.25 2.25 0 0021 19.5V8.25m-18 0h18"/></svg>
                        </div>
                        <div>
                            <div class="eu3-section-title">Admin Permissions</div>
                            <div class="eu3-section-desc">Choose the areas this additional Admin can access.</div>
                        </div>
                    </div>
                    <div class="eu3-permissions-grid">
                        <label class="eu3-checkbox">
                            <input type="checkbox" name="permissions[can_manage_users]" value="1" {{ ($userPermissions['can_manage_users'] ?? true) ? 'checked' : '' }}>
                            User Access Management
                        </label>
                        <label class="eu3-checkbox">
                            <input type="checkbox" name="permissions[can_view_reports]" value="1" {{ ($userPermissions['can_view_reports'] ?? true) ? 'checked' : '' }}>
                            Reports
                        </label>
                        <label class="eu3-checkbox">
                            <input type="checkbox" name="permissions[can_manage_audit_logs]" value="1" {{ ($userPermissions['can_manage_audit_logs'] ?? true) ? 'checked' : '' }}>
                            Audit Logs
                        </label>
                        <label class="eu3-checkbox">
                            <input type="checkbox" name="permissions[can_manage_backups]" value="1" {{ ($userPermissions['can_manage_backups'] ?? true) ? 'checked' : '' }}>
                            Database Backups
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="eu3-actions">
                    <button type="submit" class="eu3-btn eu3-btn-primary" id="submitBtn">
                        <span class="eu3-spinner" id="submitSpinner"></span>
                        <span id="submitLabel">Update User</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="eu3-btn eu3-btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const departmentPermissionsSection = document.getElementById('departmentPermissionsSection');
        const adminPermissionsSection = document.getElementById('adminPermissionsSection');
        const form = document.getElementById('editUserForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitSpinner = document.getElementById('submitSpinner');
        const submitLabel = document.getElementById('submitLabel');

        function updatePermissionsVisibility() {
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const selectedRoleText = selectedOption.text.toLowerCase();

            if (selectedRoleText.includes('department')) {
                departmentPermissionsSection.style.display = 'block';
            } else {
                departmentPermissionsSection.style.display = 'none';
            }

            adminPermissionsSection.style.display = selectedRoleText.includes('admin') ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', updatePermissionsVisibility);
        updatePermissionsVisibility();

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
            submitSpinner.style.display = 'inline-block';
            submitLabel.textContent = 'Updating...';
        });
    });
</script>
@endsection