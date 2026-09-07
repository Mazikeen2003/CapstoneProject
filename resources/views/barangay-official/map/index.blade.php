@extends('layouts.barangay')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<style>
/* ===== BARANGAY MAP - RICH REDESIGN ===== */
.bm-wrap {
    --bm-bg: #f4f4f5;
    --bm-surface: #ffffff;
    --bm-raised: #fafaf9;
    --bm-ink: #0f0d1f;
        --bm-bg: #f8f7f5;
    --bm-muted: #6b7280;
    --bm-line: rgba(0,0,0,0.06);
    --bm-line-strong: rgba(0,0,0,0.12);
    --bm-shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
    --bm-shadow: 0 4px 12px -2px rgba(0,0,0,0.06);
    --bm-shadow-md: 0 8px 24px -6px rgba(0,0,0,0.08);
    --bm-shadow-lg: 0 24px 48px -12px rgba(0,0,0,0.14);
    --bm-gold: #f59e0b;
    --bm-indigo: #4338ca;
    --bm-radius-xl: 28px;
    --bm-radius: 20px;
    --bm-radius-sm: 16px;
    --bm-radius-xs: 12px;
    --bm-transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    --font-display: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', system-ui, sans-serif;
}
.dark .bm-wrap {
    --bm-bg: #0f172a;
    --bm-surface: #0f172a;
    --bm-raised: #1e293b;
    html:not(.dark-mode) body:has(.bm-wrap) {
        background-color: #f8f7f5 !important;
    }
    --bm-ink: #f8fafc;
    --bm-ink-secondary: #cbd5e1;
    --bm-muted: #94a3b8;
    --bm-line: rgba(148,163,184,0.2);
    --bm-line-strong: rgba(148,163,184,0.35);
    --bm-shadow-sm: 0 1px 3px rgba(2,6,23,0.25);
    --bm-shadow: 0 4px 12px -2px rgba(2,6,23,0.25);
    --bm-shadow-md: 0 8px 24px -6px rgba(2,6,23,0.35);
    --bm-shadow-lg: 0 24px 48px -12px rgba(2,6,23,0.5);
}
html.dark-mode .bm-wrap {
    --bm-bg: #0f172a;
    --bm-surface: #0f172a;
    --bm-raised: #1e293b;
    --bm-ink: #f8fafc;
    --bm-ink-secondary: #cbd5e1;
    --bm-muted: #94a3b8;
    --bm-line: rgba(148,163,184,0.2);
    --bm-line-strong: rgba(148,163,184,0.35);
    --bm-shadow-sm: 0 1px 3px rgba(2,6,23,0.25);
    --bm-shadow: 0 4px 12px -2px rgba(2,6,23,0.25);
    --bm-shadow-md: 0 8px 24px -6px rgba(2,6,23,0.35);
    --bm-shadow-lg: 0 24px 48px -12px rgba(2,6,23,0.5);
}

.bm-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 24px 20px 48px;
    background: var(--bm-bg);
    color: var(--bm-ink);
    min-height: 100vh;
    transition: background 0.3s, color 0.3s;
}
@media (min-width: 640px) { .bm-wrap { padding: 32px; } }
@media (min-width: 1024px) { .bm-wrap { padding: 40px; } }

/* ===== HERO ===== */
.bm-hero {
    position: relative;
    border-radius: var(--bm-radius-xl);
    padding: 36px 28px;
    margin-bottom: 28px;
    overflow: hidden;
    background: linear-gradient(135deg, #24070b 0%, #4c0d14 30%, #991b1b 70%, #dc2626 100%);
    box-shadow: var(--bm-shadow-lg), inset 0 1px 0 rgba(255,255,255,0.08), 0 0 100px -20px rgba(99,102,241,0.15);
}
@media (min-width: 640px) { .bm-hero { padding: 44px 40px; } }
.bm-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
}
.bm-hero::after {
    content: '';
    position: absolute;
    top: -80px;
    right: -60px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(239,68,68,0.2) 0%, transparent 60%);
    pointer-events: none;
    animation: bmOrbFloat 8s ease-in-out infinite;
}
@keyframes bmOrbFloat {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.1); opacity: 1; }
}
.bm-hero-grid {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
    -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 80%);
}
.bm-hero-content { position: relative; z-index: 1; }
.bm-hero-badges {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.bm-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 100px;
    background: rgba(239,68,68,0.12);
    border: 1px solid rgba(252,165,165,0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #fca5a5;
}
.bm-hero-badge svg { width: 14px; height: 14px; }
.bm-hero-badge.secondary {
    background: rgba(255,255,255,0.06);
    border-color: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7);
}
.bm-hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.15;
    letter-spacing: -0.03em;
    margin-bottom: 10px;
}
.bm-hero-subtitle {
    font-family: var(--font-body);
    font-size: 1rem;
    color: rgba(255,255,255,0.55);
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.bm-hero-subtitle span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.bm-hero-subtitle svg { width: 16px; height: 16px; opacity: 0.6; }

/* ===== STATS BAR ===== */
.bm-stats {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
    margin-bottom: 28px;
    background: var(--bm-surface);
    border: 1px solid var(--bm-line);
    border-radius: var(--bm-radius-sm);
    box-shadow: var(--bm-shadow-md);
    overflow: hidden;
    animation: bmFadeUp 0.5s ease 0.05s forwards;
    opacity: 0;
}
@media (min-width: 640px) { .bm-stats { grid-template-columns: repeat(3, 1fr); } }
.bm-stat {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 22px 24px;
    border-bottom: 1px solid var(--bm-line);
    transition: var(--bm-transition);
}
.bm-stat:last-child { border-bottom: none; }
@media (min-width: 640px) {
    .bm-stat { border-bottom: none; border-right: 1px solid var(--bm-line); }
    .bm-stat:last-child { border-right: none; }
}
.bm-stat:hover { background: var(--bm-raised); }
.bm-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.bm-stat-icon.blue { background: rgba(59,130,246,0.06); color: #3b82f6; }
.bm-stat-icon.green { background: rgba(16,185,129,0.06); color: #10b981; }
.bm-stat-icon.amber { background: rgba(245,158,11,0.06); color: #f59e0b; }
.dark .bm-stat-icon.blue { background: rgba(59,130,246,0.05); color: #60a5fa; }
.dark .bm-stat-icon.green { background: rgba(16,185,129,0.05); color: #34d399; }
.dark .bm-stat-icon.amber { background: rgba(245,158,11,0.05); color: #fbbf24; }
.bm-stat-icon svg { width: 24px; height: 24px; }
.bm-stat-label {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--bm-muted);
    margin-bottom: 4px;
}
.bm-stat-value {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--bm-ink);
    line-height: 1.1;
}

/* ===== LAYOUT ===== */
.bm-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}
@media (min-width: 1024px) { .bm-layout { grid-template-columns: 1.35fr 1fr; align-items: start; } }

/* ===== MAP CARD ===== */
.bm-map-card {
    position: relative;
    background: var(--bm-surface);
    border: 1px solid var(--bm-line);
    border-radius: var(--bm-radius-sm);
    box-shadow: var(--bm-shadow-lg);
    overflow: hidden;
    height: calc(100vh - 20rem);
    min-height: 520px;
    animation: bmFadeUp 0.5s ease 0.1s forwards;
    opacity: 0;
}
.bm-map-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #7f1d1d, #991b1b, #dc2626);
    z-index: 10;
    border-radius: 4px 4px 0 0;
}
#map {
    width: 100%;
    height: 100%;
    background: #1f2937;
}

/* Map overlay controls */
.bm-map-overlay {
    position: absolute;
    left: 16px;
    bottom: 16px;
    z-index: 500;
    display: flex;
    gap: 6px;
    padding: 6px;
    border-radius: 14px;
    background: rgba(15, 13, 31, 0.9);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.08);
}
.bm-map-overlay-btn {
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
    transition: var(--bm-transition);
}
.bm-map-overlay-btn:hover { color: rgba(255,255,255,0.8); }
.bm-map-overlay-btn.active {
    background: linear-gradient(135deg, #991b1b, #dc2626);
    color: #ffffff;
    box-shadow: 0 4px 12px -2px rgba(220,38,38,0.3);
}
.bm-map-overlay-btn svg { width: 15px; height: 15px; }

/* ===== SIDEBAR ===== */
.bm-sidebar {
    background: var(--bm-surface);
    border: 1px solid var(--bm-line);
    border-radius: var(--bm-radius-sm);
    box-shadow: var(--bm-shadow-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 20rem);
    min-height: 520px;
    animation: bmFadeUp 0.5s ease 0.15s forwards;
    opacity: 0;
    position: relative;
}
.bm-sidebar::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #991b1b, #dc2626);
    border-radius: 4px 4px 0 0;
}
.bm-sidebar-header {
    padding: 24px;
    border-bottom: 1px solid var(--bm-line);
    background: linear-gradient(180deg, var(--bm-raised), var(--bm-surface));
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.bm-sidebar-header h2 {
    font-family: var(--font-display);
    font-size: 1.125rem;
    font-weight: 800;
    color: var(--bm-ink);
    letter-spacing: -0.01em;
}
.bm-sidebar-header p {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--bm-muted);
    margin-top: 4px;
}
.bm-sidebar-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: var(--bm-raised);
}

/* Back button */
.bm-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 14px;
    padding: 10px 20px;
    border-radius: 12px;
    background: var(--bm-raised);
    border: 1px solid var(--bm-line-strong);
    color: var(--bm-muted);
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--bm-transition);
}
.bm-back-btn:hover {
    background: var(--bm-surface);
    border-color: var(--bm-gold);
    color: var(--bm-gold);
    transform: translateY(-1px);
    box-shadow: var(--bm-shadow-sm);
}
.bm-back-btn svg { width: 16px; height: 16px; }

/* ===== PROJECT CARDS ===== */
.bm-project-card {
    background: var(--bm-surface);
    border: 1px solid var(--bm-line);
    border-radius: var(--bm-radius-xs);
    overflow: hidden;
    cursor: pointer;
    transition: var(--bm-transition);
    margin-bottom: 16px;
    position: relative;
}
.bm-project-card:last-child { margin-bottom: 0; }
.bm-project-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 4px; height: 100%;
    background: linear-gradient(180deg, #6366f1, #4338ca);
    opacity: 0;
    transition: opacity 0.3s;
}
.bm-project-card:hover {
    border-color: var(--bm-line-strong);
    box-shadow: var(--bm-shadow-md);
    transform: translateX(3px);
}
.bm-project-card:hover::before { opacity: 1; }
.bm-project-card.selected {
    border-color: var(--bm-gold);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.1), var(--bm-shadow-md);
}
.bm-project-card.selected::before { opacity: 1; }
.bm-project-image-wrap {
    position: relative;
    height: 170px;
    overflow: hidden;
}
.bm-project-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}
.bm-project-card:hover .bm-project-image { transform: scale(1.05); }
.bm-project-image-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 60px;
    background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);
    pointer-events: none;
}
.bm-project-noimage {
    width: 100%;
    height: 170px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--bm-raised), var(--bm-surface));
    color: var(--bm-muted);
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 700;
}
.bm-project-body { padding: 20px; }
.bm-project-name {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 800;
    color: var(--bm-ink);
    line-height: 1.35;
    margin-bottom: 10px;
}
.bm-project-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}

/* Status badges */
.bm-status {
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
.bm-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.bm-status-proposed { background: rgba(251,191,36,0.06); color: #d97706; border: 1px solid rgba(251,191,36,0.08); }
.bm-status-proposed .bm-status-dot { background: #f59e0b; box-shadow: 0 0 0 2px rgba(245,158,11,0.12); }
.bm-status-bidding { background: rgba(250,204,21,0.06); color: #a16207; border: 1px solid rgba(250,204,21,0.08); }
.bm-status-bidding .bm-status-dot { background: #eab308; }
.bm-status-ongoing { background: rgba(59,130,246,0.06); color: #2563eb; border: 1px solid rgba(59,130,246,0.08); }
.bm-status-ongoing .bm-status-dot { background: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.12); }
.bm-status-award { background: rgba(139,92,246,0.06); color: #7c3aed; border: 1px solid rgba(139,92,246,0.08); }
.bm-status-award .bm-status-dot { background: #8b5cf6; }
.bm-status-implementation { background: rgba(14,165,233,0.06); color: #0284c7; border: 1px solid rgba(14,165,233,0.08); }
.bm-status-implementation .bm-status-dot { background: #0ea5e9; }
.bm-status-completed { background: rgba(16,185,129,0.06); color: #059669; border: 1px solid rgba(16,185,129,0.08); }
.bm-status-completed .bm-status-dot { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,0.12); }
.bm-status-hold { background: rgba(239,68,68,0.06); color: #dc2626; border: 1px solid rgba(239,68,68,0.08); }
.bm-status-hold .bm-status-dot { background: #ef4444; }
.bm-status-cancelled { background: rgba(107,114,128,0.06); color: #6b7280; border: 1px solid rgba(107,114,128,0.08); }
.bm-status-cancelled .bm-status-dot { background: #9ca3af; }
.dark .bm-status-proposed { background: rgba(251,191,36,0.05); color: #fbbf24; }
.dark .bm-status-bidding { background: rgba(250,204,21,0.05); color: #facc15; }
.dark .bm-status-ongoing { background: rgba(59,130,246,0.05); color: #60a5fa; }
.dark .bm-status-award { background: rgba(139,92,246,0.05); color: #a78bfa; }
.dark .bm-status-implementation { background: rgba(14,165,233,0.05); color: #38bdf8; }
.dark .bm-status-completed { background: rgba(16,185,129,0.05); color: #34d399; }
.dark .bm-status-hold { background: rgba(239,68,68,0.05); color: #f87171; }
.dark .bm-status-cancelled { background: rgba(148,163,184,0.05); color: #94a3b8; }

.bm-project-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
}
.bm-project-stat {
    padding: 12px 14px;
    border-radius: 10px;
    background: var(--bm-raised);
    border: 1px solid var(--bm-line);
    transition: var(--bm-transition);
}
.bm-project-card:hover .bm-project-stat {
    background: var(--bm-surface);
    border-color: var(--bm-line-strong);
}
.bm-project-stat-label {
    font-family: var(--font-body);
    font-size: 0.625rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--bm-muted);
    margin-bottom: 4px;
}
.bm-project-stat-value {
    font-family: var(--font-display);
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--bm-ink);
}
.bm-project-desc {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--bm-muted);
    line-height: 1.55;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
}

/* ===== SELECTED PROJECT DETAIL ===== */
.bm-selected {
    background: var(--bm-surface);
    border: 1px solid var(--bm-line);
    border-radius: var(--bm-radius-xs);
    overflow: hidden;
    box-shadow: var(--bm-shadow-md);
}
.bm-selected-header {
    padding: 28px 24px;
    border-bottom: 1px solid var(--bm-line);
    background: linear-gradient(180deg, var(--bm-raised), var(--bm-surface));
}
.bm-selected-label {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: var(--bm-indigo);
    margin-bottom: 10px;
}
.bm-selected-title {
    font-family: var(--font-display);
    font-size: clamp(1.125rem, 2.5vw, 1.35rem);
    font-weight: 800;
    color: var(--bm-ink);
    line-height: 1.25;
    letter-spacing: -0.01em;
}
.bm-selected-status {
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
.dark .bm-selected-status { background: rgba(59,130,246,0.05); color: #60a5fa; }
.bm-selected-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}
.bm-selected-body { padding: 24px; }

/* Lifecycle stepper */
.bm-lifecycle {
    padding: 20px;
    border-radius: var(--bm-radius-xs);
    background: linear-gradient(135deg, var(--bm-raised), var(--bm-surface));
    border: 1px solid var(--bm-line);
    margin-bottom: 20px;
}
.bm-lifecycle-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.bm-lifecycle-title {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: var(--bm-muted);
}
.bm-lifecycle-status {
    padding: 6px 14px;
    border-radius: 100px;
    background: var(--bm-raised);
    border: 1px solid var(--bm-line);
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 800;
    color: var(--bm-ink);
}
.bm-lifecycle-track {
    display: flex;
    align-items: flex-start;
}
.bm-step {
    position: relative;
    z-index: 1;
    display: flex;
    flex: 1;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    text-align: center;
}
.bm-step-dot {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: 0.75rem;
    font-weight: 800;
    border: 3px solid var(--bm-line-strong);
    background: var(--bm-surface);
    color: var(--bm-muted);
    transition: var(--bm-transition);
}
.bm-step.complete .bm-step-dot {
    border-color: #10b981;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    box-shadow: 0 4px 12px -2px rgba(16,185,129,0.3);
}
.bm-step.current .bm-step-dot {
    border-color: #3b82f6;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #ffffff;
    box-shadow: 0 4px 12px -2px rgba(59,130,246,0.3);
    animation: bmStepPulse 2s ease-in-out infinite;
}
@keyframes bmStepPulse {
    0%, 100% { box-shadow: 0 4px 12px -2px rgba(59,130,246,0.3); }
    50% { box-shadow: 0 4px 20px 2px rgba(59,130,246,0.4); }
}
.bm-step-label {
    font-family: var(--font-body);
    font-size: 0.625rem;
    font-weight: 700;
    color: var(--bm-muted);
    line-height: 1.2;
}
.bm-step.complete .bm-step-label { color: #059669; }
.bm-step.current .bm-step-label { color: var(--bm-indigo); }
.bm-step-line {
    flex: 1;
    height: 3px;
    margin-top: 15px;
    background: var(--bm-line-strong);
    border-radius: 2px;
}
.bm-step-line.complete { background: linear-gradient(90deg, #10b981, #059669); }

/* Detail grid */
.bm-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 20px;
}
.bm-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--bm-raised), var(--bm-surface));
    border: 1px solid var(--bm-line);
    transition: var(--bm-transition);
}
.bm-detail-item:hover {
    border-color: var(--bm-line-strong);
    box-shadow: var(--bm-shadow-sm);
    transform: translateY(-1px);
}
.bm-detail-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.bm-detail-icon svg { width: 18px; height: 18px; }
.bm-detail-icon.purple { background: rgba(139,92,246,0.06); color: #7c3aed; }
.bm-detail-icon.green { background: rgba(16,185,129,0.06); color: #059669; }
.bm-detail-icon.rose { background: rgba(244,63,94,0.06); color: #e11d48; }
.bm-detail-icon.blue { background: rgba(59,130,246,0.06); color: #2563eb; }
.dark .bm-detail-icon.purple { background: rgba(139,92,246,0.05); color: #a78bfa; }
.dark .bm-detail-icon.green { background: rgba(16,185,129,0.05); color: #34d399; }
.dark .bm-detail-icon.rose { background: rgba(244,63,94,0.05); color: #fb7185; }
.dark .bm-detail-icon.blue { background: rgba(59,130,246,0.05); color: #60a5fa; }
.bm-detail-label {
    font-family: var(--font-body);
    font-size: 0.625rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--bm-muted);
    margin-bottom: 3px;
}
.bm-detail-value {
    font-family: var(--font-display);
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--bm-ink);
}

/* Progress bars */
.bm-progress-wrap {
    padding: 16px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--bm-raised), var(--bm-surface));
    border: 1px solid var(--bm-line);
    margin-bottom: 16px;
}
.bm-progress-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.bm-progress-label {
    font-family: var(--font-body);
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--bm-ink-secondary);
}
.bm-progress-value {
    font-family: var(--font-display);
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--bm-ink);
}
.bm-progress-track {
    height: 10px;
    background: var(--bm-line);
    border-radius: 100px;
    overflow: hidden;
    position: relative;
}
.bm-progress-fill {
    height: 100%;
    border-radius: 100px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}
.bm-progress-fill::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
    animation: bmShimmer 2s ease-in-out infinite;
}
@keyframes bmShimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* View all button */
.bm-viewall {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--bm-raised), var(--bm-surface));
    border: 1px solid var(--bm-line-strong);
    color: var(--bm-ink-secondary);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--bm-transition);
}
.bm-viewall:hover {
    background: var(--bm-surface);
    border-color: var(--bm-gold);
    color: var(--bm-gold);
    transform: translateY(-1px);
    box-shadow: var(--bm-shadow-sm);
}
.bm-viewall svg { width: 18px; height: 18px; }

/* Empty state */
.bm-empty {
    text-align: center;
    padding: 56px 24px;
}
.bm-empty-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    border-radius: var(--bm-radius-xs);
    background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(245,158,11,0.04));
    color: var(--bm-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px -2px rgba(245,158,11,0.1);
}
.bm-empty-icon svg { width: 32px; height: 32px; }
.bm-empty h4 {
    font-family: var(--font-display);
    font-size: 1.0625rem;
    font-weight: 800;
    color: var(--bm-ink);
    margin-bottom: 6px;
}
.bm-empty p {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    color: var(--bm-muted);
}

/* Leaflet dark mode */
.dark .leaflet-container { background: #0f0e1a; }
.dark .leaflet-popup-content-wrapper,
.dark .leaflet-popup-tip {
    background: #1c1b2e;
    color: #f8f7f5;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
}
.dark .leaflet-container a.leaflet-popup-close-button { color: #a1a1aa; }

/* ===== ANIMATIONS ===== */
@keyframes bmFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.bm-animate {
    animation: bmFadeUp 0.5s ease forwards;
    opacity: 0;
}
.bm-animate:nth-child(1) { animation-delay: 0.04s; }
.bm-animate:nth-child(2) { animation-delay: 0.08s; }
.bm-animate:nth-child(3) { animation-delay: 0.12s; }
.bm-animate:nth-child(4) { animation-delay: 0.16s; }
.bm-animate:nth-child(5) { animation-delay: 0.20s; }

@media (prefers-reduced-motion: reduce) {
    .bm-animate { animation: none; opacity: 1; }
    .bm-progress-fill::after { animation: none; }
    .bm-hero::after { animation: none; }
}

@media (max-width: 767px) {
    .bm-stats { grid-template-columns: 1fr; }
    .bm-stat { border-right: none !important; border-bottom: 1px solid var(--bm-line); }
    .bm-stat:last-child { border-bottom: none; }
    .bm-map-card, .bm-sidebar { min-height: 460px; }
    .bm-sidebar { max-height: none; }
}
</style>

<div class="bm-wrap">
    <!-- HERO -->
    <div class="bm-hero bm-animate">
        <div class="bm-hero-grid"></div>
        <div class="bm-hero-content">
            <div class="bm-hero-badges">
                <span class="bm-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Cabuyao City, Laguna
                </span>
                <span class="bm-hero-badge secondary">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Interactive Map View
                </span>
            </div>
            <h1 class="bm-hero-title">Barangay Map</h1>
            <div class="bm-hero-subtitle">
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
    <div class="bm-stats">
        <div class="bm-stat">
            <div class="bm-stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6.75M9 12h6.75M9 17.25h6.75"/></svg>
            </div>
            <div>
                <div class="bm-stat-label">Total Projects</div>
                <div class="bm-stat-value" id="statTotalProjects">—</div>
            </div>
        </div>
        <div class="bm-stat">
            <div class="bm-stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
            </div>
            <div>
                <div class="bm-stat-label">Avg Progress</div>
                <div class="bm-stat-value" id="statAvgProgress">—</div>
            </div>
        </div>
        <div class="bm-stat">
            <div class="bm-stat-icon amber">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="bm-stat-label">Total Budget</div>
                <div class="bm-stat-value" id="statTotalBudget">—</div>
            </div>
        </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="bm-layout">
        <!-- MAP -->
        <div class="bm-map-card">
            <div id="map"></div>
            <div class="bm-map-overlay">
                <button type="button" id="btnLightTiles" class="bm-map-overlay-btn active">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    Light
                </button>
                <button type="button" id="btnDarkTiles" class="bm-map-overlay-btn">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.598.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    Dark
                </button>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="bm-sidebar">
            <div class="bm-sidebar-header">
                <h2>Department Projects</h2>
                <p>Cabuyao City Projects</p>
                <div id="departmentSidebarAction"></div>
            </div>
            <div class="bm-sidebar-body" id="departmentProjectList"></div>
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
                'Proposed': 'bm-status-proposed', 'Planning': 'bm-status-proposed',
                'For bidding': 'bm-status-bidding', 'Procurement': 'bm-status-bidding',
                'Bidding ongoing': 'bm-status-ongoing', 'Bidding - Success': 'bm-status-award',
                'Award of contract': 'bm-status-award', 'Implementation': 'bm-status-implementation',
                'On Going': 'bm-status-implementation', 'Completed': 'bm-status-completed',
                'On Hold': 'bm-status-hold', 'Cancelled': 'bm-status-cancelled'
            };
            return map[status] || 'bm-status-proposed';
        }

        function renderLifecycleStepper(status) {
            const steps = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation'];
            const stageByStatus = { Proposed: 0, 'For bidding': 1, 'Bidding ongoing': 2, 'Award of contract': 3, Implementation: 4, Completed: 4, Planning: 0, Procurement: 1, 'Bidding - Success': 3, 'On Going': 4 };
            const activeStep = stageByStatus[status];
            const completedProject = status === 'Completed';

            return `<div class="bm-lifecycle"><div class="bm-lifecycle-head"><span class="bm-lifecycle-title">Project lifecycle</span><span class="bm-lifecycle-status">${status || 'Unknown'}</span></div><div class="bm-lifecycle-track">${steps.map((step, stepIndex) => { const complete = activeStep !== undefined && (stepIndex < activeStep || completedProject); const current = activeStep !== undefined && stepIndex === activeStep && !completedProject; const state = complete ? 'complete' : (current ? 'current' : ''); const line = stepIndex < steps.length - 1 ? `<div class="bm-step-line ${complete ? 'complete' : ''}"></div>` : ''; return `<div class="bm-step ${state}"><div class="bm-step-dot">${complete ? '&#10003;' : stepIndex + 1}</div><span class="bm-step-label">${step}</span></div>${line}`; }).join('')}</div></div>`;
        }

        function renderProjectCard(project, index, isSingle = false) {
            const props = project.properties;
            const progress = calculateProgress(project);
            const imageHtml = props.image
                ? `<div class="bm-project-image-wrap"><img src="${props.image}" alt="${props.name}" class="bm-project-image"><div class="bm-project-image-overlay"></div></div>`
                : '<div class="bm-project-noimage">No image</div>';

            if (isSingle) {
                const expenditure = Number(props.actual_budget || 0);
                const budget = Number(props.budget || 0);
                const expenditureProgress = budget > 0 ? Math.min(100, (expenditure / budget) * 100) : 0;
                return `<div class="bm-selected" data-index="${index}"><div class="bm-selected-header"><div class="bm-selected-label">Selected project</div><div class="bm-selected-title">${props.name}</div><span class="bm-selected-status">${props.status || 'Unknown'}</span></div>${props.image ? `<img src="${props.image}" alt="${props.name}" class="bm-selected-image">` : '<div class="bm-project-noimage" style="height:220px">No image</div>'}<div class="bm-selected-body">${renderLifecycleStepper(props.status)}<div class="bm-detail-grid"><div class="bm-detail-item"><div class="bm-detail-icon purple"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></div><div><div class="bm-detail-label">Barangay</div><div class="bm-detail-value">${props.barangay || 'Not specified'}</div></div></div><div class="bm-detail-item"><div class="bm-detail-icon green"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="bm-detail-label">Budget</div><div class="bm-detail-value">${formatCurrency(budget)}</div></div></div><div class="bm-detail-item"><div class="bm-detail-icon rose"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="bm-detail-label">Expenditure</div><div class="bm-detail-value">${formatCurrency(expenditure)}</div></div></div><div class="bm-detail-item"><div class="bm-detail-icon blue"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div><div><div class="bm-detail-label">Progress</div><div class="bm-detail-value">${progress.toFixed(1)}%</div></div></div></div><div class="bm-progress-wrap"><div class="bm-progress-head"><span class="bm-progress-label">Expenditure progress</span><span class="bm-progress-value">${expenditureProgress.toFixed(1)}%</span></div><div class="bm-progress-track"><div class="bm-progress-fill" style="width:${expenditureProgress}%;background:linear-gradient(90deg,#10b981,#059669)"></div></div></div><div class="bm-progress-wrap"><div class="bm-progress-head"><span class="bm-progress-label">Timeline progress</span><span class="bm-progress-value">${progress.toFixed(1)}%</span></div><div class="bm-progress-track"><div class="bm-progress-fill" style="width:${progress}%;background:linear-gradient(90deg,#6366f1,#f59e0b,#d97706)"></div></div></div><p style="font-family:var(--font-body);font-size:0.875rem;color:var(--bm-ink-secondary);line-height:1.6;margin:0 0 16px;">${props.description || 'No description available.'}</p><button type="button" class="bm-viewall show-all-projects-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>View all projects</button></div></div>`;
            }

            return `<div class="bm-project-card ${selectedProjectIndex === index ? 'selected' : ''}" data-index="${index}">${imageHtml}<div class="bm-project-body"><div class="bm-project-name">${props.name}</div><div class="bm-project-meta"><span class="bm-status ${getStatusClass(props.status)}"><span class="bm-status-dot"></span>${props.status || 'Unknown'}</span><span class="bm-status" style="background:var(--bm-raised);color:var(--bm-muted);border:1px solid var(--bm-line);">${props.barangay || 'Barangay not specified'}</span></div><div class="bm-project-stats"><div class="bm-project-stat"><div class="bm-project-stat-label">Budget</div><div class="bm-project-stat-value">${formatCurrency(props.budget)}</div></div><div class="bm-project-stat"><div class="bm-project-stat-label">Progress</div><div class="bm-project-stat-value">${progress.toFixed(1)}%</div></div></div><p class="bm-project-desc">${props.description || 'No description available.'}</p></div></div>`;
        }

        function updateSidebarAction() {
            if (!departmentSidebarAction) return;
            if (selectedBarangayName) {
                departmentSidebarAction.innerHTML = `<button type="button" id="backToAllBarangays" class="bm-back-btn"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>Back to all barangays</button>`;
                document.getElementById('backToAllBarangays').addEventListener('click', resetToAllBarangays);
            } else {
                departmentSidebarAction.innerHTML = '';
            }
        }

        function clearSelection() {
            document.querySelectorAll('.bm-project-card').forEach(card => card.classList.remove('selected'));
        }

        function highlightProject(index) {
            selectedProjectIndex = index;
            clearSelection();
            const card = document.querySelector(`.bm-project-card[data-index="${index}"]`);
            if (card) {
                card.classList.add('selected');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function renderProjectList(projects) {
            updateSidebarAction();
            if (!projects.length) {
                projectList.innerHTML = `<div class="bm-empty"><div class="bm-empty-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div><h4>No projects found</h4><p>No public projects recorded in ${selectedBarangayName || 'this barangay'} yet.</p></div>`;
                return;
            }
            projectList.innerHTML = projects.map(project => renderProjectCard(project, project.originalIndex, projects.length === 1)).join('');
            document.querySelectorAll('.show-all-projects-btn').forEach(btn => btn.addEventListener('click', resetToAllBarangays));
            document.querySelectorAll('.bm-project-card').forEach(card => {
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
                    return ({ 'Proposed': '#fbbf24', 'Planning': '#fbbf24', 'For bidding': '#f59e0b', 'Procurement': '#f59e0b', 'Bidding ongoing': '#3b82f6', 'Award of contract': '#8b5cf6', 'Bidding - Success': '#8b5cf6', 'Implementation': '#0ea5e9', 'On Going': '#0ea5e9', 'Completed': '#10b981', 'On Hold': '#ef4444', 'Cancelled': '#64748b' })[status] || '#64748b';
                }

                allMarkers = L.featureGroup();
                projectFeatures = [];
                window.projectFeatures = projectFeatures;

                fetch('{{ url('/api/projects/geojson') }}')
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
                            marker.bindPopup(`<div style="font-family:var(--font-body);font-size:0.875rem;color:var(--bm-ink)"><h4 style="margin:0 0 6px;font-family:var(--font-display);font-weight:800;">${project.properties.name}</h4><span style="display:inline-flex;align-items:center;gap:6px;font-size:0.75rem;font-weight:700;color:var(--bm-muted);"><span style="width:8px;height:8px;border-radius:50%;background:${getMarkerColor(project.properties.status)};display:inline-block;"></span>${project.properties.status || 'Unknown'}</span></div>`);
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
                        projectList.innerHTML = `<div class="bm-empty"><div class="bm-empty-icon" style="background:linear-gradient(135deg,rgba(239,68,68,0.08),rgba(239,68,68,0.04));color:#dc2626;"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg></div><h4>Unable to load projects</h4><p>Please try refreshing the page.</p></div>`;
                    });

                map.fitBounds(boundedArea, { padding: [24, 24] });
                map.setMaxBounds(boundedArea);
                map.setMinZoom(map.getZoom());
                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(function(error) {
                console.error(error);
                projectList.innerHTML = `<div class="bm-empty"><div class="bm-empty-icon" style="background:linear-gradient(135deg,rgba(239,68,68,0.08),rgba(239,68,68,0.04));color:#dc2626;"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg></div><h4>Unable to load map</h4><p>Please try refreshing the page.</p></div>`;
            });
    });
</script>
@endsection