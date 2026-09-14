@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<style>
/* ================================================================
   DEPARTMENT MAP — UNIFIED, ACCESSIBLE, PRODUCTION-READY
   ================================================================ */

/* Root variables: light mode */
.dept-map-container {
    --dm-bg: #ffffff;
    --dm-surface: #ffffff;
    --dm-raised: #f8f7f5;
    --dm-ink: #0f0d1f;
    --dm-ink-secondary: #374151;
    --dm-muted: #6b7280;
    --dm-line: rgba(0,0,0,0.08);
    --dm-line-strong: rgba(0,0,0,0.15);
    --dm-shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
    --dm-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.08);
    --dm-shadow-md: 0 8px 16px -4px rgba(0,0,0,0.12), 0 4px 8px -4px rgba(0,0,0,0.08);
    --dm-shadow-lg: 0 16px 32px -8px rgba(0,0,0,0.15), 0 8px 16px -8px rgba(0,0,0,0.1);
    --dm-shadow-xl: 0 24px 48px -12px rgba(0,0,0,0.2), 0 12px 24px -12px rgba(0,0,0,0.12);
    --dm-radius: 20px;
    --dm-radius-sm: 14px;
    --dm-radius-xs: 10px;
    --dm-accent-start: #451a03;
    --dm-accent-mid: #b45309;
    --dm-accent-end: #d97706;
    --dm-gold: #f59e0b;
}

/* Dark mode via .dark class or html.dark-mode */
.dark .dept-map-container,
html.dark-mode .dept-map-container {
    --dm-bg: #0f172a;
    --dm-surface: #111827;
    --dm-raised: #1e293b;
    --dm-ink: #f8f7f5;
    --dm-ink-secondary: #cbd5e1;
    --dm-muted: #94a3b8;
    --dm-line: rgba(148,163,184,0.18);
    --dm-line-strong: rgba(148,163,184,0.28);
    --dm-shadow-sm: 0 1px 3px rgba(0,0,0,0.4);
    --dm-shadow: 0 4px 6px -1px rgba(0,0,0,0.4), 0 2px 4px -2px rgba(0,0,0,0.3);
    --dm-shadow-md: 0 8px 16px -4px rgba(0,0,0,0.5), 0 4px 8px -4px rgba(0,0,0,0.3);
    --dm-shadow-lg: 0 16px 32px -8px rgba(0,0,0,0.6), 0 8px 16px -8px rgba(0,0,0,0.4);
    --dm-shadow-xl: 0 24px 48px -12px rgba(0,0,0,0.7), 0 12px 24px -12px rgba(0,0,0,0.5);
}

html:not(.dark-mode) body:has(.dept-map-container),
html:not(.dark-mode) .authenticated-layout:has(.dept-map-container) {
    background-color: #ffffff !important;
}

html.dark-mode body:has(.dept-map-container),
html.dark-mode .authenticated-layout:has(.dept-map-container),
.dark body:has(.dept-map-container),
.dark .authenticated-layout:has(.dept-map-container) {
    background-color: #0f172a !important;
}

.dept-map-container {
    max-width: 1440px;
    margin: 0 auto;
    padding: 24px;
    background: var(--dm-bg);
    color: var(--dm-ink);
    transition: background 0.3s, color 0.3s;
    min-height: 100vh;
}
@media (min-width: 640px) { .dept-map-container { padding: 32px; } }
@media (min-width: 1024px) { .dept-map-container { padding: 40px; } }

/* ===== HERO ===== */
.dept-map-hero {
    position: relative;
    background: linear-gradient(135deg, var(--dm-accent-start) 0%, #78350f 30%, var(--dm-accent-mid) 70%, var(--dm-accent-end) 100%);
    border-radius: var(--dm-radius);
    padding: 36px 28px;
    margin-bottom: 28px;
    box-shadow: var(--dm-shadow-xl);
    overflow: hidden;
}
@media (min-width: 640px) { .dept-map-hero { padding: 44px 48px; } }

.dept-map-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.5;
    pointer-events: none;
}
.dept-map-hero::after {
    content: "";
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 60%);
    pointer-events: none;
    animation: heroPulse 8s ease-in-out infinite;
}
@keyframes heroPulse {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.1); opacity: 1; }
}
@media (prefers-reduced-motion: reduce) {
    .dept-map-hero::after { animation: none; }
}

.dept-map-hero-content { position: relative; z-index: 1; }
.dept-map-hero-badge-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.dept-map-hero-badge {
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
}
.dept-map-hero-badge svg { width: 14px; height: 14px; }
.dept-map-hero-badge.gold {
    background: rgba(245,158,11,0.15);
    border-color: rgba(245,158,11,0.3);
    color: #fcd34d;
}

/* Engineering theme override */
.engineering-map-theme .dept-map-hero {
    --dm-accent-start: #0a4353;
    --dm-accent-mid: #11788a;
    --dm-accent-end: #22a6b8;
}
.engineering-map-theme .dept-map-hero::after {
    background: radial-gradient(circle, rgba(158,230,247,0.22) 0%, transparent 60%);
}
.engineering-map-theme .dept-map-hero-badge.gold {
    background: rgba(15,106,124,0.32);
    border-color: rgba(158,230,247,0.38);
    color: #d8f5ff;
}

.dept-map-hero-title {
    font-family: "Plus Jakarta Sans", "Inter", sans-serif;
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.15;
    letter-spacing: -0.03em;
    margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.dept-map-hero-subtitle {
    font-size: 1rem;
    color: rgba(255,255,255,0.65);
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.dept-map-hero-subtitle span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.dept-map-hero-subtitle svg { width: 16px; height: 16px; opacity: 0.7; }

/* ===== STATS BAR ===== */
.dept-map-stats-bar {
    display: flex;
    gap: 0;
    background: var(--dm-surface);
    border: 1px solid var(--dm-line);
    border-radius: var(--dm-radius-sm);
    box-shadow: var(--dm-shadow-md);
    margin-bottom: 28px;
    overflow: hidden;
}
.dept-map-stat-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 20px 24px;
    border-right: 1px solid var(--dm-line);
}
.dept-map-stat-item:last-child { border-right: none; }
.dept-map-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.dept-map-stat-icon svg { width: 22px; height: 22px; }
.dept-map-stat-icon.blue { background: #dbeafe; color: #2563eb; }
.dept-map-stat-icon.emerald { background: #d1fae5; color: #059669; }
.dept-map-stat-icon.amber { background: #fef3c7; color: #b45309; }
.dept-map-stat-icon.purple { background: #ede9fe; color: #7c3aed; }
.dark .dept-map-stat-icon.blue,
html.dark-mode .dept-map-stat-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
.dark .dept-map-stat-icon.emerald,
html.dark-mode .dept-map-stat-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
.dark .dept-map-stat-icon.amber,
html.dark-mode .dept-map-stat-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
.dark .dept-map-stat-icon.purple,
html.dark-mode .dept-map-stat-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
.dept-map-stat-label {
    font-size: 0.625rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--dm-muted);
    margin-bottom: 2px;
}
.dept-map-stat-value {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--dm-ink);
    line-height: 1.2;
}
@media (max-width: 768px) {
    .dept-map-stats-bar { flex-direction: column; }
    .dept-map-stat-item { border-right: none; border-bottom: 1px solid var(--dm-line); }
    .dept-map-stat-item:last-child { border-bottom: none; }
}

/* ===== LAYOUT ===== */
.dept-map-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 28px;
}
@media (min-width: 1024px) {
    .dept-map-layout { grid-template-columns: 1.5fr 1fr; align-items: start; }
}

/* ===== MAP CARD ===== */
.dept-map-card {
    background: var(--dm-surface);
    border-radius: var(--dm-radius-sm);
    border: 1px solid var(--dm-line);
    box-shadow: var(--dm-shadow-lg);
    overflow: hidden;
    height: calc(100vh - 18rem);
    min-height: 450px;
    position: relative;
}
.dept-map-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #fbbf24, #f59e0b, #d97706, #b45309);
    z-index: 10;
}
.dept-map-wrap {
    width: 100%;
    height: 100%;
    background: #1f2937;
}

/* Map legend overlay */
.dept-map-legend {
    position: absolute;
    bottom: 16px;
    right: 16px;
    z-index: 500;
    max-width: 260px;
}
.dept-map-legend .map-status-legend {
    position: static;
}

/* Map overlay controls */
.dept-map-overlay {
    position: absolute;
    bottom: 20px;
    left: 20px;
    z-index: 500;
    display: flex;
    gap: 8px;
}
.dept-map-overlay-btn {
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    font-family: inherit;
    backdrop-filter: blur(12px);
    background: rgba(15, 13, 31, 0.85);
    color: white;
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.dept-map-overlay-btn:hover {
    background: rgba(15, 13, 31, 0.95);
    transform: translateY(-1px);
}
.dept-map-overlay-btn svg { width: 14px; height: 14px; }
.dept-map-overlay-btn.active {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border-color: transparent;
}

/* ===== SIDEBAR ===== */
.dept-map-sidebar {
    background: var(--dm-surface);
    border-radius: var(--dm-radius-sm);
    border: 1px solid var(--dm-line);
    box-shadow: var(--dm-shadow-lg);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 18rem);
    min-height: 450px;
    position: relative;
}
.dept-map-sidebar::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b, #d97706);
    z-index: 2;
}
.dept-map-sidebar-header {
    padding: 22px 24px;
    border-bottom: 1px solid var(--dm-line);
    flex-shrink: 0;
    background: linear-gradient(180deg, var(--dm-raised) 0%, var(--dm-surface) 100%);
    position: relative;
    z-index: 1;
}
.dept-map-sidebar-header h2 {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1.0625rem;
    font-weight: 800;
    color: var(--dm-ink);
    letter-spacing: -0.01em;
}
.dept-map-sidebar-header p {
    font-size: 0.75rem;
    color: var(--dm-muted);
    margin-top: 3px;
}
.dept-map-sidebar-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: var(--dm-raised);
}

/* Back button */
.dept-map-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 14px;
    padding: 9px 18px;
    border-radius: 100px;
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--dm-ink-secondary);
    background: var(--dm-surface);
    border: 1px solid var(--dm-line-strong);
    cursor: pointer;
    transition: all 0.15s;
    font-family: inherit;
}
.dept-map-back-btn:hover {
    background: var(--dm-raised);
    box-shadow: var(--dm-shadow-sm);
    border-color: #f59e0b;
    color: #d97706;
}
.dept-map-back-btn svg { width: 16px; height: 16px; }

/* ===== PROJECT CARDS ===== */
.dept-map-project-card {
    background: var(--dm-surface);
    border: 1px solid var(--dm-line);
    border-radius: var(--dm-radius-xs);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    margin-bottom: 14px;
    position: relative;
}
.dept-map-project-card:last-child { margin-bottom: 0; }
.dept-map-project-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; width: 4px; bottom: 0;
    background: linear-gradient(180deg, #f59e0b, #d97706);
    opacity: 0;
    transition: opacity 0.25s;
}
.dept-map-project-card:hover,
.dept-map-project-card:focus-visible {
    border-color: var(--dm-line-strong);
    box-shadow: var(--dm-shadow-md);
    transform: translateY(-3px);
    outline: none;
}
.dept-map-project-card:hover::before,
.dept-map-project-card:focus-visible::before { opacity: 1; }
.dept-map-project-card.selected {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245,158,11,0.2), var(--dm-shadow-md);
}
.dept-map-project-card.selected::before { opacity: 1; }

.dept-map-project-image-wrap {
    position: relative;
    height: 170px;
    overflow: hidden;
}
.dept-map-project-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.dept-map-project-card:hover .dept-map-project-image { transform: scale(1.05); }
.dept-map-project-image-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 60px;
    background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
    pointer-events: none;
}
.dept-map-project-noimage {
    width: 100%;
    height: 170px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--dm-raised), var(--dm-surface));
    color: var(--dm-muted);
    font-size: 0.75rem;
    font-weight: 600;
    gap: 8px;
}
.dept-map-project-body { padding: 18px; }
.dept-map-project-name {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 0.9375rem;
    font-weight: 800;
    color: var(--dm-ink);
    line-height: 1.3;
    margin-bottom: 10px;
}
.dept-map-project-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}

/* Status badges */
.dept-map-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 100px;
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.dept-map-status-badge .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    box-shadow: 0 0 0 2px currentColor;
    opacity: 0.4;
}
.dept-map-status-badge.proposed { background: rgba(37,99,235,0.10); color: #2563eb; }
.dept-map-status-badge.proposed .dot { background: #2563eb; }
.dept-map-status-badge.bidding { background: rgba(245,158,11,0.10); color: #f59e0b; }
.dept-map-status-badge.bidding .dot { background: #f59e0b; }
.dept-map-status-badge.bidding_ongoing { background: rgba(6,182,212,0.10); color: #06b6d4; }
.dept-map-status-badge.bidding_ongoing .dot { background: #06b6d4; }
.dept-map-status-badge.award { background: rgba(139,92,246,0.10); color: #8b5cf6; }
.dept-map-status-badge.award .dot { background: #8b5cf6; }
.dept-map-status-badge.implementation { background: rgba(15,118,110,0.10); color: #0f766e; }
.dept-map-status-badge.implementation .dot { background: #0f766e; }
.dept-map-status-badge.completed { background: rgba(22,163,74,0.10); color: #16a34a; }
.dept-map-status-badge.completed .dot { background: #16a34a; }
.dept-map-status-badge.onhold { background: rgba(220,38,38,0.10); color: #dc2626; }
.dept-map-status-badge.onhold .dot { background: #dc2626; }
.dept-map-status-badge.cancelled { background: rgba(100,116,139,0.10); color: #64748b; }
.dept-map-status-badge.cancelled .dot { background: #64748b; }

.dark .dept-map-status-badge.proposed,
html.dark-mode .dept-map-status-badge.proposed { background: rgba(37,99,235,0.12); color: #60a5fa; }
.dark .dept-map-status-badge.bidding,
html.dark-mode .dept-map-status-badge.bidding { background: rgba(245,158,11,0.12); color: #fbbf24; }
.dark .dept-map-status-badge.bidding_ongoing,
html.dark-mode .dept-map-status-badge.bidding_ongoing { background: rgba(6,182,212,0.12); color: #67e8f9; }
.dark .dept-map-status-badge.award,
html.dark-mode .dept-map-status-badge.award { background: rgba(139,92,246,0.12); color: #a78bfa; }
.dark .dept-map-status-badge.implementation,
html.dark-mode .dept-map-status-badge.implementation { background: rgba(15,118,110,0.12); color: #5eead4; }
.dark .dept-map-status-badge.completed,
html.dark-mode .dept-map-status-badge.completed { background: rgba(22,163,74,0.12); color: #4ade80; }
.dark .dept-map-status-badge.onhold,
html.dark-mode .dept-map-status-badge.onhold { background: rgba(220,38,38,0.12); color: #f87171; }
.dark .dept-map-status-badge.cancelled,
html.dark-mode .dept-map-status-badge.cancelled { background: rgba(100,116,139,0.12); color: #cbd5e1; }

.dept-map-project-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.dept-map-project-stat {
    padding: 12px 14px;
    background: var(--dm-raised);
    border: 1px solid var(--dm-line);
    border-radius: 10px;
    transition: all 0.15s;
}
.dept-map-project-card:hover .dept-map-project-stat {
    background: var(--dm-surface);
    border-color: var(--dm-line-strong);
}
.dept-map-project-stat-label {
    font-size: 0.625rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--dm-muted);
    margin-bottom: 3px;
}
.dept-map-project-stat-value {
    font-size: 0.8125rem;
    font-weight: 800;
    color: var(--dm-ink);
    font-family: "Plus Jakarta Sans", sans-serif;
}
.dept-map-project-desc {
    font-size: 0.8125rem;
    color: var(--dm-ink-secondary);
    line-height: 1.55;
    margin-top: 14px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ===== SINGLE PROJECT DETAIL CARD ===== */
.dept-map-detail-card {
    background: var(--dm-surface);
    border: 1px solid var(--dm-line);
    border-radius: var(--dm-radius-xs);
    overflow: hidden;
    box-shadow: var(--dm-shadow-md);
}
.dept-map-detail-header {
    padding: 24px;
    border-bottom: 1px solid var(--dm-line);
    background: linear-gradient(180deg, var(--dm-raised), var(--dm-surface));
}
.dept-map-detail-header-label {
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: var(--dm-muted);
    margin-bottom: 8px;
}
.dept-map-detail-header h3 {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--dm-ink);
    line-height: 1.25;
    letter-spacing: -0.01em;
}
.dept-map-detail-image-wrap {
    padding: 20px;
    padding-bottom: 0;
}
.dept-map-detail-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: var(--dm-radius-xs);
    display: block;
}
.dept-map-detail-body { padding: 24px; }

/* ===== STEPPER ===== */
.dept-map-stepper {
    padding: 20px;
    background: linear-gradient(135deg, var(--dm-raised), var(--dm-surface));
    border: 1px solid var(--dm-line);
    border-radius: var(--dm-radius-xs);
    margin-bottom: 20px;
}
.dept-map-stepper-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 12px;
    flex-wrap: wrap;
}
.dept-map-stepper-title {
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: var(--dm-muted);
}
.dept-map-stepper-status {
    font-size: 0.75rem;
    font-weight: 800;
    color: var(--dm-ink);
    padding: 4px 12px;
    border-radius: 100px;
    background: var(--dm-raised);
    border: 1px solid var(--dm-line);
}
.dept-map-stepper-track {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 6px;
}
.dept-map-step {
    text-align: center;
    position: relative;
    min-width: 0;
}
.dept-map-step:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 14px;
    left: 56%;
    width: 82%;
    height: 3px;
    background: var(--dm-line-strong);
    z-index: 0;
    border-radius: 2px;
}
.dept-map-step.completed:not(:last-child)::after {
    background: linear-gradient(90deg, #10b981, #059669);
}
.dept-map-step.halted:not(:last-child)::after {
    background: linear-gradient(90deg, #ef4444, #b91c1c);
}
.dept-map-step-dot {
    position: relative;
    z-index: 1;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 0.6875rem;
    font-weight: 800;
    font-family: "Plus Jakarta Sans", sans-serif;
    border: 3px solid var(--dm-line-strong);
    background: var(--dm-surface);
    color: var(--dm-muted);
    transition: all 0.3s ease;
}
.dept-map-step.completed .dept-map-step-dot {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.4);
}
.dept-map-step.active .dept-map-step-dot {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 12px -2px rgba(59, 130, 246, 0.4);
    animation: stepPulse 2s ease-in-out infinite;
}
.dept-map-step.halted .dept-map-step-dot {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 12px -2px rgba(239, 68, 68, 0.4);
}
@keyframes stepPulse {
    0%, 100% { box-shadow: 0 4px 12px -2px rgba(59, 130, 246, 0.4); }
    50% { box-shadow: 0 4px 20px 2px rgba(59, 130, 246, 0.5); }
}
@media (prefers-reduced-motion: reduce) {
    .dept-map-step.active .dept-map-step-dot { animation: none; }
}
.dept-map-step-label {
    font-size: 0.5625rem;
    font-weight: 700;
    color: var(--dm-muted);
    margin-top: 8px;
    line-height: 1.15;
    letter-spacing: -0.01em;
}
.dept-map-step.completed .dept-map-step-label { color: #059669; font-weight: 800; }
.dept-map-step.active .dept-map-step-label { color: var(--dm-ink); font-weight: 800; }
.dept-map-step.halted .dept-map-step-label { color: #ef4444; font-weight: 800; }

@media (max-width: 640px) {
    .dept-map-stepper-track {
        display: flex;
        overflow-x: auto;
        gap: 12px;
        padding-bottom: 8px;
        scrollbar-width: thin;
    }
    .dept-map-step { min-width: 72px; flex: 0 0 72px; }
    .dept-map-step:not(:last-child)::after { left: 58%; width: 70%; }
}

/* ===== DETAIL STATS GRID ===== */
.dept-map-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 20px;
}
@media (max-width: 480px) {
    .dept-map-detail-grid { grid-template-columns: 1fr; }
}
.dept-map-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px;
    background: linear-gradient(135deg, var(--dm-raised), var(--dm-surface));
    border: 1px solid var(--dm-line);
    border-radius: 12px;
    transition: all 0.2s;
}
.dept-map-detail-item:hover {
    border-color: var(--dm-line-strong);
    box-shadow: var(--dm-shadow-sm);
    transform: translateY(-1px);
}
.dept-map-detail-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.dept-map-detail-icon svg { width: 18px; height: 18px; }
.dept-map-detail-icon.blue { background: #dbeafe; color: #2563eb; }
.dept-map-detail-icon.emerald { background: #d1fae5; color: #059669; }
.dept-map-detail-icon.amber { background: #fef3c7; color: #b45309; }
.dept-map-detail-icon.rose { background: #ffe4e6; color: #e11d48; }
.dark .dept-map-detail-icon.blue,
html.dark-mode .dept-map-detail-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
.dark .dept-map-detail-icon.emerald,
html.dark-mode .dept-map-detail-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
.dark .dept-map-detail-icon.amber,
html.dark-mode .dept-map-detail-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
.dark .dept-map-detail-icon.rose,
html.dark-mode .dept-map-detail-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
.dept-map-detail-label {
    font-size: 0.625rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--dm-muted);
    margin-bottom: 3px;
}
.dept-map-detail-value {
    font-size: 0.8125rem;
    font-weight: 800;
    color: var(--dm-ink);
    font-family: "Plus Jakarta Sans", sans-serif;
}

/* ===== PROGRESS BARS ===== */
.dept-map-progress-wrap {
    margin-bottom: 20px;
    padding: 16px;
    background: linear-gradient(135deg, var(--dm-raised), var(--dm-surface));
    border: 1px solid var(--dm-line);
    border-radius: 12px;
}
.dept-map-description-card {
    margin-top: 18px;
    padding: 16px 18px;
    border: 1px solid var(--dm-line);
    border-radius: 12px;
    background: var(--dm-raised);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
}
.dept-map-description-card p {
    margin: 0;
    font-size: 0.875rem;
    color: var(--dm-ink-secondary);
    line-height: 1.6;
    white-space: pre-wrap;
    word-break: break-word;
}
.dept-map-progress-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.dept-map-progress-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--dm-ink-secondary);
}
.dept-map-progress-value {
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--dm-ink);
    font-family: "Plus Jakarta Sans", sans-serif;
}
.dept-map-progress-track {
    height: 10px;
    background: var(--dm-line);
    border-radius: 100px;
    overflow: hidden;
    position: relative;
}
.dept-map-progress-fill {
    height: 100%;
    border-radius: 100px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}
.dept-map-progress-fill::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.25) 50%, transparent 100%);
    animation: shimmer 2s ease-in-out infinite;
}
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
@media (prefers-reduced-motion: reduce) {
    .dept-map-progress-fill::after { animation: none; }
}

/* ===== VIEW ALL BUTTON ===== */
.dept-map-viewall {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 18px;
    gap: 8px;
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 800;
    color: var(--dm-ink-secondary);
    background: linear-gradient(135deg, var(--dm-raised), var(--dm-surface));
    border: 1px solid var(--dm-line-strong);
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
}
.dept-map-viewall:hover {
    background: var(--dm-surface);
    border-color: #f59e0b;
    color: #d97706;
    box-shadow: var(--dm-shadow-sm);
    transform: translateY(-1px);
}
.dept-map-viewall svg { width: 18px; height: 18px; }

/* ===== EMPTY / LOADING STATES ===== */
.dept-map-empty,
.dept-map-loading {
    text-align: center;
    padding: 56px 24px;
}
.dept-map-empty-icon,
.dept-map-loading-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    border-radius: var(--dm-radius-xs);
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #b45309;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px -2px rgba(245, 158, 11, 0.2);
}
.dark .dept-map-empty-icon,
html.dark-mode .dept-map-empty-icon,
.dark .dept-map-loading-icon,
html.dark-mode .dept-map-loading-icon {
    background: linear-gradient(135deg, rgba(251,191,36,0.15), rgba(251,191,36,0.08));
    color: #fbbf24;
}
.dept-map-empty-icon svg,
.dept-map-loading-icon svg { width: 32px; height: 32px; }
.dept-map-empty h4 {
    font-family: "Plus Jakarta Sans", sans-serif;
    font-size: 1.0625rem;
    font-weight: 800;
    color: var(--dm-ink);
    margin-bottom: 6px;
}
.dept-map-empty p,
.dept-map-loading p {
    font-size: 0.8125rem;
    color: var(--dm-muted);
}
.dept-map-loading .spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(245,158,11,0.2);
    border-top-color: #f59e0b;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 12px;
}
@keyframes spin { to { transform: rotate(360deg); } }
@media (prefers-reduced-motion: reduce) {
    .dept-map-loading .spinner { animation: none; border-top-color: transparent; }
}

/* ===== LEAFLET CUSTOMIZATION ===== */
.leaflet-popup-content-wrapper {
    border-radius: 12px;
    padding: 0;
    overflow: hidden;
}
.leaflet-popup-content {
    margin: 12px 16px;
    font-family: "Inter", sans-serif;
}
.custom-project-popup .project-popup-title { margin: 0 0 6px; font-family: "Plus Jakarta Sans", sans-serif; font-size: 0.9375rem; font-weight: 800; color: #1e293b; }
.custom-project-popup .project-popup-status { font-size: 0.75rem; font-weight: 700; color: #64748b; }
.custom-project-popup .project-popup-details { font-size: 0.75rem; color: #64748b; line-height: 1.5; }
.dark .leaflet-container,
html.dark-mode .leaflet-container { background: #0f0e1a; }
.dark .leaflet-popup-content-wrapper,
html.dark-mode .leaflet-popup-content-wrapper,
.dark .leaflet-popup-tip,
html.dark-mode .leaflet-popup-tip {
    background: #1e293b;
    color: #f8fafc;
    box-shadow: 0 8px 24px rgba(0,0,0,0.5);
}
.dark .custom-project-popup .project-popup-title,
html.dark-mode .custom-project-popup .project-popup-title { color: #f8fafc; }
.dark .custom-project-popup .project-popup-status,
html.dark-mode .custom-project-popup .project-popup-status,
.dark .custom-project-popup .project-popup-details,
html.dark-mode .custom-project-popup .project-popup-details { color: #94a3b8; }
.dark .leaflet-container a.leaflet-popup-close-button,
html.dark-mode .leaflet-container a.leaflet-popup-close-button { color: #cbd5e1; }

/* ===== ANIMATIONS ===== */
@keyframes dmFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.dept-animate {
    animation: dmFadeUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0;
}
.dept-animate:nth-child(1) { animation-delay: 0.04s; }
.dept-animate:nth-child(2) { animation-delay: 0.08s; }
.dept-animate:nth-child(3) { animation-delay: 0.12s; }
.dept-animate:nth-child(4) { animation-delay: 0.16s; }
.dept-animate:nth-child(5) { animation-delay: 0.20s; }

@media (prefers-reduced-motion: reduce) {
    .dept-animate { animation: none; opacity: 1; }
}
</style>

<div class="dept-map-container {{ ($mapTheme ?? null) === 'engineering' ? 'engineering-map-theme' : '' }}">

    <!-- HERO HEADER -->
    <div class="dept-map-hero dept-animate">
        <div class="dept-map-hero-content">
            <div class="dept-map-hero-badge-row">
                <span class="dept-map-hero-badge gold">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Cabuyao City, Laguna
                </span>
                <span class="dept-map-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Interactive Map View
                </span>
            </div>
            <h1 class="dept-map-hero-title">{{ $mapTitle ?? 'Department Map' }}</h1>
            <div class="dept-map-hero-subtitle">
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

    <!-- FLOATING STATS BAR -->
    <div class="dept-map-stats-bar dept-animate" id="mapStatsBar">
        <div class="dept-map-stat-item">
            <div class="dept-map-stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6.75M9 12h6.75M9 17.25h6.75"/></svg>
            </div>
            <div>
                <div class="dept-map-stat-label">Total Projects</div>
                <div class="dept-map-stat-value" id="statTotalProjects">—</div>
            </div>
        </div>
        <div class="dept-map-stat-item">
            <div class="dept-map-stat-icon emerald">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
            </div>
            <div>
                <div class="dept-map-stat-label">Avg Reported Progress</div>
                <div class="dept-map-stat-value" id="statAvgProgress">—</div>
            </div>
        </div>
        <div class="dept-map-stat-item">
            <div class="dept-map-stat-icon amber">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="dept-map-stat-label">Total Budget</div>
                <div class="dept-map-stat-value" id="statTotalBudget">—</div>
            </div>
        </div>
        <div class="dept-map-stat-item">
            <div class="dept-map-stat-icon purple">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
            </div>
            <div>
                <div class="dept-map-stat-label">Barangays</div>
                <div class="dept-map-stat-value" id="statBarangays">—</div>
            </div>
        </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="dept-map-layout">

        <!-- MAP -->
        <div class="dept-map-card dept-animate">
            <div class="dept-map-wrap" id="map"></div>

            <!-- Legend moved outside Leaflet container -->
            <div class="dept-map-legend">
                @include('components.map-status-legend')
            </div>

            <div class="dept-map-overlay">
                <button type="button" class="dept-map-overlay-btn active" id="btnLightTiles" title="Light map" aria-pressed="true">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    Light
                </button>
                <button type="button" class="dept-map-overlay-btn" id="btnDarkTiles" title="Dark map" aria-pressed="false">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.598.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    Dark
                </button>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="dept-map-sidebar dept-animate">
            <div class="dept-map-sidebar-header">
                <h2>{{ $projectsTitle ?? 'Department Projects' }}</h2>
                <p>Cabuyao City Projects</p>
                <div id="departmentSidebarAction"></div>
            </div>
            <div class="dept-map-sidebar-body" id="departmentProjectList">
                <!-- Loading state inserted by JS -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ============================================================
       UTILITIES
       ============================================================ */
    function e(str) {
        if (str == null) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatCurrency(value) {
        return '₱' + Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
    }

    function isMobile() { return window.innerWidth < 768; }

    /* ============================================================
       STATE
       ============================================================ */
    const projectList = document.getElementById('departmentProjectList');
    const sidebarAction = document.getElementById('departmentSidebarAction');
    let selectedProjectIndex = null;
    let map = null;
    let boundedArea = null;
    let projectFeatures = [];
    let barangayLayer = null;
    let selectedBarangayLayer = null;
    let selectedBarangayName = null;
    const barangayMarkerGroups = {};
    let allMarkers = L.featureGroup();
    let currentTileLayer = null;
    let lightTiles = null;
    let darkTiles = null;

    /* ============================================================
       PROGRESS & STATUS HELPERS
       ============================================================ */
    function calculateProgress(project) {
        const reportedProgress = calculateReportedProgress(project);
        if (reportedProgress !== null) {
            return reportedProgress;
        }

        return 0;
    }

    function calculateReportedProgress(project) {
        const rp = project.properties.progress_percentage;
        return (rp !== null && rp !== undefined && rp !== '')
            ? Math.min(100, Math.max(0, Number(rp)))
            : null;
    }

    function getStatusColor(status) {
        const map = {
            'Proposed': '#2563eb', 'For bidding': '#f59e0b',
            'Bidding ongoing': '#06b6d4', 'Award of contract': '#8b5cf6',
            'Implementation': '#0f766e', 'Completed': '#16a34a',
            'On Hold': '#dc2626', 'Cancelled': '#64748b'
        };
        return map[status] || '#64748b';
    }

    function getStatusBadgeClass(status) {
        const map = {
            'Proposed': 'proposed', 'Planning': 'proposed',
            'For bidding': 'bidding', 'Procurement': 'bidding',
            'Bidding ongoing': 'bidding_ongoing', 'Award of contract': 'award',
            'Bidding - Success': 'award', 'Implementation': 'implementation',
            'On Going': 'implementation', 'Completed': 'completed',
            'On Hold': 'onhold', 'Cancelled': 'cancelled'
        };
        return map[status] || 'proposed';
    }

    function barangayColor(name) {
        let hash = 0;
        for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
        const hue = Math.abs(hash) % 360;
        return 'hsl(' + hue + ', 65%, 55%)';
    }

    /* ============================================================
       STATS
       ============================================================ */
    function updateStatsBar(projects) {
        const total = projects.length;
        const totalBudget = projects.reduce(function(sum, p) { return sum + Number(p.properties.budget || 0); }, 0);
        const reportedProgresses = projects
            .map(calculateReportedProgress)
            .filter(function(progress) { return progress !== null; });
        const avgProgress = reportedProgresses.length > 0
            ? reportedProgresses.reduce(function(sum, progress) { return sum + progress; }, 0) / reportedProgresses.length
            : 0;
        const barangays = new Set(projects.map(function(p) { return p.properties.barangay; }).filter(Boolean));

        document.getElementById('statTotalProjects').textContent = total;
        document.getElementById('statAvgProgress').textContent = avgProgress.toFixed(1) + '%';
        document.getElementById('statTotalBudget').textContent = formatCurrency(totalBudget);
        document.getElementById('statBarangays').textContent = barangays.size;
    }

    /* ============================================================
       RENDERERS
       ============================================================ */
    function renderLifecycleStepper(status) {
        const steps = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed'];
        const stageByStatus = {
            Proposed: 0, 'For bidding': 1, 'Bidding ongoing': 2,
            'Award of contract': 3, Implementation: 4, Completed: 5,
            Planning: 0, Procurement: 1, 'Bidding - Success': 3, 'On Going': 4
        };
        const activeStep = stageByStatus[status];
        const halted = status === 'On Hold' || status === 'Cancelled';

        let html = '<div class="dept-map-stepper"><div class="dept-map-stepper-header"><span class="dept-map-stepper-title">Project lifecycle</span><span class="dept-map-stepper-status">' + e(status || 'Unknown') + '</span></div><div class="dept-map-stepper-track">';

        steps.forEach(function(step, idx) {
            let dotClass = '';
            let labelClass = '';
            let dotContent = String(idx + 1);

            if (halted) {
                if (idx === activeStep) { dotClass = 'halted'; labelClass = 'halted'; dotContent = '&#10007;'; }
            } else {
                if (activeStep !== undefined && idx < activeStep) { dotClass = 'completed'; labelClass = 'completed'; dotContent = '&#10003;'; }
                else if (activeStep !== undefined && idx === activeStep) { dotClass = 'active'; labelClass = 'active'; }
            }

            html += '<div class="dept-map-step ' + dotClass + '"><div class="dept-map-step-dot">' + dotContent + '</div><div class="dept-map-step-label">' + e(step) + '</div></div>';
        });
        html += '</div></div>';
        return html;
    }

    function renderProjectCard(project, index, isSingle) {
        const props = project.properties;
        const reportedProgress = calculateReportedProgress(project);
        const progress = calculateProgress(project);
        const allocatedBudget = Number(props.budget || 0);
        const expenditure = Number(props.actual_budget || 0);
        const expenditureProgress = allocatedBudget > 0 ? Math.min(100, Math.max(0, (expenditure / allocatedBudget) * 100)) : 0;
        const startDate = props.start_date ? new Date(props.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
        const targetDate = props.target_end_date ? new Date(props.target_end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
        const statusClass = getStatusBadgeClass(props.status);
        const isSelected = selectedProjectIndex === index;

        const imageHtml = props.image
            ? '<div class="dept-map-project-image-wrap"><img src="' + e(props.image) + '" alt="' + e(props.name) + '" class="dept-map-project-image" loading="lazy"><div class="dept-map-project-image-overlay"></div></div>'
            : '<div class="dept-map-project-noimage"><svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M21 21h-5.25M3 21h18M12.75 7.5a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>No image</div>';

        if (isSingle) {
            return '<div class="dept-map-detail-card" data-index="' + index + '">' +
                '<div class="dept-map-detail-header">' +
                    '<div class="dept-map-detail-header-label">Selected project</div>' +
                    '<h3>' + e(props.name) + '</h3>' +
                    '<span class="dept-map-status-badge ' + statusClass + '" style="margin-top:10px;"><span class="dot"></span>' + e(props.status || 'Unknown') + '</span>' +
                '</div>' +
                '<div class="dept-map-detail-image-wrap">' +
                    (props.image ? '<img src="' + e(props.image) + '" alt="' + e(props.name) + '" class="dept-map-detail-image" loading="lazy">' : '<div class="dept-map-project-noimage" style="border-radius:10px;height:220px;"><svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M21 21h-5.25M3 21h18M12.75 7.5a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg></div>') +
                '</div>' +
                '<div class="dept-map-detail-body">' +
                    renderLifecycleStepper(props.status) +
                    '<div class="dept-map-detail-grid">' +
                        '<div class="dept-map-detail-item"><div class="dept-map-detail-icon purple"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></div><div><div class="dept-map-detail-label">Barangay</div><div class="dept-map-detail-value">' + e(props.barangay || 'Not specified') + '</div></div></div>' +
                        '<div class="dept-map-detail-item"><div class="dept-map-detail-icon emerald"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="dept-map-detail-label">Budget</div><div class="dept-map-detail-value">' + formatCurrency(allocatedBudget) + '</div></div></div>' +
                        '<div class="dept-map-detail-item"><div class="dept-map-detail-icon blue"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div><div><div class="dept-map-detail-label">Reported Progress</div><div class="dept-map-detail-value">' + (reportedProgress !== null ? reportedProgress.toFixed(1) + '%' : 'Not reported') + '</div></div></div>' +
                        '<div class="dept-map-detail-item"><div class="dept-map-detail-icon rose"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><div class="dept-map-detail-label">Expenditure</div><div class="dept-map-detail-value">' + formatCurrency(expenditure) + '</div></div></div>' +
                    '</div>' +
                    '<div class="dept-map-progress-wrap">' +
                        '<div class="dept-map-progress-header"><span class="dept-map-progress-label">Expenditure progress</span><span class="dept-map-progress-value">' + expenditureProgress.toFixed(1) + '%</span></div>' +
                        '<div class="dept-map-progress-track"><div class="dept-map-progress-fill" style="width:' + expenditureProgress + '%;background:linear-gradient(90deg,#10b981,#059669);"></div></div>' +
                    '</div>' +
                    '<div class="dept-map-progress-wrap">' +
                        '<div class="dept-map-progress-header"><span class="dept-map-progress-label">' + (reportedProgress !== null ? 'Reported progress' : 'Progress') + '</span><span class="dept-map-progress-value">' + progress.toFixed(1) + '%</span></div>' +
                        '<div class="dept-map-progress-track"><div class="dept-map-progress-fill" style="width:' + progress + '%;background:linear-gradient(90deg,#3b82f6,#2563eb);"></div></div>' +
                        '<div style="display:flex;justify-content:space-between;margin-top:6px;font-size:0.6875rem;font-weight:600;color:var(--dm-muted);"><span>Start: ' + e(startDate) + '</span><span>Target: ' + e(targetDate) + '</span></div>' +
                    '</div>' +
                    '<div class="dept-map-description-card"><p>' + e(props.description || 'No description available.') + '</p></div>' +
                    '<button type="button" class="dept-map-viewall show-all-projects-btn">' +
                        '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>' +
                        'View all projects' +
                    '</button>' +
                '</div>' +
            '</div>';
        }

        return '<div class="dept-map-project-card ' + (isSelected ? 'selected' : '') + '" data-index="' + index + '" tabindex="0" role="button" aria-pressed="' + (isSelected ? 'true' : 'false') + '">' +
            imageHtml +
            '<div class="dept-map-project-body">' +
                '<div class="dept-map-project-name">' + e(props.name) + '</div>' +
                '<div class="dept-map-project-meta">' +
                    '<span class="dept-map-status-badge ' + statusClass + '"><span class="dot"></span>' + e(props.status || 'Unknown') + '</span>' +
                    '<span class="dept-map-status-badge" style="background:var(--dm-raised);color:var(--dm-muted);border:1px solid var(--dm-line);">' +
                        '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>' +
                        e(props.barangay || 'Barangay not specified') +
                    '</span>' +
                '</div>' +
                '<div class="dept-map-project-stats">' +
                    '<div class="dept-map-project-stat"><div class="dept-map-project-stat-label">Budget</div><div class="dept-map-project-stat-value">' + formatCurrency(props.budget) + '</div></div>' +
                    '<div class="dept-map-project-stat"><div class="dept-map-project-stat-label">Progress</div><div class="dept-map-project-stat-value">' + progress.toFixed(1) + '%</div></div>' +
                '</div>' +
                '<p class="dept-map-project-desc">' + e(props.description || 'No description available.') + '</p>' +
            '</div>' +
        '</div>';
    }

    /* ============================================================
       SIDEBAR ACTIONS
       ============================================================ */
    function updateSidebarAction() {
        if (!sidebarAction) return;
        if (selectedBarangayName) {
            sidebarAction.innerHTML = '<button type="button" id="backToAllBarangays" class="dept-map-back-btn">' +
                '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>' +
                'Back to all barangays</button>';
            document.getElementById('backToAllBarangays').addEventListener('click', resetToAllBarangays);
        } else {
            sidebarAction.innerHTML = '';
        }
    }

    function clearSelection() {
        document.querySelectorAll('.dept-map-project-card').forEach(function(card) {
            card.classList.remove('selected');
            card.setAttribute('aria-pressed', 'false');
        });
    }

    function highlightProject(index) {
        selectedProjectIndex = index;
        clearSelection();
        const card = document.querySelector('.dept-map-project-card[data-index="' + index + '"]');
        if (card) {
            card.classList.add('selected');
            card.setAttribute('aria-pressed', 'true');
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function attachCardListeners() {
        document.querySelectorAll('.dept-map-project-card').forEach(function(card) {
            card.addEventListener('click', function() {
                const idx = parseInt(this.getAttribute('data-index'), 10);
                selectProject(projectFeatures[idx], idx);
            });
            card.addEventListener('keydown', function(ev) {
                if (ev.key === 'Enter' || ev.key === ' ') {
                    ev.preventDefault();
                    const idx = parseInt(this.getAttribute('data-index'), 10);
                    selectProject(projectFeatures[idx], idx);
                }
            });
        });
        document.querySelectorAll('.show-all-projects-btn').forEach(function(btn) {
            btn.addEventListener('click', function(ev) {
                ev.stopPropagation();
                showAllProjects();
            });
        });
    }

    function renderProjectList(projects) {
        const isSingle = projects.length === 1;
        updateSidebarAction();

        if (projects.length === 0) {
            projectList.innerHTML = '<div class="dept-map-empty"><div class="dept-map-empty-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div><h4>No projects found</h4><p>No public projects recorded in ' + e(selectedBarangayName || 'this barangay') + ' yet.</p></div>';
            updateStatsBar(projects);
            return;
        }

        projectList.innerHTML = projects.map(function(project) {
            return renderProjectCard(project, project.originalIndex, isSingle);
        }).join('');

        attachCardListeners();
        updateStatsBar(projects);
    }

    function showAllProjects() {
        selectedProjectIndex = null;
        const activeList = selectedBarangayName
            ? projectFeatures.filter(function(p) { return p.properties.barangay === selectedBarangayName; })
            : projectFeatures;
        renderProjectList(activeList);
        if (map && boundedArea && !selectedBarangayName) {
            map.fitBounds(boundedArea, { padding: [24, 24], animate: true, duration: 0.7, easeLinearity: 0.3 });
        }
    }

    function selectProject(project, index) {
        highlightProject(index);
        renderProjectList([project]);
        if (map && project && project.geometry && project.geometry.coordinates) {
            const c = project.geometry.coordinates;
            map.flyTo([c[1], c[0]], 15, { duration: 0.7, easeLinearity: 0.35 });
        }
    }

    function resetToAllBarangays() {
        if (selectedBarangayLayer) {
            barangayLayer.resetStyle(selectedBarangayLayer);
            selectedBarangayLayer = null;
        }
        selectedBarangayName = null;
        if (map) {
            Object.values(barangayMarkerGroups).forEach(function(g) { map.removeLayer(g); });
            map.addLayer(allMarkers);
        }
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
        Object.values(barangayMarkerGroups).forEach(function(g) { map.removeLayer(g); });
        if (barangayMarkerGroups[name]) map.addLayer(barangayMarkerGroups[name]);

        renderProjectList(projectFeatures.filter(function(p) { return p.properties.barangay === name; }));
    }

    /* ============================================================
       TILE LAYER TOGGLE
       ============================================================ */
    function setTileLayer(dark) {
        if (!map) return;
        if (currentTileLayer) map.removeLayer(currentTileLayer);
        const btnLight = document.getElementById('btnLightTiles');
        const btnDark = document.getElementById('btnDarkTiles');
        const card = document.querySelector('.dept-map-card');

        if (dark) {
            if (!darkTiles) {
                darkTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap &copy; CARTO', maxZoom: 19, subdomains: 'abcd'
                });
            }
            currentTileLayer = darkTiles;
            btnDark.classList.add('active');
            btnDark.setAttribute('aria-pressed', 'true');
            btnLight.classList.remove('active');
            btnLight.setAttribute('aria-pressed', 'false');
            if (card) card.style.background = '#141321';
        } else {
            if (!lightTiles) {
                lightTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors', maxZoom: 19
                });
            }
            currentTileLayer = lightTiles;
            btnLight.classList.add('active');
            btnLight.setAttribute('aria-pressed', 'true');
            btnDark.classList.remove('active');
            btnDark.setAttribute('aria-pressed', 'false');
            if (card) card.style.background = '';
        }
        currentTileLayer.addTo(map);
    }

    document.getElementById('btnLightTiles').addEventListener('click', function() { setTileLayer(false); });
    document.getElementById('btnDarkTiles').addEventListener('click', function() { setTileLayer(true); });

    /* ============================================================
       LOADING STATE
       ============================================================ */
    projectList.innerHTML = '<div class="dept-map-loading"><div class="spinner"></div><p>Loading projects…</p></div>';

    /* ============================================================
       DATA FETCHING (Parallel)
       ============================================================ */
    const geojsonUrl = '{{ asset('data/cabuyao-map.geojson') }}';
    const projectsUrl = '{{ url('/api/projects/geojson') }}';

    Promise.all([
        fetch(geojsonUrl).then(function(r) { if (!r.ok) throw new Error('Map fetch failed: ' + r.status); return r.json(); }),
        fetch(projectsUrl).then(function(r) { if (!r.ok) throw new Error('Project fetch failed: ' + r.status); return r.json(); })
    ])
    .then(function(results) {
        const geojson = results[0];
        const projectData = results[1];
        initMap(geojson, projectData);
    })
    .catch(function(error) {
        console.error(error);
        projectList.innerHTML = '<div class="dept-map-empty"><div class="dept-map-empty-icon" style="background:linear-gradient(135deg,rgba(239,68,68,0.15),rgba(239,68,68,0.08));color:#dc2626;"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg></div><h4>Unable to load data</h4><p>' + e(error.message || 'Please try refreshing the page.') + '</p></div>';
    });

    /* ============================================================
       MAP INITIALIZATION
       ============================================================ */
    function initMap(geojson, projectData) {
        const cabuyaoBounds = L.geoJSON(geojson).getBounds();
        boundedArea = cabuyaoBounds.pad(0.02);
        map = L.map('map', { maxBounds: boundedArea, maxBoundsViscosity: 1.0 });

        lightTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'OpenStreetMap contributors', maxZoom: 19
        });
        currentTileLayer = lightTiles;
        lightTiles.addTo(map);

        barangayLayer = L.geoJSON(geojson, {
            style: function(feature) {
                return {
                    fillColor: barangayColor(feature.properties.name),
                    fillOpacity: 0.35,
                    color: '#ffffff',
                    weight: 1.5,
                };
            },
            onEachFeature: function(feature, layer) {
                const name = feature.properties.name;
                layer.bindTooltip(e(name), { sticky: true, className: 'barangay-tooltip' });
                layer.on({
                    mouseover: function(e) {
                        if (layer !== selectedBarangayLayer) e.target.setStyle({ fillOpacity: 0.6, weight: 2.5 });
                    },
                    mouseout: function(e) {
                        if (layer !== selectedBarangayLayer) barangayLayer.resetStyle(e.target);
                    },
                    click: function() { selectBarangayOnMap(layer, name); }
                });
            },
        }).addTo(map);

        allMarkers = L.featureGroup();
        projectFeatures = [];

        if (projectData && projectData.features) {
            projectData.features.forEach(function(project, index) {
                const coords = project.geometry && project.geometry.coordinates;
                if (!coords || coords.length < 2) return;

                const marker = L.circleMarker([coords[1], coords[0]], {
                    radius: 14,
                    fillColor: getStatusColor(project.properties.status),
                    color: '#ffffff',
                    weight: 3,
                    opacity: 1,
                    fillOpacity: 0.9
                });

                const popupHtml = '<div style="font-family:\'Inter\',sans-serif;min-width:180px;">' +
                    '<h4 class="project-popup-title">' + e(project.properties.name) + '</h4>' +
                    '<div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">' +
                        '<span style="width:8px;height:8px;border-radius:50%;background:' + e(getStatusColor(project.properties.status)) + ';display:inline-block;"></span>' +
                        '<span class="project-popup-status">' + e(project.properties.status || 'Unknown') + '</span>' +
                    '</div>' +
                    '<div class="project-popup-details">' +
                        '<div style="margin-bottom:2px;"><strong>Budget:</strong> ' + formatCurrency(project.properties.budget) + '</div>' +
                        '<div><strong>Barangay:</strong> ' + e(project.properties.barangay || 'Citywide') + '</div>' +
                    '</div>' +
                '</div>';

                marker.bindPopup(popupHtml, { borderRadius: 12, className: 'custom-project-popup' });
                marker.on('click', function(ev) {
                    L.DomEvent.stopPropagation(ev);
                    map.flyTo([coords[1], coords[0]], 16, { duration: 0.7, easeLinearity: 0.35 });
                    selectProject(project, index);
                });

                allMarkers.addLayer(marker);
                projectFeatures.push(Object.assign({ originalIndex: index }, project));

                const barangayName = project.properties.barangay;
                if (barangayName) {
                    if (!barangayMarkerGroups[barangayName]) barangayMarkerGroups[barangayName] = L.layerGroup();
                    barangayMarkerGroups[barangayName].addLayer(marker);
                }
            });
        }

        map.on('click', function() {
            if (selectedProjectIndex !== null) {
                showAllProjects();
            }
        });

        allMarkers.addTo(map);
        renderProjectList(projectFeatures);

        map.fitBounds(boundedArea, { padding: [24, 24] });
        map.setMaxBounds(boundedArea);
        map.setMinZoom(map.getZoom());
        setTimeout(function() { map.invalidateSize(); }, 100);

        window.addEventListener('resize', function() {
            if (map) setTimeout(function() { map.invalidateSize(); }, 100);
        });
    }
});
</script>
@endsection
