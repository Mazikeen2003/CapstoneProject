@extends('layouts.admin')

@section('content')
<style>
/* ===== ADMIN DASHBOARD - FULL RICH REDESIGN ===== */
.adm-wrap {
    --adm-bg: #f4f4f5;
    --adm-surface: #ffffff;
    --adm-ink: #0f0d1f;
    --adm-muted: #6b7280;
    --adm-line: rgba(0,0,0,0.06);
    --adm-line-strong: rgba(0,0,0,0.1);
    --adm-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.04);
    --adm-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.08), 0 4px 8px -4px rgba(0,0,0,0.04);
    --adm-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.14), 0 12px 24px -12px rgba(0,0,0,0.08);
    --adm-gold: #f59e0b;
    --adm-indigo: #4338ca;
    --adm-radius-xl: 28px;
    --adm-radius: 20px;
    --adm-radius-sm: 16px;
    --adm-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
}
.dark .adm-wrap {
    --adm-bg: #0f0e1a;
    --adm-surface: #141321;
    --adm-ink: #f8f7f5;
    --adm-muted: #94a3b8;
    --adm-line: rgba(255,255,255,0.06);
    --adm-line-strong: rgba(255,255,255,0.1);
    --adm-shadow: 0 1px 3px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.1);
    --adm-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.2), 0 4px 8px -4px rgba(0,0,0,0.15);
    --adm-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.35), 0 12px 24px -12px rgba(0,0,0,0.25);
}

.adm-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 24px 20px 48px;
    display: flex;
    flex-direction: column;
    gap: 32px;
}
@media (min-width: 640px) {
    .adm-wrap { padding: 32px 32px 56px; }
}

/* ========================================
   HERO - DRAMATIC GRADIENT + DEPTH
   ======================================== */
.adm-hero {
    position: relative;
    border-radius: var(--adm-radius-xl);
    padding: 44px 28px;
    overflow: hidden;
    background:
        linear-gradient(135deg, #0c0a1f 0%, #1e1b4b 25%, #312e81 55%, #4338ca 85%, #6366f1 100%);
    box-shadow:
        var(--adm-shadow-lg),
        inset 0 1px 0 rgba(255,255,255,0.08),
        0 0 100px -20px rgba(99,102,241,0.18);
}
@media (min-width: 640px) {
    .adm-hero { padding: 56px 44px; }
}
/* Gold orb */
.adm-hero::before {
    content: '';
    position: absolute;
    top: -120px;
    right: -60px;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 60%);
    pointer-events: none;
}
/* Indigo orb */
.adm-hero::after {
    content: '';
    position: absolute;
    bottom: -100px;
    left: -40px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 60%);
    pointer-events: none;
}
/* Grid pattern */
.adm-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 52px 52px;
    pointer-events: none;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 75%);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 75%);
}
/* Diagonal accent */
.adm-hero-line {
    position: absolute;
    top: 0;
    right: 100px;
    width: 1px;
    height: 100%;
    background: linear-gradient(180deg, transparent 0%, rgba(245,158,11,0.25) 25%, rgba(245,158,11,0.1) 75%, transparent 100%);
    pointer-events: none;
}
.adm-hero-line-2 {
    position: absolute;
    top: 0;
    right: 160px;
    width: 1px;
    height: 100%;
    background: linear-gradient(180deg, transparent 0%, rgba(99,102,241,0.15) 30%, rgba(99,102,241,0.05) 70%, transparent 100%);
    pointer-events: none;
}
.adm-hero-inner {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
@media (min-width: 768px) {
    .adm-hero-inner {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
    }
}
/* Label pill */
.adm-hero-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.22em;
    color: #fbbf24;
    padding: 8px 18px;
    border-radius: 100px;
    background: rgba(245,158,11,0.1);
    border: 1px solid rgba(245,158,11,0.15);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 20px -4px rgba(245,158,11,0.12);
    width: fit-content;
}
.adm-hero-label::before {
    content: '';
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #fbbf24;
    box-shadow: 0 0 0 3px rgba(251,191,36,0.25);
    animation: admPulse 2.5s ease-in-out infinite;
}
@keyframes admPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.4); }
}
.adm-hero-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.03em;
    line-height: 1.05;
    margin-top: 16px;
    text-shadow: 0 2px 24px rgba(0,0,0,0.25);
}
@media (min-width: 640px) {
    .adm-hero-title { font-size: 3.25rem; }
}
@media (min-width: 1024px) {
    .adm-hero-title { font-size: 4rem; }
}
.adm-hero-desc {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    color: rgba(255,255,255,0.55);
    line-height: 1.7;
    margin-top: 14px;
    max-width: 440px;
}
/* Hero right: mini stat chips */
.adm-hero-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.adm-hero-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 100px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    font-family: var(--font-body);
    font-size: 0.75rem;
    font-weight: 700;
    color: rgba(255,255,255,0.7);
    transition: var(--adm-transition);
}
.adm-hero-chip:hover {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.12);
    transform: translateY(-2px);
}
.adm-hero-chip-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}
.adm-hero-chip-dot.blue { background: #60a5fa; box-shadow: 0 0 0 2px rgba(96,165,250,0.2); }
.adm-hero-chip-dot.emerald { background: #34d399; box-shadow: 0 0 0 2px rgba(52,211,153,0.2); }
.adm-hero-chip-dot.amber { background: #fbbf24; box-shadow: 0 0 0 2px rgba(251,191,36,0.2); }
.adm-hero-chip-dot.rose { background: #fb7185; box-shadow: 0 0 0 2px rgba(251,113,133,0.2); }

/* ========================================
   STATS GRID - GLASSMORPHISM CARDS
   ======================================== */
.adm-stats {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}
@media (min-width: 640px) {
    .adm-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .adm-stats { grid-template-columns: repeat(4, 1fr); }
}

.adm-stat {
    position: relative;
    background: var(--adm-surface);
    border: 1px solid var(--adm-line);
    border-radius: var(--adm-radius);
    padding: 32px;
    box-shadow: var(--adm-shadow);
    transition: var(--adm-transition);
    overflow: hidden;
}
.adm-stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    border-radius: 4px 4px 0 0;
}
.adm-stat::after {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    opacity: 0.025;
    pointer-events: none;
}
.adm-stat:hover {
    transform: translateY(-6px);
    box-shadow: var(--adm-shadow-lg);
    border-color: var(--adm-line-strong);
}
.adm-stat.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.adm-stat.blue::after { background: #3b82f6; }
.adm-stat.emerald::before { background: linear-gradient(90deg, #10b981, #34d399); }
.adm-stat.emerald::after { background: #10b981; }
.adm-stat.amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.adm-stat.amber::after { background: #f59e0b; }
.adm-stat.rose::before { background: linear-gradient(90deg, #f43f5e, #fb7185); }
.adm-stat.rose::after { background: #f43f5e; }

.adm-stat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.adm-stat-label {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--adm-muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
}
.adm-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}
.adm-stat-icon::before {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0.06;
    border-radius: inherit;
}
.adm-stat-icon svg { width: 26px; height: 26px; position: relative; z-index: 1; }
.adm-stat-icon.blue {
    background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
    color: #2563eb;
    box-shadow: 0 8px 24px -6px rgba(37,99,235,0.22);
}
.adm-stat-icon.blue::before { background: #3b82f6; }
.adm-stat-icon.emerald {
    background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%);
    color: #059669;
    box-shadow: 0 8px 24px -6px rgba(5,150,105,0.22);
}
.adm-stat-icon.emerald::before { background: #10b981; }
.adm-stat-icon.amber {
    background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
    color: #d97706;
    box-shadow: 0 8px 24px -6px rgba(217,119,6,0.22);
}
.adm-stat-icon.amber::before { background: #f59e0b; }
.adm-stat-icon.rose {
    background: linear-gradient(135deg, #ffe4e6 0%, #fff1f2 100%);
    color: #e11d48;
    box-shadow: 0 8px 24px -6px rgba(225,29,72,0.22);
}
.adm-stat-icon.rose::before { background: #f43f5e; }
.dark .adm-stat-icon.blue { background: rgba(59,130,246,0.08); color: #60a5fa; box-shadow: 0 8px 24px -6px rgba(96,165,250,0.1); }
.dark .adm-stat-icon.emerald { background: rgba(16,185,129,0.08); color: #34d399; box-shadow: 0 8px 24px -6px rgba(52,211,153,0.1); }
.dark .adm-stat-icon.amber { background: rgba(245,158,11,0.08); color: #fbbf24; box-shadow: 0 8px 24px -6px rgba(251,191,36,0.1); }
.dark .adm-stat-icon.rose { background: rgba(244,63,94,0.08); color: #fb7185; box-shadow: 0 8px 24px -6px rgba(251,113,133,0.1); }

.adm-stat-value {
    font-family: var(--font-display);
    font-size: 3rem;
    font-weight: 800;
    color: var(--adm-ink);
    letter-spacing: -0.04em;
    margin-top: 24px;
    line-height: 1;
}
@media (min-width: 640px) {
    .adm-stat-value { font-size: 3.25rem; }
}
.adm-stat-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid var(--adm-line);
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--adm-muted);
}
.adm-stat-meta span {
    display: flex;
    align-items: center;
    gap: 8px;
}
.adm-stat-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}
.adm-stat-dot.published { background: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.12); }
.adm-stat-dot.draft { background: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.12); }

/* ========================================
   ACTIVITY - RICH CARD
   ======================================== */
.adm-activity {
    background: var(--adm-surface);
    border: 1px solid var(--adm-line);
    border-radius: var(--adm-radius);
    box-shadow: var(--adm-shadow);
    overflow: hidden;
    position: relative;
}
.adm-activity::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24, #fcd34d, #fbbf24, #f59e0b);
    background-size: 400% 100%;
    animation: admShimmer 5s ease infinite;
}
@keyframes admShimmer {
    0% { background-position: 400% 0; }
    100% { background-position: -400% 0; }
}
.adm-activity-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 28px 32px;
    border-bottom: 1px solid var(--adm-line);
    background: linear-gradient(180deg, rgba(245,158,11,0.02) 0%, transparent 100%);
}
.adm-activity-header h2 {
    font-family: var(--font-display);
    font-size: 1.375rem;
    font-weight: 800;
    color: var(--adm-ink);
    letter-spacing: -0.02em;
}
.adm-activity-header p {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--adm-muted);
    margin-top: 4px;
}
.adm-live-badge {
    display: none;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 100px;
    background: rgba(245,158,11,0.06);
    border: 1px solid rgba(245,158,11,0.1);
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--adm-gold);
    white-space: nowrap;
}
@media (min-width: 640px) {
    .adm-live-badge { display: inline-flex; }
}
.adm-live-badge::before {
    content: '';
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--adm-gold);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
    animation: admLivePulse 2s ease-in-out infinite;
}
@keyframes admLivePulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
}
.adm-activity-body {
    padding: 24px 32px 32px;
}

/* Activity items */
.adm-activity-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.adm-activity-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px 24px;
    border-radius: var(--adm-radius-sm);
    border: 1px solid var(--adm-line);
    background: var(--adm-bg);
    transition: var(--adm-transition);
    position: relative;
    overflow: hidden;
}
.adm-activity-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #6366f1, #4338ca);
    opacity: 0;
    transition: opacity 0.3s;
}
.adm-activity-item:hover {
    background: var(--adm-surface);
    border-color: rgba(99,102,241,0.15);
    box-shadow: var(--adm-shadow-md);
    transform: translateX(6px);
}
.adm-activity-item:hover::before {
    opacity: 1;
}
.adm-activity-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 8px 24px -6px rgba(37,99,235,0.12);
}
.dark .adm-activity-icon {
    background: rgba(59,130,246,0.08);
    color: #60a5fa;
    box-shadow: 0 8px 24px -6px rgba(96,165,250,0.08);
}
.adm-activity-icon svg { width: 22px; height: 22px; }
.adm-activity-content {
    min-width: 0;
    flex: 1;
}
.adm-activity-top {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.adm-activity-user {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--adm-ink);
}
.adm-activity-time {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 700;
    color: var(--adm-muted);
    padding: 5px 14px;
    border-radius: 100px;
    background: var(--adm-line);
    white-space: nowrap;
}
.adm-activity-action {
    font-family: var(--font-body);
    font-size: 0.875rem;
    color: var(--adm-muted);
    line-height: 1.65;
    margin-top: 6px;
}

/* Empty */
.adm-empty {
    padding: 72px 24px;
    text-align: center;
    border-radius: var(--adm-radius-sm);
    border: 1px dashed var(--adm-line-strong);
    background: var(--adm-bg);
}
.adm-empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    border-radius: 24px;
    background: var(--adm-surface);
    color: var(--adm-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--adm-shadow);
}
.adm-empty-icon svg { width: 36px; height: 36px; }
.adm-empty p {
    font-family: var(--font-body);
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--adm-muted);
}

/* ===== ANIMATIONS ===== */
@keyframes admFadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
.adm-animate {
    opacity: 0;
    animation: admFadeUp 0.6s ease forwards;
}
.adm-animate:nth-child(1) { animation-delay: 0.05s; }
.adm-animate:nth-child(2) { animation-delay: 0.1s; }
.adm-animate:nth-child(3) { animation-delay: 0.15s; }
.adm-animate:nth-child(4) { animation-delay: 0.2s; }
.adm-animate:nth-child(5) { animation-delay: 0.25s; }
</style>

<div class="adm-wrap">
    <!-- ===== HERO ===== -->
    <div class="adm-hero adm-animate">
        <div class="adm-hero-grid"></div>
        <div class="adm-hero-line"></div>
        <div class="adm-hero-line-2"></div>
        <div class="adm-hero-inner">
            <div>
                <span class="adm-hero-label">System Command Center</span>
                <h1 class="adm-hero-title">Admin Dashboard</h1>
                <p class="adm-hero-desc">A focused view of access, projects, reports, and system activity.</p>
            </div>
            <div class="adm-hero-chips">
                <div class="adm-hero-chip"><span class="adm-hero-chip-dot blue"></span>{{ $users ?? 0 }} Users</div>
                <div class="adm-hero-chip"><span class="adm-hero-chip-dot emerald"></span>{{ $projects ?? 0 }} Projects</div>
                <div class="adm-hero-chip"><span class="adm-hero-chip-dot amber"></span>{{ $reports ?? 0 }} Reports</div>
                <div class="adm-hero-chip"><span class="adm-hero-chip-dot rose"></span>{{ $auditLogs ?? 0 }} Logs</div>
            </div>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="adm-stats">
        <!-- Users -->
        <div class="adm-stat blue adm-animate">
            <div class="adm-stat-header">
                <span class="adm-stat-label">Users</span>
                <div class="adm-stat-icon blue">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
            </div>
            <p class="adm-stat-value">{{ $users ?? 0 }}</p>
        </div>

        <!-- Projects -->
        <div class="adm-stat emerald adm-animate">
            <div class="adm-stat-header">
                <span class="adm-stat-label">Projects</span>
                <div class="adm-stat-icon emerald">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L12 3l9 4.5v9L12 21l-9-4.5v-9zM3 7.5l9 4.5m9-4.5l-9 4.5m0 0V21"/></svg>
                </div>
            </div>
            <p class="adm-stat-value">{{ $projects ?? 0 }}</p>
            <div class="adm-stat-meta">
                <span><span class="adm-stat-dot published"></span>Published: {{ $projects_published ?? 0 }}</span>
                <span><span class="adm-stat-dot draft"></span>Drafts: {{ $projects_draft ?? 0 }}</span>
            </div>
        </div>

        <!-- Reports -->
        <div class="adm-stat amber adm-animate">
            <div class="adm-stat-header">
                <span class="adm-stat-label">Reports</span>
                <div class="adm-stat-icon amber">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2h9l4 4v16H6V2zm9 0v5h4M9 13h6m-6 4h6M9 9h2"/></svg>
                </div>
            </div>
            <p class="adm-stat-value">{{ $reports ?? 0 }}</p>
        </div>

        <!-- Audit Logs -->
        <div class="adm-stat rose adm-animate">
            <div class="adm-stat-header">
                <span class="adm-stat-label">Audit Logs</span>
                <div class="adm-stat-icon rose">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 4.5-3 7.8-7 9-4-1.2-7-4.5-7-9V7l7-4zm-3 9l2 2 4-4"/></svg>
                </div>
            </div>
            <p class="adm-stat-value">{{ $auditLogs ?? 0 }}</p>
        </div>
    </div>

    <!-- ===== ACTIVITY ===== -->
    <div class="adm-activity adm-animate">
        <div class="adm-activity-header">
            <div>
                <h2>Recent Activity</h2>
                <p>Latest system actions and changes.</p>
            </div>
            <span class="adm-live-badge">Live feed</span>
        </div>
        <div class="adm-activity-body">
            @if(!empty($recentActivity) && $recentActivity->count())
                <ul class="adm-activity-list">
                    @foreach($recentActivity as $activity)
                        <li class="adm-activity-item">
                            <div class="adm-activity-icon">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="adm-activity-content">
                                <div class="adm-activity-top">
                                    <span class="adm-activity-user">{{ optional($activity->user)->username ?? optional($activity->user)->user_email ?? 'System' }}</span>
                                    <span class="adm-activity-time">{{ optional($activity->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="adm-activity-action">{{ $activity->action }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="adm-empty">
                    <div class="adm-empty-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p>No recent activity.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection