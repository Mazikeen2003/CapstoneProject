@extends('layouts.department')

@section('content')

@php
    $statusOrder = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed', 'On Hold', 'Cancelled'];
    $statusColors = ['#fbbf24', '#f59e0b', '#3b82f6', '#8b5cf6', '#0ea5e9', '#10b981', '#ef4444', '#64748b'];
    $statusAliases = [
        'Planning' => 'Proposed',
        'Procurement' => 'For bidding',
        'Bidding - Success' => 'Award of contract',
        'On Going' => 'Implementation',
    ];
    $lifecycleStatusCounts = collect($byStatus)->reduce(function ($counts, $item, $status) use ($statusAliases) {
        $lifecycleStatus = $statusAliases[$status] ?? $status;
        $counts[$lifecycleStatus] = ($counts[$lifecycleStatus] ?? 0) + ($item['count'] ?? 0);
        return $counts;
    }, []);
    $statusCounts = collect($statusOrder)->map(fn ($status) => $lifecycleStatusCounts[$status] ?? 0)->values();
    $remainingBudget = max(($budgetStats['total_budget'] ?? 0) - ($budgetStats['total_spent'] ?? 0), 0);
    $totalBudget = (float) ($stats['total_budget'] ?? 0);
    $totalBudgetDisplay = $totalBudget >= 1000000000
        ? '₱' . number_format($totalBudget / 1000000000, 1) . 'B'
        : ($totalBudget >= 1000000 ? '₱' . number_format($totalBudget / 1000000, 1) . 'M' : '₱' . number_format($totalBudget, 0));
    $barangayLabels = isset($byBarangay) ? $byBarangay->take(10)->keys()->values() : collect();
    $barangayValues = isset($byBarangay) ? $byBarangay->take(10)->map(fn ($item) => $item['budget'] ?? 0)->values() : collect();
    $barangayProjectCounts = isset($byBarangay) ? $byBarangay->take(10)->map(fn ($item) => $item['count'] ?? 0)->values() : collect();
@endphp

<style>
/* ===== DESIGN SYSTEM ===== */
.dept-analytics-container {
    --da-bg: #f0eeea;
    --da-surface: #ffffff;
    --da-raised: #f8f7f5;
    --da-ink: #0f0d1f;
    --da-ink-secondary: #374151;
    --da-muted: #6b7280;
    --da-line: rgba(0,0,0,0.07);
    --da-line-strong: rgba(0,0,0,0.14);
    --da-shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
    --da-shadow: 0 4px 6px -1px rgba(0,0,0,0.06), 0 2px 4px -2px rgba(0,0,0,0.04);
    --da-shadow-md: 0 8px 16px -4px rgba(0,0,0,0.08), 0 4px 8px -4px rgba(0,0,0,0.04);
    --da-shadow-lg: 0 16px 32px -8px rgba(0,0,0,0.1), 0 8px 16px -8px rgba(0,0,0,0.06);
    --da-shadow-xl: 0 24px 48px -12px rgba(0,0,0,0.12), 0 12px 24px -12px rgba(0,0,0,0.08);
    --da-radius: 20px;
    --da-radius-sm: 14px;
    --da-radius-xs: 10px;
}
.dark .dept-analytics-container {
    --da-bg: #0a0912;
    --da-surface: #141321;
    --da-raised: #1c1b2e;
    --da-ink: #f8f7f5;
    --da-ink-secondary: #a1a1aa;
    --da-muted: #71717a;
    --da-line: rgba(255,255,255,0.06);
    --da-line-strong: rgba(255,255,255,0.12);
    --da-shadow-sm: 0 1px 3px rgba(0,0,0,0.3);
    --da-shadow: 0 4px 6px -1px rgba(0,0,0,0.3), 0 2px 4px -2px rgba(0,0,0,0.2);
    --da-shadow-md: 0 8px 16px -4px rgba(0,0,0,0.4), 0 4px 8px -4px rgba(0,0,0,0.3);
    --da-shadow-lg: 0 16px 32px -8px rgba(0,0,0,0.5), 0 8px 16px -8px rgba(0,0,0,0.4);
    --da-shadow-xl: 0 24px 48px -12px rgba(0,0,0,0.6), 0 12px 24px -12px rgba(0,0,0,0.5);
}

.dept-analytics-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    background: var(--da-bg);
    color: var(--da-ink);
    transition: background 0.3s, color 0.3s;
    min-height: 100vh;
}
@media (min-width: 640px) { .dept-analytics-container { padding: 32px; } }
@media (min-width: 1024px) { .dept-analytics-container { padding: 40px; } }

/* ===== HERO ===== */
.dept-analytics-hero {
    position: relative;
    background: linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%);
    border-radius: var(--da-radius);
    padding: 36px 40px;
    margin-bottom: 28px;
    box-shadow: var(--da-shadow-xl);
    overflow: hidden;
}
.dept-analytics-hero-gold {
    background: linear-gradient(135deg, #451a03 0%, #78350f 30%, #b45309 70%, #d97706 100%);
}
html.dark-mode .dept-analytics-hero-gold {
    background: linear-gradient(135deg, #451a03 0%, #78350f 30%, #b45309 70%, #d97706 100%);
}
.dept-analytics-hero-red,
html.dark-mode .dept-analytics-hero-red {
    background: linear-gradient(135deg, #24070b 0%, #4c0d14 30%, #991b1b 70%, #dc2626 100%);
}
@media (min-width: 640px) { .dept-analytics-hero { padding: 44px 52px; } }
.dept-analytics-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.5;
    pointer-events: none;
}
.dept-analytics-hero::after {
    content: "";
    position: absolute;
    top: -40%;
    right: -5%;
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, rgba(168,85,247,0.24) 0%, transparent 60%);
    pointer-events: none;
    animation: heroGlow 10s ease-in-out infinite;
}
.dept-analytics-hero-gold::after {
    background: radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 60%);
}
.dept-analytics-hero-red::after {
    background: radial-gradient(circle, rgba(239,68,68,0.2) 0%, transparent 60%);
}
@keyframes heroGlow {
    0%, 100% { transform: scale(1); opacity: 0.7; }
    50% { transform: scale(1.15); opacity: 1; }
}
.dept-analytics-hero-inner { position: relative; z-index: 1; }
.dept-analytics-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 100px;
    font-size: 0.6875rem;
    font-weight: 700;
    color: rgba(255,255,255,0.85);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 16px;
}
.dept-analytics-hero-badge svg { width: 14px; height: 14px; }
.dept-analytics-hero-title {
    font-family: "Plus Jakarta Sans", "Inter", sans-serif;
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.15;
    letter-spacing: -0.03em;
    margin-bottom: 10px;
}
.dept-analytics-hero-subtitle {
    font-size: 1rem;
    color: rgba(255,255,255,0.65);
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.dept-analytics-hero-subtitle span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.dept-analytics-hero-subtitle svg { width: 16px; height: 16px; opacity: 0.7; }

/* ===== KPI CARDS ===== */
.dept-kpi-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    margin-bottom: 24px;
}
@media (min-width: 640px) {
    .dept-kpi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1280px) {
    .dept-kpi-grid { grid-template-columns: repeat(5, 1fr); }
}
.dept-kpi-card {
    background: var(--da-surface);
    border: 1px solid var(--da-line);
    border-radius: var(--da-radius-sm);
    padding: 24px;
    box-shadow: var(--da-shadow-sm);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.dept-kpi-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    opacity: 0;
    transition: opacity 0.25s;
}
.dept-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--da-shadow-md);
}
.dept-kpi-card:hover::before { opacity: 1; }
.dept-kpi-card.accent::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
.dept-kpi-card.success::before { background: linear-gradient(90deg, #10b981, #059669); }
.dept-kpi-card.info::before { background: linear-gradient(90deg, #3b82f6, #2563eb); }
.dept-kpi-card.danger::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
.dept-kpi-card.dark::before { background: linear-gradient(90deg, #1e1b4b, #4338ca); }

.dept-kpi-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px;
}
.dept-kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dept-kpi-icon svg { width: 24px; height: 24px; }
.dept-kpi-icon.blue { background: #dbeafe; color: #2563eb; }
.dept-kpi-icon.emerald { background: #d1fae5; color: #059669; }
.dept-kpi-icon.amber { background: #fef3c7; color: #b45309; }
.dept-kpi-icon.rose { background: #ffe4e6; color: #e11d48; }
.dept-kpi-icon.indigo { background: #e0e7ff; color: #4338ca; }
.dark .dept-kpi-icon.blue { background: rgba(59,130,246,0.12); color: #60a5fa; }
.dark .dept-kpi-icon.emerald { background: rgba(16,185,129,0.12); color: #34d399; }
.dark .dept-kpi-icon.amber { background: rgba(251,191,36,0.12); color: #fbbf24; }
.dark .dept-kpi-icon.rose { background: rgba(244,63,94,0.12); color: #fb7185; }
.dark .dept-kpi-icon.indigo { background: rgba(99,102,241,0.12); color: #a5b4fc; }

.dept-kpi-label {
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--da-muted);
    margin-bottom: 8px;
}
.dept-kpi-value {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.02em;
}
.dept-kpi-value.dark { color: #1e1b4b; }
.dark .dept-kpi-value.dark { color: #f8f7f5; }
html.dark-mode .dept-kpi-card.accent .dept-kpi-value,
.dark .dept-kpi-card.accent .dept-kpi-value {
    color: #fbbf24 !important;
}

/* ===== INSIGHT CARDS ===== */
.dept-insight-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    margin-bottom: 28px;
}
@media (min-width: 640px) {
    .dept-insight-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .dept-insight-grid { grid-template-columns: repeat(4, 1fr); }
}
.dept-insight-card {
    background: var(--da-surface);
    border: 1px solid var(--da-line);
    border-radius: var(--da-radius-sm);
    padding: 24px;
    box-shadow: var(--da-shadow-sm);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.dept-insight-card::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    opacity: 0;
    transition: opacity 0.25s;
}
.dept-insight-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--da-shadow-md);
}
.dept-insight-card:hover::before { opacity: 1; }
.dept-insight-card.danger::before  { background: linear-gradient(180deg, #ef4444, #dc2626); }
.dept-insight-card.warning::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
.dept-insight-card.info::before    { background: linear-gradient(180deg, #3b82f6, #2563eb); }
.dept-insight-card.success::before { background: linear-gradient(180deg, #10b981, #059669); }

.dept-insight-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.dept-insight-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dept-insight-icon svg { width: 20px; height: 20px; }
.dept-insight-icon.danger  { background: #fef2f2; color: #dc2626; }
.dept-insight-icon.warning { background: #fffbeb; color: #d97706; }
.dept-insight-icon.info    { background: #eff6ff; color: #2563eb; }
.dept-insight-icon.success { background: #f0fdf4; color: #059669; }
.dark .dept-insight-icon.danger  { background: rgba(239,68,68,0.08); color: #f87171; }
.dark .dept-insight-icon.warning { background: rgba(245,158,11,0.08); color: #fbbf24; }
.dark .dept-insight-icon.info    { background: rgba(59,130,246,0.08); color: #60a5fa; }
.dark .dept-insight-icon.success { background: rgba(16,185,129,0.08); color: #34d399; }

.dept-insight-label {
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--da-muted);
    margin-bottom: 8px;
}
.dept-insight-value {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.02em;
    margin-bottom: 6px;
}
.dept-insight-caption {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--da-muted);
}

/* ===== LIFECYCLE SECTION ===== */
.dept-lifecycle-card {
    background: var(--da-surface);
    border: 1px solid var(--da-line);
    border-radius: var(--da-radius-sm);
    padding: 28px;
    box-shadow: var(--da-shadow-sm);
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.dept-lifecycle-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #f59e0b, #d97706, #8b5cf6, #3b82f6, #10b981);
}
.dept-lifecycle-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.dept-lifecycle-title-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
}
.dept-lifecycle-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    color: #7c3aed;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dark .dept-lifecycle-icon {
    background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(139,92,246,0.08));
    color: #a78bfa;
}
.dept-lifecycle-icon svg { width: 22px; height: 22px; }
.dept-lifecycle-title {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1.0625rem;
    font-weight: 800;
    color: var(--da-ink);
    letter-spacing: -0.01em;
}
.dept-lifecycle-subtitle {
    font-size: 0.75rem;
    color: var(--da-muted);
    margin-top: 2px;
}
.dept-lifecycle-hint {
    font-size: 0.6875rem;
    font-weight: 600;
    color: var(--da-muted);
    padding: 6px 12px;
    background: var(--da-raised);
    border: 1px solid var(--da-line);
    border-radius: 100px;
}
.dept-lifecycle-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 14px;
}
@media (min-width: 640px) {
    .dept-lifecycle-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .dept-lifecycle-grid { grid-template-columns: repeat(4, 1fr); }
}
.dept-lifecycle-item {
    background: var(--da-raised);
    border: 1px solid var(--da-line);
    border-radius: var(--da-radius-xs);
    padding: 18px;
    transition: all 0.2s ease;
}
.dept-lifecycle-item:hover {
    background: var(--da-surface);
    border-color: var(--da-line-strong);
    box-shadow: var(--da-shadow-md);
    transform: translateY(-2px);
}
.dept-lifecycle-item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.dept-lifecycle-item-label {
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--da-ink-secondary);
}
.dept-lifecycle-item-count {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--da-ink);
}
.dept-lifecycle-track {
    height: 8px;
    background: var(--da-line);
    border-radius: 100px;
    overflow: hidden;
}
.dept-lifecycle-fill {
    height: 100%;
    border-radius: 100px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}
.dept-lifecycle-fill::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
    animation: shimmer 2.5s ease-in-out infinite;
}
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* ===== CHART CARDS ===== */
.dept-chart-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    margin-bottom: 28px;
}
@media (min-width: 1280px) {
    .dept-chart-grid { grid-template-columns: 1fr 1fr; }
}
.dept-chart-card {
    background: var(--da-surface);
    border: 1px solid var(--da-line);
    border-radius: var(--da-radius-sm);
    padding: 28px;
    box-shadow: var(--da-shadow-sm);
    transition: all 0.25s ease;
}
.dept-chart-card:hover {
    box-shadow: var(--da-shadow-md);
}
.dept-chart-card-header {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 24px;
}
@media (min-width: 640px) {
    .dept-chart-card-header {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
    }
}
.dept-chart-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}
.dept-chart-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dept-chart-icon.blue { background: #dbeafe; color: #2563eb; }
.dept-chart-icon.amber { background: #fef3c7; color: #b45309; }
.dark .dept-chart-icon.blue { background: rgba(59,130,246,0.12); color: #60a5fa; }
.dark .dept-chart-icon.amber { background: rgba(251,191,36,0.12); color: #fbbf24; }
.dept-chart-icon svg { width: 20px; height: 20px; }
.dept-chart-title {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1rem;
    font-weight: 800;
    color: var(--da-ink);
    letter-spacing: -0.01em;
}
.dept-chart-subtitle {
    font-size: 0.75rem;
    color: var(--da-muted);
    margin-top: 2px;
}

/* Filter form */
.dept-filter-form {
    display: flex;
    gap: 10px;
    align-items: stretch;
}
.dept-filter-select {
    min-width: 140px;
    padding: 9px 14px;
    border-radius: 10px;
    border: 1px solid var(--da-line-strong);
    background: var(--da-raised);
    color: var(--da-ink);
    font-size: 0.8125rem;
    font-family: inherit;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}
.dept-filter-select:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
}
.dept-filter-btn {
    padding: 9px 20px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #1e1b4b, #4338ca);
    color: white;
    font-size: 0.8125rem;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
}
.dept-filter-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px -2px rgba(30, 27, 75, 0.4);
}
.dept-chart-body {
    height: 320px;
    position: relative;
}
@media (min-width: 640px) {
    .dept-chart-body { height: 340px; }
}

/* ===== BARANGAY CHART ===== */
.dept-barangay-card {
    background: var(--da-surface);
    border: 1px solid var(--da-line);
    border-radius: var(--da-radius-sm);
    padding: 28px;
    box-shadow: var(--da-shadow-sm);
    margin-bottom: 28px;
    transition: all 0.25s ease;
}
.dept-barangay-card:hover {
    box-shadow: var(--da-shadow-md);
}
.dept-barangay-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}
.dept-barangay-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #d1fae5;
    color: #059669;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dark .dept-barangay-icon {
    background: rgba(16,185,129,0.12);
    color: #34d399;
}
.dept-barangay-icon svg { width: 20px; height: 20px; }
.dept-barangay-title {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1rem;
    font-weight: 800;
    color: var(--da-ink);
    letter-spacing: -0.01em;
}
.dept-barangay-body {
    height: 360px;
    position: relative;
}
@media (min-width: 640px) {
    .dept-barangay-body { height: 400px; }
}

/* ===== EMPTY STATE ===== */
.dept-analytics-empty {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border: 1px solid #bfdbfe;
    border-radius: var(--da-radius-xs);
    color: #1e40af;
    font-size: 0.875rem;
    font-weight: 600;
}
.dark .dept-analytics-empty {
    background: linear-gradient(135deg, rgba(59,130,246,0.08), rgba(59,130,246,0.04));
    border-color: rgba(59,130,246,0.2);
    color: #60a5fa;
}
.dept-analytics-empty svg { width: 22px; height: 22px; flex-shrink: 0; }

/* Application theme overrides */
html:not(.dark-mode) .dept-analytics-container {
    --da-bg: #ffffff;
    --da-surface: #ffffff;
    --da-raised: #ffffff;
}

html.dark-mode .dept-analytics-container {
    --da-bg: #0f172a;
    --da-surface: #0f172a;
    --da-raised: #1e293b;
    --da-ink: #f8fafc;
    --da-ink-secondary: #cbd5e1;
    --da-muted: #94a3b8;
    --da-line: rgba(148, 163, 184, 0.2);
    --da-line-strong: rgba(148, 163, 184, 0.35);
}

html.dark-mode .dept-kpi-card,
html.dark-mode .dept-insight-card,
html.dark-mode .dept-lifecycle-card,
html.dark-mode .dept-chart-card,
html.dark-mode .dept-barangay-card {
    background: #141321 !important;
    border: 1px solid #020617 !important;
    box-shadow: inset 0 0 0 1px #1e293b, 0 1px 3px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    color: #f8f7f5 !important;
}

html:not(.dark-mode) .dept-kpi-card,
html:not(.dark-mode) .dept-insight-card,
html:not(.dark-mode) .dept-lifecycle-card,
html:not(.dark-mode) .dept-chart-card,
html:not(.dark-mode) .dept-barangay-card {
    background: #ffffff !important;
    border-color: #dbe3ee !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
}

html.dark-mode .dept-lifecycle-item,
html.dark-mode .dept-lifecycle-hint,
html.dark-mode .dept-filter-select {
    background: #1e293b !important;
    border-color: #475569 !important;
    color: #cbd5e1 !important;
}

html.dark-mode .dept-kpi-icon.blue,
html.dark-mode .dept-chart-icon.blue { background: rgba(59,130,246,0.12); color: #60a5fa; }
html.dark-mode .dept-kpi-icon.emerald,
html.dark-mode .dept-chart-icon.emerald { background: rgba(16,185,129,0.12); color: #34d399; }
html.dark-mode .dept-kpi-icon.amber,
html.dark-mode .dept-chart-icon.amber { background: rgba(251,191,36,0.12); color: #fbbf24; }
html.dark-mode .dept-kpi-icon.rose { background: rgba(244,63,94,0.12); color: #fb7185; }
html.dark-mode .dept-kpi-icon.indigo { background: rgba(99,102,241,0.12); color: #a5b4fc; }
html.dark-mode .dept-insight-icon.danger { background: rgba(239,68,68,0.08); color: #f87171; }
html.dark-mode .dept-insight-icon.warning { background: rgba(245,158,11,0.08); color: #fbbf24; }
html.dark-mode .dept-insight-icon.info { background: rgba(59,130,246,0.08); color: #60a5fa; }
html.dark-mode .dept-insight-icon.success { background: rgba(16,185,129,0.08); color: #34d399; }
html.dark-mode .dept-lifecycle-icon { background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(139,92,246,0.08)); color: #a78bfa; }
html.dark-mode .dept-barangay-icon { background: rgba(16,185,129,0.12); color: #34d399; }

html.dark-mode .dept-kpi-value.dark { color: #f8fafc; }
html.dark-mode .dept-analytics-empty {
    background: linear-gradient(135deg, rgba(59,130,246,0.08), rgba(59,130,246,0.04));
    border-color: rgba(59,130,246,0.2);
    color: #60a5fa;
}

/* ===== ANIMATIONS ===== */
@keyframes daFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.dept-animate {
    animation: daFadeUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0;
}
.dept-animate:nth-child(1) { animation-delay: 0.04s; }
.dept-animate:nth-child(2) { animation-delay: 0.08s; }
.dept-animate:nth-child(3) { animation-delay: 0.12s; }
.dept-animate:nth-child(4) { animation-delay: 0.16s; }
.dept-animate:nth-child(5) { animation-delay: 0.20s; }

@media (prefers-reduced-motion: reduce) {
    .dept-animate { animation: none; opacity: 1; }
    .dept-lifecycle-fill::after { animation: none; }
}
</style>

<div class="dept-analytics-container">

    <!-- HERO -->
    <div class="dept-analytics-hero {{ ($heroTone ?? 'purple') === 'red' ? 'dept-analytics-hero-red' : (($heroTone ?? 'purple') === 'gold' ? 'dept-analytics-hero-gold' : '') }} dept-animate">
        <div class="dept-analytics-hero-inner">
            <div class="dept-analytics-hero-badge">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Analytics Dashboard
            </div>
            <h1 class="dept-analytics-hero-title">{{ $heading }}</h1>
            <div class="dept-analytics-hero-subtitle">
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    Visual overview of project progress and funding
                </span>
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Real-time budget tracking
                </span>
            </div>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="dept-kpi-grid">
        @foreach ([
            ['label' => 'Total Projects', 'value' => $stats['total_projects'], 'color' => '#1e1b4b', 'icon' => 'indigo', 'type' => 'dark'],
            ['label' => 'Completed',      'value' => $stats['completed'],      'color' => '#059669', 'icon' => 'emerald', 'type' => 'success'],
            ['label' => 'Ongoing',        'value' => $stats['ongoing'],        'color' => '#2563eb', 'icon' => 'blue', 'type' => 'info'],
            ['label' => 'On Hold',        'value' => $stats['on_hold'],       'color' => '#dc2626', 'icon' => 'rose', 'type' => 'danger'],
            ['label' => 'Total Budget',   'value' => $totalBudgetDisplay,     'color' => '#1e1b4b', 'icon' => 'amber', 'type' => 'accent'],
        ] as $kpi)
            <div class="dept-kpi-card {{ $kpi['type'] }} dept-animate">
                <div class="dept-kpi-header">
                    <div class="dept-kpi-icon {{ $kpi['icon'] }}">
                        @if($kpi['icon'] === 'indigo')
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z"/></svg>
                        @elseif($kpi['icon'] === 'emerald')
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($kpi['icon'] === 'blue')
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        @elseif($kpi['icon'] === 'rose')
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        @else
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                </div>
                <div class="dept-kpi-label">{{ $kpi['label'] }}</div>
                <div class="dept-kpi-value" style="color: {{ $kpi['color'] }};">{{ $kpi['value'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- INSIGHT CARDS -->
    <div class="dept-insight-grid">
        @foreach ([
            ['label' => 'Needs attention', 'value' => $insights['overdue'], 'caption' => 'Overdue projects', 'color' => '#dc2626', 'icon' => 'danger', 'svg' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'],
            ['label' => 'Coming up', 'value' => $insights['due_soon'], 'caption' => 'Due within 30 days', 'color' => '#d97706', 'icon' => 'warning', 'svg' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Missing updates', 'value' => $insights['without_updates'], 'caption' => 'Active projects', 'color' => '#2563eb', 'icon' => 'info', 'svg' => 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 00.063.853l.041.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z'],
            ['label' => 'Budget used', 'value' => number_format($insights['budget_utilization'], 1) . '%', 'caption' => 'Actual versus approved', 'color' => '#059669', 'icon' => 'success', 'svg' => 'M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941'],
        ] as $insight)
            <div class="dept-insight-card {{ $insight['icon'] }} dept-animate">
                <div class="dept-insight-header">
                    <div class="dept-insight-icon {{ $insight['icon'] }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $insight['svg'] }}"/></svg>
                    </div>
                </div>
                <div class="dept-insight-label">{{ $insight['label'] }}</div>
                <div class="dept-insight-value" style="color: {{ $insight['color'] }};">{{ $insight['value'] }}</div>
                <div class="dept-insight-caption">{{ $insight['caption'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- LIFECYCLE OVERVIEW -->
    <div class="dept-lifecycle-card dept-animate">
        <div class="dept-lifecycle-header">
            <div class="dept-lifecycle-title-wrap">
                <div class="dept-lifecycle-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <div>
                    <div class="dept-lifecycle-title">Lifecycle Overview</div>
                    <div class="dept-lifecycle-subtitle">Projects grouped by their current lifecycle stage</div>
                </div>
            </div>
            <span class="dept-lifecycle-hint">Completed and exception statuses remain visible</span>
        </div>
        <div class="dept-lifecycle-grid">
            @foreach ($statusOrder as $statusIndex => $lifecycleStatus)
                @php
                    $lifecycleCount = $insights['lifecycle_counts'][$lifecycleStatus] ?? 0;
                    $pct = $stats['total_projects'] > 0 ? min(100, ($lifecycleCount / $stats['total_projects']) * 100) : 0;
                @endphp
                <div class="dept-lifecycle-item">
                    <div class="dept-lifecycle-item-header">
                        <span class="dept-lifecycle-item-label">{{ $lifecycleStatus }}</span>
                        <span class="dept-lifecycle-item-count">{{ $lifecycleCount }}</span>
                    </div>
                    <div class="dept-lifecycle-track">
                        <div class="dept-lifecycle-fill" style="width: {{ $pct }}%; background: linear-gradient(90deg, {{ $statusColors[$statusIndex] }}, {{ $statusColors[$statusIndex] }}dd);"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- CHARTS -->
    <div class="dept-chart-grid">
        <!-- Status Distribution -->
        <div class="dept-chart-card dept-animate">
            <div class="dept-chart-card-header">
                <div class="dept-chart-title-wrap">
                    <div class="dept-chart-icon blue">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                    </div>
                    <div>
                        <div class="dept-chart-title">Project Status Distribution</div>
                        <div class="dept-chart-subtitle">Breakdown by lifecycle stage</div>
                    </div>
                </div>
                <form method="GET" class="dept-filter-form">
                    <input type="hidden" name="budget_year" value="{{ $budgetYear }}">
                    <select aria-label="Filter project status by year" name="status_year" class="dept-filter-select">
                        <option value="">All years</option>
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" @selected((string) $statusYear === (string) $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="dept-filter-btn">Filter</button>
                </form>
            </div>
            <div class="dept-chart-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Budget Comparison -->
        <div class="dept-chart-card dept-animate">
            <div class="dept-chart-card-header">
                <div class="dept-chart-title-wrap">
                    <div class="dept-chart-icon amber">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="dept-chart-title">Budget Comparison</div>
                        <div class="dept-chart-subtitle">Allocated vs spent vs remaining</div>
                    </div>
                </div>
                <form method="GET" class="dept-filter-form">
                    <input type="hidden" name="status_year" value="{{ $statusYear }}">
                    <select aria-label="Filter budget comparison by year" name="budget_year" class="dept-filter-select">
                        <option value="">All years</option>
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" @selected((string) $budgetYear === (string) $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="dept-filter-btn">Filter</button>
                </form>
            </div>
            <div class="dept-chart-body">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>
    </div>

    <!-- BARANGAY BUDGET SHARE -->
    @if (isset($byBarangay))
        <div class="dept-barangay-card dept-animate">
            <div class="dept-barangay-header">
                <div class="dept-barangay-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
                <div>
                    <div class="dept-barangay-title">Barangay Budget Share</div>
                    <div class="dept-chart-subtitle">Top 10 barangays by allocated budget</div>
                </div>
            </div>
            <div class="dept-barangay-body">
                <canvas id="barangayChart"></canvas>
            </div>
        </div>
    @endif

    <!-- EMPTY STATE -->
    @if ($stats['total_projects'] === 0)
        <div class="dept-analytics-empty dept-animate">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 00.063.853l.041.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            No project data is available yet.
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    window.addEventListener('theme:changed', () => window.location.reload());

    const analyticsScrollPosition = sessionStorage.getItem('analyticsScrollPosition');
    if (analyticsScrollPosition !== null) {
        sessionStorage.removeItem('analyticsScrollPosition');
        const analyticsScrollContainer = document.querySelector('main');
        if (analyticsScrollContainer && analyticsScrollContainer.scrollHeight > analyticsScrollContainer.clientHeight) {
            analyticsScrollContainer.scrollTo(0, Number(analyticsScrollPosition));
        } else {
            window.scrollTo(0, Number(analyticsScrollPosition));
        }
    }

    document.querySelectorAll('form[method="GET"]').forEach(form => {
        form.addEventListener('submit', () => {
            sessionStorage.setItem('analyticsScrollPosition', String(window.scrollY));
        });
    });

    const statusLabels = @json($statusOrder);
    const statusCounts = @json($statusCounts);
    const darkMode = document.documentElement.classList.contains('dark-mode');
    const chartTextColor = darkMode ? '#e2e8f0' : '#334155';
    const chartGridColor = darkMode ? 'rgba(148, 163, 184, 0.22)' : 'rgba(148, 163, 184, 0.18)';
    Chart.defaults.color = chartTextColor;
    const statusColors = darkMode
        ? ['#fcd34d', '#fbbf24', '#60a5fa', '#c4b5fd', '#38bdf8', '#34d399', '#fb7185', '#94a3b8']
        : @json($statusColors);
    const peso = value => '₱' + Number(value || 0).toLocaleString();
    const smoothAnimation = { duration: 1300, easing: 'easeOutQuart' };
    const smoothHover = { mode: 'nearest', intersect: true, animationDuration: 420 };

    new Chart(document.getElementById('statusChart'), {
        type: 'polarArea',
        data: { labels: statusLabels, datasets: [{ data: statusCounts, backgroundColor: statusColors, hoverOffset: 18, borderWidth: 2, borderColor: darkMode ? '#1e293b' : '#ffffff' }] },
        options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, scales: { r: { ticks: { precision: 0, color: chartTextColor }, grid: { color: chartGridColor } } }, plugins: { legend: { position: 'bottom', labels: { color: chartTextColor, padding: 20, usePointStyle: true, pointStyle: 'circle' } } } }
    });

    new Chart(document.getElementById('budgetChart'), {
        type: 'bar',
        data: { labels: ['Allocated', 'Spent', 'Remaining'], datasets: [{ data: @json([$budgetStats['total_budget'], $budgetStats['total_spent'], $remainingBudget]), backgroundColor: darkMode ? ['#60a5fa', '#fbbf24', '#34d399'] : ['#1e1b4b', '#f59e0b', '#10b981'], hoverBackgroundColor: darkMode ? ['#93c5fd', '#fcd34d', '#6ee7b7'] : ['#4338ca', '#fbbf24', '#34d399'], borderRadius: 10, hoverBorderRadius: 12, barThickness: 60 }] },
        options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, scales: { y: { beginAtZero: true, grid: { color: chartGridColor }, ticks: { color: chartTextColor, callback: value => peso(value) } }, x: { ticks: { color: chartTextColor }, grid: { display: false } } }, plugins: { legend: { display: false }, tooltip: { callbacks: { label: context => `${context.label}: ${peso(context.raw)}` } } } }
    });

    @if (isset($byBarangay))
        const barangayProjectCounts = @json($barangayProjectCounts);
        new Chart(document.getElementById('barangayChart'), {
            type: 'doughnut',
            data: { labels: @json($barangayLabels), datasets: [{ data: @json($barangayValues), backgroundColor: darkMode ? ['#60a5fa', '#fbbf24', '#34d399', '#93c5fd', '#c4b5fd', '#fb923c', '#f472b6', '#2dd4bf', '#94a3b8', '#fcd34d'] : ['#1e1b4b', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#f97316', '#ec4899', '#14b8a6', '#64748b', '#eab308'], hoverOffset: 20, borderWidth: 2, borderColor: darkMode ? '#1e293b' : '#ffffff' }] },
            options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, cutout: '60%', plugins: { legend: { position: 'bottom', labels: { color: chartTextColor, padding: 16, usePointStyle: true, pointStyle: 'circle', generateLabels: chart => { const labels = Chart.defaults.plugins.legend.labels.generateLabels(chart); return labels.map((item, index) => ({ ...item, text: `${chart.data.labels[index]} — ${barangayProjectCounts[index] || 0} project(s)` })); } } }, tooltip: { callbacks: { label: context => `${context.label}: ${peso(context.raw)} · ${barangayProjectCounts[context.dataIndex] || 0} project(s)` } } } }
        });
    @endif
});
</script>

@endsection