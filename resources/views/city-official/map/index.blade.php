@extends('layouts.city')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<style>
/* ===== CITY MAP - RICH REDESIGN ===== */
.cm-wrap {
    --cm-bg: #f4f4f5;
    --cm-surface: #ffffff;
    --cm-raised: #fafaf9;
    --cm-ink: #0f172a;
    --cm-ink-secondary: #374151;
    --cm-muted: #6b7280;
    --cm-line: rgba(0,0,0,0.06);
    --cm-line-strong: rgba(0,0,0,0.12);
    --cm-shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
    --cm-shadow: 0 4px 12px -2px rgba(0,0,0,0.06);
    --cm-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.08);
    --cm-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.14);
    --cm-gold: #f59e0b;
    --cm-indigo: #4338ca;
    --cm-radius-xl: 28px;
    --cm-radius: 20px;
    --cm-radius-sm: 16px;
    --cm-radius-xs: 12px;
    --cm-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
}
.dark .cm-wrap {
    --cm-bg: #0f172a;
    --cm-surface: #0f172a;
    --cm-raised: #1e293b;
    --cm-ink: #f8fafc;
    --cm-ink-secondary: #cbd5e1;
    --cm-muted: #94a3b8;
    --cm-line: rgba(148,163,184,0.2);
    --cm-line-strong: rgba(148,163,184,0.35);
    --cm-shadow-sm: 0 1px 3px rgba(2,6,23,0.25);
    --cm-shadow: 0 4px 12px -2px rgba(2,6,23,0.25);
    --cm-shadow-md: 0 8px 24px -6px rgba(2,6,23,0.35);
    --cm-shadow-lg: 0 24px 48px -12px rgba(2,6,23,0.5);
}
html.dark-mode .cm-wrap {
    --cm-bg: #0f172a;
    --cm-surface: #0f172a;
    --cm-raised: #1e293b;
    --cm-ink: #f8fafc;
    --cm-ink-secondary: #cbd5e1;
    --cm-muted: #94a3b8;
    --cm-line: rgba(148,163,184,0.2);
    --cm-line-strong: rgba(148,163,184,0.35);
    --cm-shadow-sm: 0 1px 3px rgba(2,6,23,0.25);
    --cm-shadow: 0 4px 12px -2px rgba(2,6,23,0.25);
    --cm-shadow-md: 0 8px 24px -6px rgba(2,6,23,0.35);
    --cm-shadow-lg: 0 24px 48px -12px rgba(2,6,23,0.5);
}

.cm-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 24px 20px 48px;
    background: var(--cm-bg);
    color: var(--cm-ink);
    min-height: 100vh;
    transition: background 0.3s, color 0.3s;
}
html:not(.dark-mode) body:has(.cm-wrap) { background-color: #f8f7f5 !important; }
html:not(.dark-mode) .cm-wrap { background: #f8f7f5; }
html.dark-mode body:has(.cm-wrap) { background: #0f172a !important; }
@media (min-width: 640px) { .cm-wrap { padding: 32px; } }
@media (min-width: 1024px) { .cm-wrap { padding: 40px; } }

/* ===== HERO ===== */
.cm-hero {
    position: relative;
    border-radius: var(--cm-radius-xl);
    padding: 36px 28px;
    margin-bottom: 28px;
    overflow: hidden;
    background: linear-gradient(135deg, #10051f 0%, #24103f 30%, #4c1d95 70%, #6d28d9 100%);
    box-shadow: var(--cm-shadow-lg), inset 0 1px 0 rgba(255,255,255,0.08), 0 0 100px -20px rgba(99,102,241,0.15);
}
@media (min-width: 640px) { .cm-hero { padding: 44px 40px; } }
.cm-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 50%, rgba(129,140,248,0.16) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(199,210,254,0.12) 0%, transparent 40%);
    pointer-events: none;
}
.cm-hero::after {
    content: '';
    position: absolute;
    top: -80px;
    right: -60px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 60%);
    pointer-events: none;
    animation: cmOrbFloat 8s ease-in-out infinite;
}
@keyframes cmOrbFloat {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.1); opacity: 1; }
}
.cm-hero-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
}
.cm-hero-content { position: relative; z-index: 1; }
.cm-hero-badges {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.cm-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 100px;
    background: rgba(99,102,241,0.12);
    border: 1px solid rgba(165,180,252,0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #a5b4fc;
}
.cm-hero-badge svg { width: 14px; height: 14px; }
.cm-hero-badge.secondary {
    background: rgba(255,255,255,0.06);
    border-color: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7);
}
.cm-hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.15;
    letter-spacing: -0.03em;
    margin-bottom: 10px;
}
.cm-hero-subtitle {
    font-family: var(--font-body);
    font-size: 1rem;
    color: rgba(255,255,255,0.55);
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.cm-hero-subtitle span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.cm-hero-subtitle svg { width: 16px; height: 16px; opacity: 0.6; }

/* ===== STATS BAR ===== */
.cm-stats {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
    margin-bottom: 28px;
    background: var(--cm-surface);
    border: 1px solid var(--cm-line);
    border-radius: var(--cm-radius-sm);
    box-shadow: var(--cm-shadow-md);
    overflow: hidden;
    animation: cmFadeUp 0.5s ease 0.05s forwards;
    opacity: 0;
}
@media (min-width: 640px) { .cm-stats { grid-template-columns: repeat(3, 1fr); } }
.cm-stat {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 22px 24px;
    border-bottom: 1px solid var(--cm-line);
    transition: var(--cm-transition);
}
.cm-stat:last-child { border-bottom: none; }
@media (min-width: 640px) {
    .cm-stat { border-bottom: none; border-right: 1px solid var(--cm-line); }
    .cm-stat:last-child { border-right: none; }
}
.cm-stat:hover { background: var(--cm-raised); }
.cm-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cm-stat-icon.blue { background: rgba(59,130,246,0.06); color: #3b82f6; }
.cm-stat-icon.green { background: rgba(16,185,129,0.06); color: #10b981; }
.cm-stat-icon.amber { background: rgba(245,158,11,0.06); color: #f59e0b; }
.dark .cm-stat-icon.blue { background: rgba(59,130,246,0.05); color: #60a5fa; }
.dark .cm-stat-icon.green { background: rgba(16,185,129,0.05); color: #34d399; }
.dark .cm-stat-icon.amber { background: rgba(245,158,11,0.05); color: #fbbf24; }
.cm-stat-icon svg { width: 24px; height: 24px; }
.cm-stat-label {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--cm-muted);
    margin-bottom: 4px;
}
.cm-stat-value {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--cm-ink);
    line-height: 1.1;
}

/* ===== LAYOUT ===== */
.cm-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}
@media (min-width: 1024px) { .cm-layout { grid-template-columns: 1.35fr 1fr; align-items: start; } }

/* ===== MAP CARD ===== */
.cm-map-card {
    position: relative;
    background: var(--cm-surface);
    border: 1px solid var(--cm-line);
    border-radius: var(--cm-radius-sm);
    box-shadow: var(--cm-shadow-lg);
    overflow: hidden;
    height: calc(100vh - 20rem);
    min-height: 520px;
    animation: cmFadeUp 0.5s ease 0.1s forwards;
    opacity: 0;
}
.cm-map-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #24103f, #4c1d95, #6d28d9);
    z-index: 10;
    border-radius: 4px 4px 0 0;
}
#map {
    width: 100%;
    height: 100%;
    background: #1f2937;
}

/* Map overlay controls */
.cm-map-overlay {
    position: absolute;
    left: 16px;
    bottom: 16px;
    z-index: 500;
    display: flex;
    gap: 6px;
    padding: 6px;
    border-radius: 14px;
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.08);
}
.cm-map-overlay-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 10px;
    border: none;
    background: transparent;
    color: rgba(255,255,255,0.5);
    font-family: var(--font-body);
    font-size: 0.75rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--cm-transition);
}
.cm-map-overlay-btn:hover { color: rgba(255,255,255,0.8); }
.cm-map-overlay-btn.active {
    background: linear-gradient(135deg, #4c1d95, #6d28d9);
    color: #ffffff;
    box-shadow: 0 4px 12px -2px rgba(99,102,241,0.3);
}
.cm-map-overlay-btn svg { width: 15px; height: 15px; }

/* ===== SIDEBAR ===== */
.cm-sidebar {
    background: var(--cm-surface);
    border: 1px solid var(--cm-line);
    border-radius: var(--cm-radius-sm);
    box-shadow: var(--cm-shadow-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 20rem);
    min-height: 520px;
    animation: cmFadeUp 0.5s ease 0.15s forwards;
    opacity: 0;
    position: relative;
}
.cm-sidebar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #4c1d95, #6d28d9);
    border-radius: 4px 4px 0 0;
}
.cm-sidebar-header {
    padding: 24px;
    border-bottom: 1px solid var(--cm-line);
    background: linear-gradient(180deg, var(--cm-raised), var(--cm-surface));
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.cm-sidebar-header h2 {
    font-family: var(--font-display);
    font-size: 1.125rem;
    font-weight: 800;
    color: var(--cm-ink);
    letter-spacing: -0.01em;
}
.cm-sidebar-header p {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--cm-muted);
    margin-top: 4px;
}
.cm-sidebar-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: var(--cm-raised);
}

/* Back button */
.cm-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 14px;
    padding: 10px 20px;
    border-radius: 12px;
    background: var(--cm-raised);
    border: 1px solid var(--cm-line-strong);
    color: var(--cm-muted);
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--cm-transition);
}
.cm-back-btn:hover {
    background: var(--cm-surface);
    border-color: var(--cm-gold);
    color: var(--cm-gold);
    transform: translateY(-1px);
    box-shadow: var(--cm-shadow-sm);
}
.cm-back-btn svg { width: 16px; height: 16px; }

/* ===== PROJECT CARDS ===== */
.cm-project-card {
    background: var(--cm-surface);
    border: 1px solid var(--cm-line);
    border-radius: var(--cm-radius-xs);
    overflow: hidden;
    cursor: pointer;
    transition: var(--cm-transition);
    margin-bottom: 16px;
    position: relative;
}
.cm-project-card:last-child { margin-bottom: 0; }
.cm-project-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 4px; height: 100%;
    background: linear-gradient(180deg, #6d28d9, #4c1d95);
    opacity: 0;
    transition: opacity 0.3s;
}
.cm-project-card:hover {
    border-color: var(--cm-line-strong);
    box-shadow: var(--cm-shadow-md);
    transform: translateX(3px);
}
.cm-project-card:hover::before { opacity: 1; }
.cm-project-card.selected {
    border-color: var(--cm-gold);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.1), var(--cm-shadow-md);
}
.cm-project-card.selected::before { opacity: 1; }
.cm-project-image-wrap {
    position: relative;
    height: 170px;
    overflow: hidden;
}
.cm-project-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}
.cm-project-card:hover .cm-project-image { transform: scale(1.05); }
.cm-project-image-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 60px;
    background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);
    pointer-events: none;
}
.cm-project-noimage {
    width: 100%;
    height: 170px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--cm-raised), var(--cm-surface));
    color: var(--cm-muted);
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
}
.cm-project-body { padding: 20px; }
.cm-project-name {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 800;
    color: var(--cm-ink);
    line-height: 1.35;
    margin-bottom: 10px;
}
.cm-project-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}

/* Status badges */
.cm-status {
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
}
.cm-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.cm-status-proposed { background: rgba(37,99,235,0.10); color: #2563eb; border: 1px solid rgba(37,99,235,0.20); }
.cm-status-proposed .cm-status-dot { background: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.12); }
.cm-status-bidding { background: rgba(245,158,11,0.10); color: #f59e0b; border: 1px solid rgba(245,158,11,0.20); }
.cm-status-bidding .cm-status-dot { background: #f59e0b; }
.cm-status-ongoing { background: rgba(6,182,212,0.10); color: #06b6d4; border: 1px solid rgba(6,182,212,0.20); }
.cm-status-ongoing .cm-status-dot { background: #06b6d4; box-shadow: 0 0 0 2px rgba(6,182,212,0.12); }
.cm-status-award { background: rgba(139,92,246,0.10); color: #8b5cf6; border: 1px solid rgba(139,92,246,0.20); }
.cm-status-award .cm-status-dot { background: #8b5cf6; }
.cm-status-implementation { background: rgba(15,118,110,0.10); color: #0f766e; border: 1px solid rgba(15,118,110,0.20); }
.cm-status-implementation .cm-status-dot { background: #0f766e; }
.cm-status-completed { background: rgba(22,163,74,0.10); color: #16a34a; border: 1px solid rgba(22,163,74,0.20); }
.cm-status-completed .cm-status-dot { background: #16a34a; box-shadow: 0 0 0 2px rgba(22,163,74,0.12); }
.cm-status-hold { background: rgba(220,38,38,0.10); color: #dc2626; border: 1px solid rgba(220,38,38,0.20); }
.cm-status-hold .cm-status-dot { background: #dc2626; }
.cm-status-cancelled { background: rgba(100,116,139,0.10); color: #64748b; border: 1px solid rgba(100,116,139,0.20); }
.cm-status-cancelled .cm-status-dot { background: #64748b; }
.dark .cm-status-proposed { background: rgba(37,99,235,0.12); color: #60a5fa; }
.dark .cm-status-bidding { background: rgba(245,158,11,0.12); color: #fbbf24; }
.dark .cm-status-ongoing { background: rgba(6,182,212,0.12); color: #67e8f9; }
.dark .cm-status-award { background: rgba(139,92,246,0.12); color: #a78bfa; }
.dark .cm-status-implementation { background: rgba(15,118,110,0.12); color: #5eead4; }
.dark .cm-status-completed { background: rgba(22,163,74,0.12); color: #4ade80; }
.dark .cm-status-hold { background: rgba(220,38,38,0.12); color: #f87171; }
.dark .cm-status-cancelled { background: rgba(100,116,139,0.12); color: #cbd5e1; }

.cm-project-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
}
.cm-project-stat {
    padding: 12px 14px;
    border-radius: 10px;
    background: var(--cm-raised);
    border: 1px solid var(--cm-line);
    transition: var(--cm-transition);
}
.cm-project-card:hover .cm-project-stat {
    background: var(--cm-surface);
    border-color: var(--cm-line-strong);
}
.cm-project-stat-label {
    font-family: var(--font-body);
    font-size: 0.625rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--cm-muted);
    margin-bottom: 4px;
}
.cm-project-stat-value {
    font-family: var(--font-display);
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--cm-ink);
}
.cm-project-desc {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--cm-muted);
    line-height: 1.55;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
}

/* ===== SELECTED PROJECT DETAIL ===== */
.cm-selected {
    background: var(--cm-surface);
    border: 1px solid var(--cm-line);
    border-radius: var(--cm-radius-xs);
    overflow: hidden;
    box-shadow: var(--cm-shadow-md);
}
.cm-selected-header {
    padding: 28px 24px;
    border-bottom: 1px solid var(--cm-line);
    background: linear-gradient(180deg, var(--cm-raised), var(--cm-surface));
}
.cm-selected-label {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: var(--cm-indigo);
    margin-bottom: 10px;
}
.cm-selected-title {
    font-family: var(--font-display);
    font-size: clamp(1.125rem, 2.5vw, 1.35rem);
    font-weight: 800;
    color: var(--cm-ink);
    line-height: 1.25;
    letter-spacing: -0.01em;
}
.cm-selected-status {
    display: inline-flex;
    margin-top: 12px;
    padding: 6px 14px;
    border-radius: 100px;
    background: rgba(59,130,246,0.06);
    color: #2563eb;
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.dark .cm-selected-status { background: rgba(59,130,246,0.05); color: #60a5fa; }
.cm-selected-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}
.cm-selected-body { padding: 24px; }

/* Lifecycle stepper */
.dept-stepper-lifecycle {
    --de-surface: #ffffff;
    --de-line: rgba(0, 0, 0, 0.06);
    --de-line-strong: rgba(0, 0, 0, 0.12);
    --de-ink: #1e1b4b;
    --de-ink-secondary: #374151;
    --de-muted: #9ca3af;
    --de-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    background: var(--de-surface);
    border: 1px solid var(--de-line);
    border-radius: 12px;
    box-shadow: var(--de-shadow-sm);
    padding: 20px 20px 22px;
    margin-bottom: 20px;
}
.dept-stepper-header { margin-bottom: 20px; }
.dept-stepper-title {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--de-ink);
    margin: 0 0 4px;
}
.dept-stepper-subtitle {
    font-size: 0.75rem;
    color: var(--de-muted);
    margin: 0;
    font-weight: 500;
}
.dept-stepper-track {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 4px;
}
.dept-step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex: 1 1 0;
    position: relative;
    z-index: 2;
    min-width: 0;
}
.dept-step-node {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6875rem;
    font-weight: 700;
    flex-shrink: 0;
}
.dept-step-item.completed .dept-step-node { background: #10b981; color: #fff; }
.dept-step-item.current .dept-step-node { background: #3b82f6; color: #fff; }
.dept-step-item.pending .dept-step-node {
    background: transparent;
    color: var(--de-muted);
    border: 2px solid var(--de-line-strong);
}
.dept-step-name {
    font-size: 0.625rem;
    font-weight: 600;
    color: var(--de-ink-secondary);
    text-align: center;
    line-height: 1.2;
    white-space: normal;
    max-width: 72px;
}
.dept-step-item.completed .dept-step-name { color: #059669; font-weight: 700; }
.dept-step-item.current .dept-step-name { color: #2563eb; font-weight: 700; }
.dept-step-item.pending .dept-step-name { color: var(--de-muted); font-weight: 500; }
.dept-step-connector {
    flex: 1;
    height: 2px;
    background: var(--de-line-strong);
    margin-top: 14px;
    min-width: 12px;
    position: relative;
    z-index: 1;
}
.dept-step-connector.completed { background: #10b981; }
html.dark-mode .dept-stepper-lifecycle,
.dark .dept-stepper-lifecycle {
    --de-surface: #1a1929;
    --de-line: rgba(255, 255, 255, 0.06);
    --de-line-strong: rgba(255, 255, 255, 0.12);
    --de-ink: #f8fafc;
    --de-ink-secondary: #cbd5e1;
    --de-muted: #94a3b8;
    --de-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
    background: var(--de-surface);
    border-color: var(--de-line);
    box-shadow: var(--de-shadow-sm);
}
html.dark-mode .dept-stepper-title,
.dark .dept-stepper-title { color: #f8f7f5; }
html.dark-mode .dept-stepper-subtitle,
.dark .dept-stepper-subtitle { color: #94a3b8; }
html.dark-mode .dept-step-item.completed .dept-step-node,
.dark .dept-step-item.completed .dept-step-node { background: #34d399; color: #064e3b; }
html.dark-mode .dept-step-item.current .dept-step-node,
.dark .dept-step-item.current .dept-step-node { background: #60a5fa; color: #0f172a; }
html.dark-mode .dept-step-item.pending .dept-step-node,
.dark .dept-step-item.pending .dept-step-node {
    background: #1a1929;
    border-color: rgba(255, 255, 255, 0.12);
    color: #94a3b8;
}
html.dark-mode .dept-step-item.completed .dept-step-name,
.dark .dept-step-item.completed .dept-step-name { color: #34d399; }
html.dark-mode .dept-step-item.current .dept-step-name,
.dark .dept-step-item.current .dept-step-name { color: #60a5fa; }
html.dark-mode .dept-step-connector.completed,
.dark .dept-step-connector.completed { background: #34d399; }
@media (max-width: 640px) {
    .dept-stepper-track { overflow-x: auto; padding-bottom: 8px; }
    .dept-step-item { min-width: 70px; }
    .dept-step-name { max-width: 70px; }
}

/* Detail grid */
.cm-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 20px;
}
.cm-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--cm-raised), var(--cm-surface));
    border: 1px solid var(--cm-line);
    transition: var(--cm-transition);
}
.cm-detail-item:hover {
    border-color: var(--cm-line-strong);
    box-shadow: var(--cm-shadow-sm);
    transform: translateY(-1px);
}
.cm-detail-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cm-detail-icon svg { width: 18px; height: 18px; }
.cm-detail-icon.purple { background: rgba(139,92,246,0.06); color: #7c3aed; }
.cm-detail-icon.green { background: rgba(16,185,129,0.06); color: #059669; }
.cm-detail-icon.rose { background: rgba(244,63,94,0.06); color: #e11d48; }
.cm-detail-icon.blue { background: rgba(59,130,246,0.06); color: #2563eb; }
.dark .cm-detail-icon.purple { background: rgba(139,92,246,0.05); color: #a78bfa; }
.dark .cm-detail-icon.green { background: rgba(16,185,129,0.05); color: #34d399; }
.dark .cm-detail-icon.rose { background: rgba(244,63,94,0.05); color: #fb7185; }
.dark .cm-detail-icon.blue { background: rgba(59,130,246,0.05); color: #60a5fa; }
.cm-detail-label {
    font-family: var(--font-body);
    font-size: 0.625rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--cm-muted);
    margin-bottom: 3px;
}
.cm-detail-value {
    font-family: var(--font-display);
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--cm-ink);
}

/* Progress bars */
.cm-progress-wrap {
    padding: 16px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--cm-raised), var(--cm-surface));
    border: 1px solid var(--cm-line);
    margin-bottom: 16px;
}
.cm-progress-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.cm-progress-label {
    font-family: var(--font-body);
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--cm-ink-secondary);
}
.cm-progress-value {
    font-family: var(--font-display);
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--cm-ink);
}
.cm-progress-track {
    height: 10px;
    background: var(--cm-line);
    border-radius: 100px;
    overflow: hidden;
    position: relative;
}
.cm-progress-fill {
    height: 100%;
    border-radius: 100px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}
.cm-progress-fill::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
    animation: cmShimmer 2s ease-in-out infinite;
}
@keyframes cmShimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* View all button */
.cm-viewall {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--cm-raised), var(--cm-surface));
    border: 1px solid var(--cm-line-strong);
    color: var(--cm-ink-secondary);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--cm-transition);
}
.cm-viewall:hover {
    background: var(--cm-surface);
    border-color: var(--cm-gold);
    color: var(--cm-gold);
    transform: translateY(-1px);
    box-shadow: var(--cm-shadow-sm);
}
.cm-viewall svg { width: 18px; height: 18px; }

/* Empty state */
.cm-empty {
    text-align: center;
    padding: 56px 24px;
}
.cm-empty-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    border-radius: var(--cm-radius-xs);
    background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(245,158,11,0.04));
    color: var(--cm-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px -2px rgba(245,158,11,0.1);
}
.cm-empty-icon svg { width: 32px; height: 32px; }
.cm-empty h4 {
    font-family: var(--font-display);
    font-size: 1.0625rem;
    font-weight: 800;
    color: var(--cm-ink);
    margin-bottom: 6px;
}
.cm-empty p {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--cm-muted);
}

/* Leaflet dark mode */
.dark .leaflet-container { background: #0f172a; }
.dark .leaflet-popup-content-wrapper,
.dark .leaflet-popup-tip {
    background: #1e293b;
    color: #f8fafc;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
}
.dark .leaflet-container a.leaflet-popup-close-button { color: #94a3b8; }

/* ===== ANIMATIONS ===== */
@keyframes cmFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.cm-animate {
    animation: cmFadeUp 0.5s ease forwards;
    opacity: 0;
}
.cm-animate:nth-child(1) { animation-delay: 0.04s; }
.cm-animate:nth-child(2) { animation-delay: 0.08s; }
.cm-animate:nth-child(3) { animation-delay: 0.12s; }
.cm-animate:nth-child(4) { animation-delay: 0.16s; }
.cm-animate:nth-child(5) { animation-delay: 0.20s; }

@media (prefers-reduced-motion: reduce) {
    .cm-animate { animation: none; opacity: 1; }
    .cm-progress-fill::after { animation: none; }
    .cm-hero::after { animation: none; }
}

@media (max-width: 767px) {
    .cm-stats { grid-template-columns: 1fr; }
    .cm-stat { border-right: none !important; border-bottom: 1px solid var(--cm-line); }
    .cm-stat:last-child { border-bottom: none; }
    .cm-map-card, .cm-sidebar { min-height: 460px; }
    .cm-sidebar { max-height: none; }
}
</style>

<div class="cm-wrap">
    <!-- HERO -->
    <div class="cm-hero cm-animate">
        <div class="cm-hero-grid"></div>
        <div class="cm-hero-content">
            <div class="cm-hero-badges">
                <span class="cm-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Cabuyao City, Laguna
                </span>
                <span class="cm-hero-badge secondary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Interactive Map View
                </span>
            </div>
            <h1 class="cm-hero-title">City Map</h1>
            <div class="cm-hero-subtitle">
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Tap a barangay to explore projects
                </span>
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Real-time budget tracking
                </span>
            </div>
        </div>
    </div>

    <!-- STATS BAR -->
    <div class="cm-stats">
        <div class="cm-stat">
            <div class="cm-stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6.75M9 12h6.75M9 17.25h6.75"/></svg>
            </div>
            <div>
                <div class="cm-stat-label">Total Projects</div>
                <div class="cm-stat-value" id="statTotalProjects">—</div>
            </div>
        </div>
        <div class="cm-stat">
            <div class="cm-stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
            </div>
            <div>
                <div class="cm-stat-label">Avg Progress</div>
                <div class="cm-stat-value" id="statAvgProgress">—</div>
            </div>
        </div>
        <div class="cm-stat">
            <div class="cm-stat-icon amber">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="cm-stat-label">Total Budget</div>
                <div class="cm-stat-value" id="statTotalBudget">—</div>
            </div>
        </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="cm-layout">
        <!-- MAP -->
        <div class="cm-map-card">
            <div id="map">
                @include('components.map-status-legend')
            </div>
            <div class="cm-map-overlay">
                <button type="button" id="btnLightTiles" class="cm-map-overlay-btn active">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    Light
                </button>
                <button type="button" id="btnDarkTiles" class="cm-map-overlay-btn">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.598.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    Dark
                </button>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="cm-sidebar">
            <div class="cm-sidebar-header">
                <h2>City Projects</h2>
                <p>Cabuyao City Projects</p>
                <div id="departmentSidebarAction"></div>
            </div>
            <div class="cm-sidebar-body" id="departmentProjectList"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectList = document.getElementById('departmentProjectList');
        const departmentSidebarAction = document.getElementById('departmentSidebarAction');
        let selectedProjectIndex = null;
        let map = null;
        let boundedArea = null;
        let projectFeatures = [];
        let barangayLayer = null;
        let selectedBarangayLayer = null;
        let selectedBarangayName = null;
        const markersByBarangay = {};
        let allMarkers = null;
        let lightTiles = null;
        let darkTiles = null;
        let currentTiles = null;

        function formatCurrency(value) {
            return `₱${Number(value || 0).toLocaleString()}`;
        }

        function calculateProgress(project) {
            if (!project.properties.start_date || !project.properties.target_end_date) {
                return 0;
            }
            const startDate = new Date(project.properties.start_date);
            const endDate = new Date(project.properties.target_end_date);
            const today = new Date();
            const totalDays = (endDate - startDate) / (1000 * 60 * 60 * 24);
            const daysElapsed = (today - startDate) / (1000 * 60 * 60 * 24);
            return totalDays > 0 ? Math.min(100, Math.max(0, (daysElapsed / totalDays) * 100)) : 0;
        }

        function calculateReportedProgress(project) {
            const reportedProgress = project.properties.progress_percentage;
            return reportedProgress !== null && reportedProgress !== undefined && reportedProgress !== ''
                ? Math.min(100, Math.max(0, Number(reportedProgress)))
                : null;
        }

        function updateMapStats() {
            const total = projectFeatures.length;
            const progress = total ? projectFeatures.reduce((sum, project) => sum + calculateProgress(project), 0) / total : 0;
            const budget = projectFeatures.reduce((sum, project) => sum + Number(project.properties.budget || 0), 0);
            document.getElementById('statTotalProjects').textContent = total;
            document.getElementById('statAvgProgress').textContent = `${progress.toFixed(1)}%`;
            document.getElementById('statTotalBudget').textContent = `₱${budget.toLocaleString()}`;
        }

        function barangayColor(name) {
            let hash = 0;
            for (let i = 0; i < name.length; i++) {
                hash = name.charCodeAt(i) + ((hash << 5) - hash);
            }
            const hue = Math.abs(hash) % 360;
            return `hsl(${hue}, 65%, 55%)`;
        }

        function getStatusClass(status) {
            const map = {
                'Proposed': 'cm-status-proposed',
                'For bidding': 'cm-status-bidding',
                'Bidding ongoing': 'cm-status-ongoing',
                'Award of contract': 'cm-status-award', 'Implementation': 'cm-status-implementation',
                'Completed': 'cm-status-completed', 'On Hold': 'cm-status-hold', 'Cancelled': 'cm-status-cancelled'
            };
            return map[status] || 'cm-status-proposed';
        }

        function renderLifecycleStepper(status) {
            const steps = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed'];
            const stageByStatus = { Proposed: 0, 'For bidding': 1, 'Bidding ongoing': 2, 'Award of contract': 3, Implementation: 4, Completed: 5, Planning: 0, Procurement: 1, 'Bidding - Success': 3, 'On Going': 4 };
            const activeStep = stageByStatus[status];

            return `
                <div class="dept-stepper-lifecycle">
                    <div class="dept-stepper-header">
                        <h3 class="dept-stepper-title">Project Lifecycle</h3>
                        <p class="dept-stepper-subtitle">Current stage: ${status || 'Unknown'}</p>
                    </div>
                    <div class="dept-stepper-track">
                        ${steps.map((step, index) => {
                            const isComplete = activeStep !== null && activeStep !== undefined && index < activeStep;
                            const isCurrent = activeStep !== null && activeStep !== undefined && index === activeStep;
                            const state = isComplete ? 'completed' : (isCurrent ? 'current' : 'pending');

                            return `
                                <div class="dept-step-item ${state}">
                                    <div class="dept-step-node">
                                        ${isComplete ? '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>' : index + 1}
                                    </div>
                                    <span class="dept-step-name">${step}</span>
                                </div>
                                ${index < steps.length - 1 ? `<div class="dept-step-connector ${isComplete ? 'completed' : ''}"></div>` : ''}
                            `;
                        }).join('')}
                    </div>
                </div>
            `;
        }

        function renderProjectCard(project, index, isSingle = false) {
            const props = project.properties;
            const timelineProgress = calculateProgress(project);
            const reportedProgress = calculateReportedProgress(project);
            const progress = reportedProgress ?? timelineProgress;
            const imageHtml = props.image
                ? `<div class="cm-project-image-wrap"><img src="${props.image}" alt="${props.name}" class="cm-project-image"><div class="cm-project-image-overlay"></div></div>`
                : '<div class="cm-project-noimage">No image</div>';

            if (isSingle) {
                const expenditure = Number(props.actual_budget || 0);
                const budget = Number(props.budget || 0);
                const expenditureProgress = budget > 0 ? Math.min(100, (expenditure / budget) * 100) : 0;
                return `<div class="cm-selected" data-index="${index}"><div class="cm-selected-header"><div class="cm-selected-label">Selected project</div><div class="cm-selected-title">${props.name}</div><span class="cm-selected-status">${props.status || 'Unknown'}</span></div>${props.image ? `<img src="${props.image}" alt="${props.name}" class="cm-selected-image">` : '<div class="cm-project-noimage" style="height:220px">No image</div>'}<div class="cm-selected-body">${renderLifecycleStepper(props.status)}<div class="cm-detail-grid"><div class="cm-detail-item"><div class="cm-detail-icon purple"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></div><div><div class="cm-detail-label">Barangay</div><div class="cm-detail-value">${props.barangay || 'Not specified'}</div></div></div><div class="cm-detail-item"><div class="cm-detail-icon green"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="cm-detail-label">Budget</div><div class="cm-detail-value">${formatCurrency(budget)}</div></div></div><div class="cm-detail-item"><div class="cm-detail-icon rose"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="cm-detail-label">Expenditure</div><div class="cm-detail-value">${formatCurrency(expenditure)}</div></div></div><div class="cm-detail-item"><div class="cm-detail-icon blue"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div><div><div class="cm-detail-label">Progress</div><div class="cm-detail-value">${progress.toFixed(1)}%</div></div></div></div><div class="cm-progress-wrap"><div class="cm-progress-head"><span class="cm-progress-label">Expenditure progress</span><span class="cm-progress-value">${expenditureProgress.toFixed(1)}%</span></div><div class="cm-progress-track"><div class="cm-progress-fill" style="width:${expenditureProgress}%;background:linear-gradient(90deg,#10b981,#059669)"></div></div></div><div class="cm-progress-wrap"><div class="cm-progress-head"><span class="cm-progress-label">Timeline progress</span><span class="cm-progress-value">${timelineProgress.toFixed(1)}%</span></div><div class="cm-progress-track"><div class="cm-progress-fill" style="width:${timelineProgress}%;background:linear-gradient(90deg,#6366f1,#f59e0b,#d97706)"></div></div></div><div style="margin-top:12px;padding:12px 14px;border:1px solid var(--cm-line);border-radius:12px;background:var(--cm-raised);"><p style="font-family:var(--font-body);font-size:0.875rem;color:var(--cm-ink-secondary);line-height:1.6;margin:0;white-space:pre-wrap;word-break:break-word;">${props.description || 'No description available.'}</p></div><button type="button" class="cm-viewall show-all-projects-btn" style="margin-top:16px;"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>View all projects</button></div></div>`;
            }

            return `<div class="cm-project-card ${selectedProjectIndex === index ? 'selected' : ''}" data-index="${index}">${imageHtml}<div class="cm-project-body"><div class="cm-project-name">${props.name}</div><div class="cm-project-meta"><span class="cm-status ${getStatusClass(props.status)}"><span class="cm-status-dot"></span>${props.status || 'Unknown'}</span><span class="cm-status" style="background:var(--cm-raised);color:var(--cm-muted);border:1px solid var(--cm-line);">${props.barangay || 'Barangay not specified'}</span></div><div class="cm-project-stats"><div class="cm-project-stat"><div class="cm-project-stat-label">Budget</div><div class="cm-project-stat-value">${formatCurrency(props.budget)}</div></div><div class="cm-project-stat"><div class="cm-project-stat-label">Progress</div><div class="cm-project-stat-value">${progress.toFixed(1)}%</div></div></div><p class="cm-project-desc">${props.description || 'No description available.'}</p></div></div>`;
        }

        function updateSidebarAction() {
            if (!departmentSidebarAction) return;
            if (selectedBarangayName) {
                departmentSidebarAction.innerHTML = `<button type="button" id="backToAllBarangays" class="cm-back-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>Back to all barangays</button>`;
                document.getElementById('backToAllBarangays').addEventListener('click', resetToAllBarangays);
            } else {
                departmentSidebarAction.innerHTML = '';
            }
        }

        function decorateProgressDetails(project) {
            const selected = projectList.querySelector('.cm-selected');
            if (!selected) return;
            selected.querySelectorAll('.cm-detail-label').forEach(label => {
                if (label.textContent.trim() === 'Progress') label.textContent = 'Reported Progress';
            });
        }

        function clearSelection() {
            document.querySelectorAll('.cm-project-card').forEach(card => card.classList.remove('selected'));
        }

        function highlightProject(index) {
            selectedProjectIndex = index;
            clearSelection();
            const card = document.querySelector(`.cm-project-card[data-index="${index}"]`);
            if (card) {
                card.classList.add('selected');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function renderProjectList(projects) {
            updateSidebarAction();
            if (!projects.length) {
                projectList.innerHTML = `<div class="cm-empty"><div class="cm-empty-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div><h4>No projects found</h4><p>No public projects recorded in ${selectedBarangayName || 'this barangay'} yet.</p></div>`;
                return;
            }
            projectList.innerHTML = projects.map(project => renderProjectCard(project, project.originalIndex, projects.length === 1)).join('');
            if (projects.length === 1) decorateProgressDetails(projects[0]);
            document.querySelectorAll('.show-all-projects-btn').forEach(btn => btn.addEventListener('click', resetToAllBarangays));
            document.querySelectorAll('.cm-project-card').forEach(card => {
                card.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'), 10);
                    selectProject(projectFeatures[index], index);
                });
            });
        }

        function selectProject(project, index) {
            highlightProject(index);
            renderProjectList([project]);
            if (map && project && project.geometry && project.geometry.coordinates) {
                const coords = project.geometry.coordinates;
                map.flyTo([coords[1], coords[0]], 15, { duration: 0.7, easeLinearity: 0.35 });
            }
        }

        function resetToAllBarangays() {
            if (selectedBarangayLayer) {
                barangayLayer.resetStyle(selectedBarangayLayer);
                selectedBarangayLayer = null;
            }
            selectedBarangayName = null;
            if (allMarkers) map.addLayer(allMarkers);
            selectedProjectIndex = null;
            renderProjectList(projectFeatures);
            if (map && boundedArea) map.fitBounds(boundedArea, { padding: [24, 24] });
        }

        function selectBarangayOnMap(layer, name) {
            if (selectedBarangayLayer) barangayLayer.resetStyle(selectedBarangayLayer);
            selectedBarangayLayer = layer;
            layer.setStyle({ fillOpacity: 0.75, weight: 3, color: '#162347' });
            selectedBarangayName = name;
            selectedProjectIndex = null;
            map.fitBounds(layer.getBounds(), { padding: [40, 40] });
            if (allMarkers) map.removeLayer(allMarkers);
            (markersByBarangay[name] || []).forEach(marker => marker.addTo(map));
            const filtered = projectFeatures.filter(p => p.properties.barangay === name);
            renderProjectList(filtered);
        }

        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const cabuyaoBounds = L.geoJSON(geojson).getBounds();
                boundedArea = cabuyaoBounds.pad(0.02);
                map = L.map('map', { maxBounds: boundedArea, maxBoundsViscosity: 1.0 });

                lightTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors', maxZoom: 19, minZoom: 11
                });
                darkTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap &copy; CARTO', maxZoom: 19, minZoom: 11
                });
                currentTiles = lightTiles.addTo(map);

                document.getElementById('btnLightTiles').addEventListener('click', function() {
                    if (currentTiles !== lightTiles) { map.removeLayer(currentTiles); currentTiles = lightTiles.addTo(map); }
                    this.classList.add('active'); document.getElementById('btnDarkTiles').classList.remove('active');
                });
                document.getElementById('btnDarkTiles').addEventListener('click', function() {
                    if (currentTiles !== darkTiles) { map.removeLayer(currentTiles); currentTiles = darkTiles.addTo(map); }
                    this.classList.add('active'); document.getElementById('btnLightTiles').classList.remove('active');
                });

                barangayLayer = L.geoJSON(geojson, {
                    style: feature => ({
                        fillColor: barangayColor(feature.properties.name),
                        fillOpacity: 0.35, color: '#ffffff', weight: 1.5,
                    }),
                    onEachFeature: (feature, layer) => {
                        const name = feature.properties.name;
                        layer.bindTooltip(name, { sticky: true, className: 'barangay-tooltip' });
                        layer.on({
                            mouseover: e => { if (layer !== selectedBarangayLayer) e.target.setStyle({ fillOpacity: 0.6, weight: 2.5 }); },
                            mouseout: e => { if (layer !== selectedBarangayLayer) barangayLayer.resetStyle(e.target); },
                            click: () => selectBarangayOnMap(layer, name),
                        });
                    },
                }).addTo(map);

                function getMarkerColor(status) {
                    const colors = {
                        'Proposed': '#2563eb',
                        'For bidding': '#f59e0b',
                        'Bidding ongoing': '#06b6d4',
                        'Award of contract': '#8b5cf6',
                        'Implementation': '#0f766e',
                        'Completed': '#16a34a',
                        'On Hold': '#dc2626',
                        'Cancelled': '#64748b',
                    };
                    const normalizedStatus = String(status ?? '').trim();
                    return colors[normalizedStatus] || '#64748b';
                }

                allMarkers = L.featureGroup();
                projectFeatures = [];
                window.projectFeatures = projectFeatures;

                fetch('{{ route('api.projects.geojson') }}')
                    .then(response => response.json())
                    .then(function(projectData) {
                        if (!projectData || !projectData.features) throw new Error('Invalid project data');
                        projectData.features.forEach(function(project, index) {
                            const coords = project.geometry && project.geometry.coordinates;
                            if (!coords || coords.length < 2) return;
                            const marker = L.circleMarker([coords[1], coords[0]], {
                                radius: 12, fillColor: getMarkerColor(project.properties.status),
                                color: '#ffffff', weight: 2, opacity: 1, fillOpacity: 0.9
                            });
                            marker.bindPopup(`<div style="font-family:var(--font-body);font-size:0.875rem;color:var(--cm-ink)"><h4 style="margin:0 0 6px;font-family:var(--font-display);font-weight:800;">${project.properties.name}</h4><span style="display:inline-flex;align-items:center;gap:6px;font-size:0.75rem;font-weight:700;color:var(--cm-muted);"><span style="width:8px;height:8px;border-radius:50%;background:${getMarkerColor(project.properties.status)};display:inline-block;"></span>${project.properties.status || 'Unknown'}</span></div>`);
                            marker.on('click', function(e) {
                                L.DomEvent.stopPropagation(e);
                                map.flyTo([coords[1], coords[0]], 16, { duration: 0.7, easeLinearity: 0.35 });
                                selectProject(project, index);
                            });
                            allMarkers.addLayer(marker);
                            projectFeatures.push(Object.assign({ originalIndex: index }, project));
                            const barangayName = project.properties.barangay;
                            if (barangayName) {
                                if (!markersByBarangay[barangayName]) markersByBarangay[barangayName] = [];
                                markersByBarangay[barangayName].push(marker);
                            }
                        });
                        map.on('click', () => { if (selectedProjectIndex !== null) { selectedProjectIndex = null; clearSelection(); } });
                        allMarkers.addTo(map);
                        updateMapStats();
                        renderProjectList(projectFeatures);
                    })
                    .catch(function(error) {
                        console.error(error);
                        projectList.innerHTML = `<div class="cm-empty"><div class="cm-empty-icon" style="background:linear-gradient(135deg,rgba(239,68,68,0.08),rgba(239,68,68,0.04));color:#dc2626;"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg></div><h4>Unable to load projects</h4><p>Please try refreshing the page.</p></div>`;
                    });

                map.fitBounds(boundedArea, { padding: [24, 24] });
                map.setMaxBounds(boundedArea);
                map.setMinZoom(map.getZoom());
                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(function(error) {
                console.error(error);
                projectList.innerHTML = `<div class="cm-empty"><div class="cm-empty-icon" style="background:linear-gradient(135deg,rgba(239,68,68,0.08),rgba(239,68,68,0.04));color:#dc2626;"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg></div><h4>Unable to load map</h4><p>Please try refreshing the page.</p></div>`;
            });
    });
</script>
@endsection
