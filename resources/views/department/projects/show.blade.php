@extends('layouts.department')

@section('content')

@php
    $currentRole = auth()->user()?->role_slug ?? 'public';
@endphp

<style>
    .dept-show-container {
        --ds-bg: #f8f7f5;
        --ds-surface: #ffffff;
        --ds-raised: #fafaf9;
        --ds-ink: #1e1b4b;
        --ds-ink-secondary: #374151;
        --ds-muted: #9ca3af;
        --ds-line: rgba(0,0,0,0.06);
        --ds-line-strong: rgba(0,0,0,0.12);
        --ds-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --ds-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --ds-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --ds-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --ds-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --ds-radius: 16px;
        --ds-radius-sm: 12px;
        --ds-radius-xs: 10px;
    }
    .dark .dept-show-container {
        --ds-bg: #0f0e1a;
        --ds-surface: #1a1929;
        --ds-raised: #222136;
        --ds-ink: #f8fafc;
        --ds-ink-secondary: #cbd5e1;
        --ds-muted: #64748b;
        --ds-line: rgba(255,255,255,0.06);
        --ds-line-strong: rgba(255,255,255,0.12);
        --ds-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
        --ds-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.4), 0 1px 2px -1px rgb(0 0 0 / 0.4);
        --ds-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
        --ds-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
        --ds-shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.6), 0 8px 10px -6px rgb(0 0 0 / 0.5);
    }

    .dept-show-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
        background: var(--ds-bg);
        color: var(--ds-ink);
        transition: background 0.3s, color 0.3s;
    }
    @media (min-width: 640px) { .dept-show-container { padding: 32px; } }
    @media (min-width: 1024px) { .dept-show-container { padding: 40px; } }

    /* Hero */
    .dept-show-hero {
        position: relative;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        border-radius: var(--ds-radius);
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: var(--ds-shadow-xl);
        overflow: hidden;
    }
    @media (min-width: 640px) { .dept-show-hero { padding: 40px; } }
    .dept-show-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(245,158,11,0.12) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 40%);
        pointer-events: none;
    }
    .dept-show-hero-content { position: relative; z-index: 1; }
    .dept-show-hero-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .dept-show-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        color: rgba(255,255,255,0.9);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .dept-show-hero-badge svg { width: 14px; height: 14px; }
    .dept-show-hero-title {
        font-family: "Plus Jakarta Sans", "Inter", sans-serif;
        font-size: clamp(1.5rem, 3.5vw, 2.25rem);
        font-weight: 800;
        color: white;
        line-height: 1.2;
        letter-spacing: -0.02em;
        margin-bottom: 8px;
    }
    .dept-show-hero-subtitle {
        font-size: 0.9375rem;
        color: rgba(255,255,255,0.7);
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .dept-show-hero-subtitle span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .dept-show-hero-subtitle svg { width: 16px; height: 16px; opacity: 0.7; }

    .dept-show-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 100px;
        font-size: 0.875rem;
        font-weight: 700;
        background: rgba(59, 130, 246, 0.25);
        color: #bfdbfe;
        border: 1px solid rgba(59, 130, 246, 0.3);
        backdrop-filter: blur(8px);
    }
    .dept-show-status::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.3);
    }

    /* Stepper */
    .dept-show-stepper {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        padding: 20px 24px;
        background: var(--ds-surface);
        border: 1px solid var(--ds-line);
        border-radius: var(--ds-radius-sm);
        box-shadow: var(--ds-shadow-sm);
        overflow-x: auto;
    }

    /* Alert */
    .dept-show-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 20px;
        border-radius: var(--ds-radius-xs);
        margin-bottom: 24px;
    }
    .dept-show-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
    }
    .dark .dept-show-alert-error { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.2); }
    .dept-show-alert-icon { width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; }
    .dept-show-alert-error .dept-show-alert-icon { color: #dc2626; }
    .dept-show-alert p { font-size: 0.875rem; color: var(--ds-ink-secondary); }

    /* Main grid */
    .dept-show-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    @media (min-width: 1024px) {
        .dept-show-grid { grid-template-columns: 1fr 360px; align-items: start; }
    }

    /* Card */
    .dept-show-card {
        background: var(--ds-surface);
        border-radius: var(--ds-radius-sm);
        border: 1px solid var(--ds-line);
        box-shadow: var(--ds-shadow-sm);
        overflow: hidden;
        margin-bottom: 24px;
        transition: background 0.3s, border-color 0.3s;
    }
    .dept-show-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--ds-line);
    }
    .dept-show-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dept-show-card-icon.blue { background: #dbeafe; color: #2563eb; }
    .dept-show-card-icon.emerald { background: #d1fae5; color: #059669; }
    .dept-show-card-icon.purple { background: #ede9fe; color: #7c3aed; }
    .dept-show-card-icon.amber { background: #fef3c7; color: #b45309; }
    .dept-show-card-icon.rose { background: #ffe4e6; color: #e11d48; }
    .dept-show-card-icon.gray { background: #f3f4f6; color: #4b5563; }
    .dark .dept-show-card-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dark .dept-show-card-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
    .dark .dept-show-card-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
    .dark .dept-show-card-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .dark .dept-show-card-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
    .dark .dept-show-card-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
    .dept-show-card-header h2 {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--ds-ink);
    }
    .dept-show-card-header p {
        font-size: 0.75rem;
        color: var(--ds-muted);
        margin-top: 2px;
    }
    .dept-show-card-body { padding: 24px; }

    /* Detail grid */
    .dept-detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }
    @media (min-width: 640px) {
        .dept-detail-grid { grid-template-columns: 1fr 1fr; }
    }
    .dept-detail-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        border-radius: var(--ds-radius-xs);
        border: 1px solid var(--ds-line);
        background: var(--ds-raised);
        transition: all 0.2s ease;
    }
    .dept-detail-item:hover {
        background: var(--ds-surface);
        border-color: var(--ds-line-strong);
        box-shadow: var(--ds-shadow-md);
        transform: translateY(-1px);
    }
    .dept-detail-item.full-width {
        grid-column: 1 / -1;
    }
    .dept-detail-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dept-detail-icon svg { width: 20px; height: 20px; }
    .dept-detail-icon.blue { background: #dbeafe; color: #2563eb; }
    .dept-detail-icon.emerald { background: #d1fae5; color: #059669; }
    .dept-detail-icon.amber { background: #fef3c7; color: #b45309; }
    .dept-detail-icon.rose { background: #ffe4e6; color: #e11d48; }
    .dept-detail-icon.purple { background: #ede9fe; color: #7c3aed; }
    .dept-detail-icon.gray { background: #f3f4f6; color: #4b5563; }
    .dark .dept-detail-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dark .dept-detail-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
    .dark .dept-detail-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .dark .dept-detail-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
    .dark .dept-detail-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
    .dark .dept-detail-icon.gray { background: rgba(107,114,128,0.15); color: #9ca3af; }
    .dept-detail-content { min-width: 0; }
    .dept-detail-label {
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ds-muted);
        margin-bottom: 4px;
    }
    .dept-detail-value {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--ds-ink);
        line-height: 1.4;
    }
    .dept-detail-value.muted {
        color: var(--ds-ink-secondary);
        font-weight: 500;
        line-height: 1.6;
    }

    /* Timeline */
    .dept-timeline {
        position: relative;
        padding-left: 28px;
    }
    .dept-timeline::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 4px;
        bottom: 4px;
        width: 2px;
        background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        border-radius: 2px;
        opacity: 0.3;
    }
    .dept-timeline-item {
        position: relative;
        padding-bottom: 24px;
    }
    .dept-timeline-item:last-child { padding-bottom: 0; }
    .dept-timeline-dot {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--ds-surface);
        border: 3px solid #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
    }
    .dept-timeline-item.completed .dept-timeline-dot {
        border-color: #10b981;
        background: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }
    .dept-timeline-content {
        background: var(--ds-raised);
        border: 1px solid var(--ds-line);
        border-radius: var(--ds-radius-xs);
        padding: 16px;
        transition: all 0.2s ease;
    }
    .dept-timeline-content:hover {
        background: var(--ds-surface);
        border-color: var(--ds-line-strong);
        box-shadow: var(--ds-shadow-md);
    }
    .dept-timeline-date {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--ds-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .dept-timeline-date svg { width: 14px; height: 14px; }
    .dept-timeline-progress {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #fef3c7;
        color: #b45309;
        margin-bottom: 8px;
    }
    .dark .dept-timeline-progress { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .dept-timeline-remarks {
        font-size: 0.875rem;
        color: var(--ds-ink-secondary);
        line-height: 1.5;
    }
    .dept-empty-state {
        text-align: center;
        padding: 40px 24px;
    }
    .dept-empty-state-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 12px;
        border-radius: 14px;
        background: #fef3c7;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .dark .dept-empty-state-icon { background: rgba(251,191,36,0.15); }
    .dept-empty-state-icon svg { width: 24px; height: 24px; }
    .dept-empty-state h4 {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--ds-ink);
        margin-bottom: 4px;
    }
    .dept-empty-state p {
        font-size: 0.8125rem;
        color: var(--ds-muted);
    }

    /* Sidebar */
    .dept-show-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    @media (min-width: 1024px) {
        .dept-show-sidebar { position: sticky; top: 24px; }
    }

    /* Progress mini */
    .dept-progress-mini {
        padding: 20px 24px;
    }
    .dept-progress-mini-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .dept-progress-mini-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--ds-muted);
    }
    .dept-progress-mini-value {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: #d97706;
    }
    .dept-progress-mini-track {
        height: 8px;
        background: var(--ds-raised);
        border-radius: 100px;
        overflow: hidden;
        border: 1px solid var(--ds-line);
    }
    .dept-progress-mini-fill {
        height: 100%;
        border-radius: 100px;
        background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
        box-shadow: 0 0 12px rgba(245, 158, 11, 0.3);
        transition: width 1s ease;
    }
    .dept-progress-mini-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 12px;
        font-size: 0.75rem;
        color: var(--ds-muted);
    }

    /* Forms grid */
    .dept-forms-grid {
        display: flex;
        gap: 14px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        overscroll-behavior-x: contain;
        padding: 2px 2px 12px;
        scrollbar-width: thin;
        scrollbar-color: var(--ds-line-strong) transparent;
    }
    .dept-forms-grid::-webkit-scrollbar { height: 8px; }
    .dept-forms-grid::-webkit-scrollbar-track { background: transparent; }
    .dept-forms-grid::-webkit-scrollbar-thumb {
        background: var(--ds-line-strong);
        border-radius: 100px;
    }
    .dept-form-card {
        flex: 0 0 min(100%, 360px);
        scroll-snap-align: start;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px;
        border-radius: var(--ds-radius-xs);
        border: 1px solid var(--ds-line);
        background: var(--ds-raised);
        transition: all 0.2s ease;
    }
    @media (min-width: 900px) {
        .dept-form-card { flex-basis: calc((100% - 14px) / 2); }
    }
    .dept-form-card:hover {
        background: var(--ds-surface);
        border-color: var(--ds-line-strong);
        box-shadow: var(--ds-shadow-md);
        transform: translateY(-1px);
    }
    .dept-form-card.completed { border-left: 3px solid #10b981; }
    .dept-form-card.pending { border-left: 3px solid var(--ds-muted); }
    .dept-form-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 0.75rem;
        font-weight: 800;
        flex-shrink: 0;
    }
    .dept-form-icon.completed {
        background: #d1fae5;
        color: #059669;
    }
    .dept-form-icon.pending {
        background: #f3f4f6;
        color: #9ca3af;
    }
    .dark .dept-form-icon.completed { background: rgba(16,185,129,0.15); color: #34d399; }
    .dark .dept-form-icon.pending { background: rgba(107,114,128,0.15); color: #9ca3af; }
    .dept-form-info { flex: 1; min-width: 0; }
    .dept-form-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--ds-ink);
        line-height: 1.3;
        margin-bottom: 6px;
    }
    .dept-form-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .dept-form-status.completed { color: #059669; }
    .dept-form-status.pending { color: var(--ds-muted); }
    .dept-form-status svg { width: 14px; height: 14px; }
    .dept-form-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }
    .dept-form-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }
    .dept-form-btn-primary {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    .dept-form-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 10px -2px rgba(245, 158, 11, 0.4); }
    .dept-form-btn-secondary {
        background: var(--ds-surface);
        color: var(--ds-ink-secondary);
        border: 1px solid var(--ds-line-strong);
    }
    .dept-form-btn-secondary:hover { background: var(--ds-raised); }
    .dept-form-btn svg { width: 14px; height: 14px; }

    .dept-forms-header-tools {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
    }
    .dept-forms-count {
        font-size: 0.6875rem;
        font-weight: 700;
        color: var(--ds-muted);
        white-space: nowrap;
    }
    .dept-forms-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid var(--ds-line-strong);
        border-radius: 8px;
        background: var(--ds-raised);
        color: var(--ds-ink-secondary);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .dept-forms-nav:hover:not(:disabled) {
        background: var(--ds-surface);
        border-color: #f59e0b;
        color: #d97706;
    }
    .dept-forms-nav:disabled { opacity: 0.35; cursor: not-allowed; }
    .dept-forms-nav svg { width: 16px; height: 16px; }
    @media (max-width: 639px) {
        .dept-show-card-header { align-items: flex-start; }
        .dept-forms-header-tools { flex-wrap: wrap; justify-content: flex-end; }
        .dept-forms-count { display: none; }
    }

    /* Page actions */
    .dept-show-actions {
        display: flex;
        flex-direction: column-reverse;
        gap: 12px;
        margin-top: 8px;
    }
    @media (min-width: 480px) {
        .dept-show-actions { flex-direction: row; justify-content: flex-end; }
    }
    .dept-show-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 28px;
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        border: none;
    }
    .dept-show-btn-primary {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.5);
    }
    .dept-show-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -4px rgba(245, 158, 11, 0.6);
    }
    .dept-show-btn-secondary {
        background: var(--ds-raised);
        color: var(--ds-ink-secondary);
        border: 1px solid var(--ds-line-strong);
    }
    .dept-show-btn-secondary:hover {
        background: var(--ds-surface);
        box-shadow: var(--ds-shadow-sm);
    }
    .dept-show-btn svg { width: 18px; height: 18px; }

    @keyframes dsFadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dept-animate {
        animation: dsFadeUp 0.4s ease forwards;
        opacity: 0;
    }
    .dept-animate:nth-child(1) { animation-delay: 0.03s; }
    .dept-animate:nth-child(2) { animation-delay: 0.06s; }
    .dept-animate:nth-child(3) { animation-delay: 0.09s; }
    .dept-animate:nth-child(4) { animation-delay: 0.12s; }
    .dept-animate:nth-child(5) { animation-delay: 0.15s; }
    .dept-animate:nth-child(6) { animation-delay: 0.18s; }

    @media (prefers-reduced-motion: reduce) {
        .dept-animate { animation: none; opacity: 1; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentRole = window.__currentRole || @json($currentRole);
        const pendingNotification = {{ Js::from(session('pending_notification') ?? null) }};
        const roleKeys = ['admin', 'department', 'city', 'barangay', 'public'];

        if (pendingNotification) {
            try {
                roleKeys.forEach((role) => {
                    const storageKey = 'projectTrackerNotifications:' + role;
                    const existing = JSON.parse(localStorage.getItem(storageKey) || '[]');

                    if (!existing.some(item => item.id === pendingNotification.id)) {
                        existing.unshift(pendingNotification);
                        localStorage.setItem(storageKey, JSON.stringify(existing));
                    }
                });

                window.dispatchEvent(new Event('notifications:updated'));
            } catch (error) {
                console.warn('Unable to store notification', error);
            }
        }

        const formsRail = document.querySelector('.dept-forms-grid');
        const formsPrevious = document.querySelector('[data-forms-previous]');
        const formsNext = document.querySelector('[data-forms-next]');

        if (formsRail && formsPrevious && formsNext) {
            const updateFormsNavigation = function() {
                formsPrevious.disabled = formsRail.scrollLeft <= 2;
                formsNext.disabled = formsRail.scrollLeft + formsRail.clientWidth >= formsRail.scrollWidth - 2;
            };
            const moveForms = function(direction) {
                formsRail.scrollBy({ left: direction * formsRail.clientWidth * 0.85, behavior: 'smooth' });
            };

            formsPrevious.addEventListener('click', () => moveForms(-1));
            formsNext.addEventListener('click', () => moveForms(1));
            formsRail.addEventListener('scroll', updateFormsNavigation, { passive: true });
            window.addEventListener('resize', updateFormsNavigation);
            updateFormsNavigation();
        }
    });
</script>

<div class="dept-show-container">

    <!-- HERO HEADER -->
    <div class="dept-show-hero dept-animate">
        <div class="dept-show-hero-content">
            <div class="dept-show-hero-meta">
                <span class="dept-show-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 3.75h15m-16.5 3.75h15m-16.5 3.75h15"/></svg>
                    {{ $project->project_code }}
                </span>
                <span class="dept-show-hero-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                    {{ $project->project_type }}
                </span>
                <span class="dept-show-status">{{ $project->current_status }}</span>
            </div>
            <h1 class="dept-show-hero-title">{{ $project->project_name }}</h1>
            <div class="dept-show-hero-subtitle">
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    {{ $project->barangay->barangay_name ?? 'Citywide' }}
                </span>
                <span>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ₱{{ number_format($project->approved_budget ?? 0, 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- STEPPER -->
    @include('components.project-stepper', ['project' => $project])

    <!-- ERROR ALERT -->
    @if (session('error'))
        <div class="dept-show-alert dept-show-alert-error dept-animate">
            <svg class="dept-show-alert-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- MAIN GRID -->
    <div class="dept-show-grid">

        <!-- LEFT COLUMN -->
        <div class="dept-show-main">

            <!-- Project Details -->
            <div class="dept-show-card dept-animate">
                <div class="dept-show-card-header">
                    <div class="dept-show-card-icon blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 00.063.853l.041.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </div>
                    <div>
                        <h2>Project Details</h2>
                        <p>Key information about this project.</p>
                    </div>
                </div>
                <div class="dept-show-card-body">
                    <div class="dept-detail-grid">
                        <div class="dept-detail-item">
                            <div class="dept-detail-icon blue">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A11.959 11.959 0 013.22 12c0-.778.099-1.533.284-2.253m0 0A8.959 8.959 0 0121 12a8.959 8.959 0 01-7.843 4.582"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Status</div>
                                <div class="dept-detail-value">{{ $project->current_status }}</div>
                            </div>
                        </div>
                        <div class="dept-detail-item">
                            <div class="dept-detail-icon purple">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Barangay</div>
                                <div class="dept-detail-value">{{ $project->barangay->barangay_name ?? 'Citywide' }}</div>
                            </div>
                        </div>
                        <div class="dept-detail-item">
                            <div class="dept-detail-icon emerald">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Approved Budget</div>
                                <div class="dept-detail-value">₱{{ number_format($project->approved_budget ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="dept-detail-item">
                            <div class="dept-detail-icon rose">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Actual Budget</div>
                                <div class="dept-detail-value">₱{{ number_format($project->actual_budget ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="dept-detail-item">
                            <div class="dept-detail-icon amber">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Start Date</div>
                                <div class="dept-detail-value">{{ $project->start_date?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dept-detail-item">
                            <div class="dept-detail-icon amber">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Target Completion</div>
                                <div class="dept-detail-value">{{ $project->target_end_date?->format('M d, Y') ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dept-detail-item full-width">
                            <div class="dept-detail-icon gray">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Location</div>
                                <div class="dept-detail-value muted">{{ $project->location_description ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="dept-detail-item full-width">
                            <div class="dept-detail-icon blue">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Public Description</div>
                                <div class="dept-detail-value muted">{{ $project->public_description ?? 'No public description available.' }}</div>
                            </div>
                        </div>
                        <div class="dept-detail-item full-width">
                            <div class="dept-detail-icon rose">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            </div>
                            <div class="dept-detail-content">
                                <div class="dept-detail-label">Internal Remarks (Private)</div>
                                <div class="dept-detail-value muted">{{ $project->remarks ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Updates -->
            <div class="dept-show-card dept-animate">
                <div class="dept-show-card-header">
                    <div class="dept-show-card-icon emerald">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <div>
                        <h2>Progress Updates</h2>
                        <p>Timeline of project milestones and progress reports.</p>
                    </div>
                </div>
                <div class="dept-show-card-body">
                    @if ($project->updates->isEmpty())
                        <div class="dept-empty-state">
                            <div class="dept-empty-state-icon">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                            </div>
                            <h4>No updates yet</h4>
                            <p>Progress updates will appear here once they are logged.</p>
                        </div>
                    @else
                        <div class="dept-timeline">
                            @foreach ($project->updates as $update)
                                <div class="dept-timeline-item {{ $loop->index < $project->updates->count() - 1 ? 'completed' : '' }}">
                                    <div class="dept-timeline-dot"></div>
                                    <div class="dept-timeline-content">
                                        <div class="dept-timeline-date">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                            {{ $update->update_date?->format('M d, Y') ?? '—' }}
                                        </div>
                                        <div class="dept-timeline-progress">{{ $update->progress_percentage }}% Complete</div>
                                        <div class="dept-timeline-remarks">{{ $update->remarks ?? '' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN (Sidebar) -->
        <div class="dept-show-sidebar">

            @php
                $startDate = \Carbon\Carbon::parse($project->start_date);
                $endDate = \Carbon\Carbon::parse($project->target_end_date);
                $today = \Carbon\Carbon::today();
                $totalDays = $startDate->diffInDays($endDate);
                $daysElapsed = $startDate->diffInDays($today);
                $timelineProgress = ($totalDays > 0) ? min(100, max(0, ($daysElapsed / $totalDays) * 100)) : 0;
                $reportedProgress = $project->latestUpdate?->progress_percentage;
                $progress = $reportedProgress !== null ? $reportedProgress : $timelineProgress;
            @endphp

            <!-- Progress Mini Card -->
            <div class="dept-show-card dept-animate">
                <div class="dept-progress-mini">
                    <div class="dept-progress-mini-header">
                        <span class="dept-progress-mini-label">Overall Progress</span>
                        <span class="dept-progress-mini-value">{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="dept-progress-mini-track">
                        <div class="dept-progress-mini-fill" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="dept-progress-mini-footer">
                        <span>Started {{ $project->start_date?->format('M d, Y') ?? '—' }}</span>
                        <span>{{ max(0, $today->diffInDays($endDate, false)) }} days remaining</span>
                    </div>
                </div>
            </div>

            <!-- Government Forms -->
            <div class="dept-show-card dept-animate">
                <div class="dept-show-card-header">
                    <div class="dept-show-card-icon purple">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div>
                        <h2>Government Forms</h2>
                        <p>Official documentation (Form 1–11)</p>
                    </div>
                    <div class="dept-forms-header-tools">
                        <span class="dept-forms-count">11 forms</span>
                        <button type="button" class="dept-forms-nav" data-forms-previous aria-label="Previous government forms" title="Previous forms">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" class="dept-forms-nav" data-forms-next aria-label="Next government forms" title="Next forms">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
                <div class="dept-show-card-body">
                    @php
                        $formList = [
                            'form_1' => 'Form 1 — Initial Project Report',
                            'form_2' => 'Form 2 — Physical and Financial Accomplishment Report',
                            'form_3' => 'Form 3 — Project Exception Report',
                            'form_4' => 'Form 4 — Project Results',
                            'form_5' => 'Form 5 — Summary of Financial and Physical Accomplishments',
                            'form_6' => 'Form 6 — Status of Projects with Implementation Problems',
                            'form_7' => 'Form 7 — Project Inspection Report',
                            'form_8' => 'Form 8 — Problem Solving Sessions/Facilitation Meeting',
                            'form_9' => 'Form 9 — Training/Workshop Conducted/Facilitated/Attended by the PMC',
                            'form_10' => 'Form 10 — RPMC and RDC Resolutions Related to RPMES',
                            'form_11' => 'Form 11 — Key Lessons Learned from Issues Resolved and Best Practices',
                        ];
                    @endphp

                    <div class="dept-forms-grid">
                        @foreach ($formList as $type => $label)
                            @php
                                $existingForm = $project->forms->firstWhere('form_type', $type);
                                $isAvailable = in_array($type, ['form_1', 'form_2', 'form_3', 'form_4', 'form_5', 'form_6', 'form_7', 'form_8', 'form_9', 'form_10', 'form_11'], true);
                                $formNumber = (int) str_replace('form_', '', $type);
                            @endphp
                            <div class="dept-form-card {{ $existingForm ? 'completed' : 'pending' }}">
                                <div class="dept-form-icon {{ $existingForm ? 'completed' : 'pending' }}">{{ $formNumber }}</div>
                                <div class="dept-form-info">
                                    <div class="dept-form-title">{{ $label }}</div>
                                    <div class="dept-form-status {{ $existingForm ? 'completed' : 'pending' }}">
                                        @if($existingForm)
                                            <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            Completed — {{ $existingForm->updated_at->format('M d, Y') }}
                                        @else
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $isAvailable ? 'Not filled out yet' : 'Coming soon' }}
                                        @endif
                                    </div>
                                    @if ($isAvailable)
                                        <div class="dept-form-actions">
                                            <a href="{{ route('department.projects.forms.edit', [$project->project_id, $type]) }}" class="dept-form-btn dept-form-btn-primary">{{ $existingForm ? 'View / Edit' : 'Fill Out' }}</a>
                                            @if ($existingForm)
                                                <a href="{{ route('department.projects.forms.pdf', [$project->project_id, $type]) }}" class="dept-form-btn dept-form-btn-secondary">PDF</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAGE ACTIONS -->
    <div class="dept-show-actions dept-animate">
        <a href="{{ route('department.projects.index') }}" class="dept-show-btn dept-show-btn-secondary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
            Back to List
        </a>
        <a href="{{ route('department.projects.edit', $project->project_id) }}" class="dept-show-btn dept-show-btn-primary">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
            Edit Project
        </a>
    </div>

</div>

@endsection