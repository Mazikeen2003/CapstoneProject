@extends('layouts.department')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<style>
    .dept-edit-container {
        --de-bg: #f8f7f5;
        --de-surface: #ffffff;
        --de-raised: #fafaf9;
        --de-ink: #1e1b4b;
        --de-ink-secondary: #374151;
        --de-muted: #9ca3af;
        --de-line: rgba(0,0,0,0.06);
        --de-line-strong: rgba(0,0,0,0.12);
        --de-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --de-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --de-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --de-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --de-radius-sm: 12px;
        --de-radius-xs: 10px;
    }
    .dark .dept-edit-container {
        --de-bg: #0f0e1a;
        --de-surface: #1a1929;
        --de-raised: #222136;
        --de-ink: #f8fafc;
        --de-ink-secondary: #cbd5e1;
        --de-muted: #64748b;
        --de-line: rgba(255,255,255,0.06);
        --de-line-strong: rgba(255,255,255,0.12);
        --de-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.3);
        --de-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.4), 0 1px 2px -1px rgb(0 0 0 / 0.4);
        --de-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4);
        --de-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.5), 0 4px 6px -4px rgb(0 0 0 / 0.5);
    }

    .dept-edit-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
        background: var(--de-bg);
        color: var(--de-ink);
        transition: background 0.3s, color 0.3s;
    }
    @media (min-width: 640px) { .dept-edit-container { padding: 32px; } }
    @media (min-width: 1024px) { .dept-edit-container { padding: 40px; } }

    /* Header */
    .dept-edit-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 28px;
    }
    .dept-edit-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.5);
    }
    .dark .dept-edit-icon { box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.3); }
    .dept-edit-icon svg { width: 26px; height: 26px; }
    .dept-edit-title {
        font-family: "Plus Jakarta Sans", "Inter", sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
        color: var(--de-ink);
    }
    .dept-edit-subtitle {
        font-size: 0.9375rem;
        color: var(--de-muted);
        margin-top: 4px;
        font-weight: 500;
    }

    /* Stepper */
    .dept-stepper {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 28px;
        padding: 20px 24px;
        background: var(--de-surface);
        border: 1px solid var(--de-line);
        border-radius: var(--de-radius-sm);
        box-shadow: var(--de-shadow-sm);
        overflow-x: auto;
    }
    .dept-step {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .dept-step-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
        font-family: "Plus Jakarta Sans", sans-serif;
        background: var(--de-raised);
        color: var(--de-muted);
        border: 2px solid var(--de-line-strong);
    }
    .dept-step.active .dept-step-dot {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px -4px rgba(245, 158, 11, 0.5);
    }
    .dept-step.completed .dept-step-dot {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-color: transparent;
    }
    .dept-step-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--de-muted);
        white-space: nowrap;
    }
    .dept-step.active .dept-step-label { color: var(--de-ink); }
    .dept-step.completed .dept-step-label { color: #10b981; }
    .dept-step-line {
        width: 32px;
        height: 2px;
        background: var(--de-line-strong);
        flex-shrink: 0;
    }
    .dept-step-line.completed { background: linear-gradient(90deg, #10b981, #059669); }

    /* Progress card */
    .dept-progress-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        border-radius: var(--de-radius-sm);
        padding: 28px;
        box-shadow: var(--de-shadow-lg);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .dept-progress-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(245,158,11,0.12) 0%, transparent 50%);
        pointer-events: none;
    }
    .dept-progress-content { position: relative; z-index: 1; }
    .dept-progress-content h2 {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dept-progress-content h2 svg { width: 18px; height: 18px; color: #fbbf24; }
    .dept-progress-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .dept-progress-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: rgba(255,255,255,0.7);
    }
    .dept-progress-value {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: #fbbf24;
    }
    .dept-progress-track {
        height: 10px;
        background: rgba(255,255,255,0.15);
        border-radius: 100px;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .dept-progress-fill {
        height: 100%;
        border-radius: 100px;
        background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.4);
        transition: width 1s ease;
    }
    .dept-progress-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .dept-progress-stat {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: var(--de-radius-xs);
        padding: 14px 16px;
    }
    .dept-progress-stat-label {
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.55);
        margin-bottom: 6px;
    }
    .dept-progress-stat-value {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 0.9375rem;
        font-weight: 700;
        color: white;
    }
    .dept-progress-stat-value.accent { color: #fbbf24; }

    /* Card */
    .dept-edit-card {
        background: var(--de-surface);
        border-radius: var(--de-radius-sm);
        border: 1px solid var(--de-line);
        box-shadow: var(--de-shadow-sm);
        overflow: hidden;
        margin-bottom: 24px;
        transition: background 0.3s, border-color 0.3s;
    }
    .dept-edit-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--de-line);
    }
    .dept-edit-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .dept-edit-card-icon.blue { background: #dbeafe; color: #2563eb; }
    .dept-edit-card-icon.emerald { background: #d1fae5; color: #059669; }
    .dept-edit-card-icon.purple { background: #ede9fe; color: #7c3aed; }
    .dept-edit-card-icon.amber { background: #fef3c7; color: #b45309; }
    .dept-edit-card-icon.rose { background: #ffe4e6; color: #e11d48; }
    .dark .dept-edit-card-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dark .dept-edit-card-icon.emerald { background: rgba(16,185,129,0.15); color: #34d399; }
    .dark .dept-edit-card-icon.purple { background: rgba(139,92,246,0.15); color: #a78bfa; }
    .dark .dept-edit-card-icon.amber { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .dark .dept-edit-card-icon.rose { background: rgba(244,63,94,0.15); color: #fb7185; }
    .dept-edit-card-header h2 {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--de-ink);
    }
    .dept-edit-card-header p {
        font-size: 0.75rem;
        color: var(--de-muted);
        margin-top: 2px;
    }
    .dept-edit-card-body { padding: 24px; }

    /* Form fields */
    .dept-edit-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    .dept-edit-row:last-child { margin-bottom: 0; }
    @media (min-width: 640px) {
        .dept-edit-row.cols-2 { grid-template-columns: 1fr 1fr; }
    }

    .dept-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .dept-field-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--de-ink);
    }
    .dept-field-label .required { color: #ef4444; font-weight: 700; }
    .dept-field-hint {
        font-size: 0.75rem;
        color: var(--de-muted);
        line-height: 1.4;
    }

    .dept-input-wrap { position: relative; }
    .dept-input-wrap .dept-input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--de-muted);
        pointer-events: none;
        transition: color 0.2s;
    }
    .dept-input-wrap.has-icon input,
    .dept-input-wrap.has-icon select { padding-left: 42px; }

    .dept-edit-container input[type="text"],
    .dept-edit-container input[type="number"],
    .dept-edit-container input[type="date"],
    .dept-edit-container input[type="file"],
    .dept-edit-container select,
    .dept-edit-container textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid var(--de-line-strong);
        border-radius: var(--de-radius-xs);
        background: var(--de-raised);
        color: var(--de-ink);
        font-family: inherit;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s ease;
    }
    .dept-edit-container input::placeholder,
    .dept-edit-container textarea::placeholder { color: var(--de-muted); opacity: 0.7; }
    .dept-edit-container input:focus,
    .dept-edit-container select:focus,
    .dept-edit-container textarea:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.12);
        background: var(--de-surface);
    }
    .dept-edit-container input:disabled,
    .dept-edit-container select:disabled,
    .dept-edit-container textarea:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: var(--de-raised);
    }
    .dept-edit-container input[type="file"] {
        padding: 8px 14px;
        font-size: 0.8125rem;
        cursor: pointer;
    }
    .dept-edit-container input[type="file"]::file-selector-button {
        margin-right: 12px;
        padding: 6px 14px;
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        font-family: inherit;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .dept-edit-container input[type="file"]::file-selector-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px -2px rgba(245, 158, 11, 0.4);
    }
    .dept-edit-container select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 20px;
        padding-right: 40px;
    }
    .dept-edit-container textarea {
        resize: vertical;
        min-height: 100px;
        line-height: 1.6;
    }

    /* Locked panel */
    .dept-locked-panel {
        background: linear-gradient(135deg, rgba(30,27,75,0.03) 0%, rgba(67,56,202,0.03) 100%);
        border: 1px dashed var(--de-line-strong);
        border-radius: var(--de-radius-xs);
        padding: 24px;
        position: relative;
    }
    .dark .dept-locked-panel {
        background: linear-gradient(135deg, rgba(139,92,246,0.05) 0%, rgba(67,56,202,0.05) 100%);
        border-color: rgba(139,92,246,0.2);
    }
    .dept-locked-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .dept-locked-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--de-ink);
    }
    .dept-locked-title svg { width: 18px; height: 18px; color: #8b5cf6; }

    .dept-permission-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }
    .dept-permission-btn.request {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px -4px rgba(245, 158, 11, 0.4);
    }
    .dept-permission-btn.request:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px -4px rgba(245, 158, 11, 0.5);
    }
    .dept-permission-btn.approved {
        background: #d1fae5;
        color: #047857;
        cursor: default;
    }
    .dark .dept-permission-btn.approved { background: rgba(16,185,129,0.15); color: #34d399; }
    .dept-permission-btn.waiting {
        background: #fef3c7;
        color: #b45309;
        cursor: default;
    }
    .dark .dept-permission-btn.waiting { background: rgba(251,191,36,0.15); color: #fbbf24; }

    .dept-locked-hint {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: var(--de-muted);
        margin-top: 6px;
    }
    .dept-locked-hint svg { width: 14px; height: 14px; flex-shrink: 0; }

    /* Map locked */
    .dept-map-locked {
        position: relative;
        border-radius: var(--de-radius-xs);
        overflow: hidden;
        border: 1px solid var(--de-line);
        opacity: 0.85;
    }
    #project-location-map {
        width: 100%;
        height: 280px;
        background: #e5e7eb;
    }
    .dept-map-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 14, 26, 0.4);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 400;
    }
    .dept-map-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px);
        border-radius: 100px;
        font-size: 0.8125rem;
        font-weight: 700;
        color: #1e1b4b;
        box-shadow: var(--de-shadow-lg);
    }
    .dept-map-badge svg { width: 16px; height: 16px; color: #8b5cf6; }

    /* Status select */
    .dept-status-wrap { position: relative; }
    .dept-status-dot {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        z-index: 2;
    }
    .dept-status-wrap select { padding-left: 32px; }
    html.dark-mode .dept-status-wrap select,
    html.dark-mode .dept-status-wrap optgroup,
    html.dark-mode .dept-status-wrap option {
        background: #141321 !important;
        color: #f8f7f5 !important;
    }
    .dept-status-wrap option.dept-status-group-option {
        color: #b45309 !important;
        font-weight: 700;
    }
    html.dark-mode .dept-status-wrap option.dept-status-group-option {
        color: #fbbf24 !important;
    }
    html.dark-mode .dept-status-wrap optgroup {
        color: #fbbf24 !important;
    }

    /* Alerts */
    .dept-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 20px;
        border-radius: var(--de-radius-xs);
        margin-bottom: 24px;
    }
    .dept-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
    }
    .dark .dept-alert-error { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.2); }
    .dept-alert-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
    }
    .dark .dept-alert-info { background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.2); }
    .dept-alert-icon { width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; }
    .dept-alert-error .dept-alert-icon { color: #dc2626; }
    .dept-alert-info .dept-alert-icon { color: #2563eb; }
    .dark .dept-alert-info .dept-alert-icon { color: #60a5fa; }
    .dept-alert ul {
        list-style: none;
        font-size: 0.8125rem;
        color: #991b1b;
    }
    .dark .dept-alert-error ul { color: #fca5a5; }
    .dept-alert li { position: relative; padding-left: 14px; margin-bottom: 4px; }
    .dept-alert li:last-child { margin-bottom: 0; }
    .dept-alert li::before { content: "•"; position: absolute; left: 0; font-weight: 700; }
    .dept-alert p { font-size: 0.875rem; color: var(--de-ink-secondary); }
    .dept-alert p strong { color: var(--de-ink); }

    /* Actions */
    .dept-edit-actions {
        display: flex;
        flex-direction: column-reverse;
        gap: 12px;
        padding-top: 8px;
    }
    @media (min-width: 480px) {
        .dept-edit-actions { flex-direction: row; justify-content: flex-end; }
    }
    .dept-btn {
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
    .dept-btn-primary {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 14px -4px rgba(245, 158, 11, 0.5);
    }
    .dept-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -4px rgba(245, 158, 11, 0.6);
    }
    .dept-btn-secondary {
        background: var(--de-raised);
        color: var(--de-ink-secondary);
        border: 1px solid var(--de-line-strong);
    }
    .dept-btn-secondary:hover {
        background: var(--de-surface);
        box-shadow: var(--de-shadow-sm);
    }
    .dept-btn svg { width: 18px; height: 18px; }

    /* Modal */
    .dept-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 14, 26, 0.7);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .dept-modal.show { display: flex; opacity: 1; }
    .dept-modal-box {
        background: var(--de-surface);
        border-radius: var(--de-radius-sm);
        border: 1px solid var(--de-line);
        box-shadow: var(--de-shadow-lg);
        width: 100%;
        max-width: 460px;
        padding: 28px;
        transform: scale(0.95);
        transition: transform 0.2s ease;
    }
    .dept-modal.show .dept-modal-box { transform: scale(1); }
    .dept-modal-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b20, #d9770620);
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    .dark .dept-modal-icon { background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(217,119,6,0.1)); }
    .dept-modal-icon svg { width: 22px; height: 22px; }
    .dept-modal-title {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 1.125rem;
        font-weight: 800;
        color: var(--de-ink);
        margin-bottom: 6px;
    }
    .dept-modal-desc {
        font-size: 0.875rem;
        color: var(--de-muted);
        line-height: 1.5;
        margin-bottom: 20px;
    }
    .dept-modal-field { margin-bottom: 20px; }
    .dept-modal-field label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--de-ink);
        margin-bottom: 6px;
    }
    .dept-modal-field textarea {
        min-height: 100px;
    }
    .dept-modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    /* Others wrapper */
    .dept-others-wrap {
        margin-top: 8px;
        padding: 16px;
        background: var(--de-raised);
        border: 1px solid var(--de-line);
        border-radius: var(--de-radius-xs);
    }
    .dept-others-wrap label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--de-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
        display: block;
    }

    @keyframes deFadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dept-animate {
        animation: deFadeUp 0.4s ease forwards;
        opacity: 0;
    }
    .dept-animate:nth-child(1) { animation-delay: 0.03s; }
    .dept-animate:nth-child(2) { animation-delay: 0.06s; }
    .dept-animate:nth-child(3) { animation-delay: 0.09s; }
    .dept-animate:nth-child(4) { animation-delay: 0.12s; }
    .dept-animate:nth-child(5) { animation-delay: 0.15s; }

    @media (prefers-reduced-motion: reduce) {
        .dept-animate { animation: none; opacity: 1; }
    }
</style>

<div class="dept-edit-container">

    <!-- PAGE HEADER -->
    <div class="dept-edit-header dept-animate">
        <div class="dept-edit-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
        </div>
        <div>
            <h1 class="dept-edit-title">Edit Project</h1>
            <p class="dept-edit-subtitle">{{ $project->project_name }}</p>
        </div>
    </div>

    <!-- STEPPER -->
    @include('components.project-stepper', ['project' => $project])

    <!-- PROGRESS CARD -->
    @php
        $startDate = \Carbon\Carbon::parse($project->start_date);
        $endDate = \Carbon\Carbon::parse($project->target_end_date);
        $today = \Carbon\Carbon::today();
        $totalDays = $startDate->diffInDays($endDate);
        $daysElapsed = $startDate->diffInDays($today);
        $timelineProgress = ($totalDays > 0) ? min(100, max(0, ($daysElapsed / $totalDays) * 100)) : 0;
        $reportedProgress = $project->latestUpdate?->progress_percentage;
        $progress = $reportedProgress !== null ? $reportedProgress : $timelineProgress;
        $progressLabel = $reportedProgress !== null ? 'Reported Progress' : 'Timeline Progress';
    @endphp
    <div class="dept-progress-hero dept-animate">
        <div class="dept-progress-content">
            <h2>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Project Progress
            </h2>
            <div class="dept-progress-meta">
                <span class="dept-progress-label">{{ $progressLabel }}</span>
                <span class="dept-progress-value">{{ number_format($progress, 1) }}%</span>
            </div>
            <div class="dept-progress-track">
                <div class="dept-progress-fill" style="width: {{ $progress }}%"></div>
            </div>
            <div class="dept-progress-stats">
                <div class="dept-progress-stat">
                    <div class="dept-progress-stat-label">Start Date</div>
                    <div class="dept-progress-stat-value">{{ $startDate->format('M d, Y') }}</div>
                </div>
                <div class="dept-progress-stat">
                    <div class="dept-progress-stat-label">Target Completion</div>
                    <div class="dept-progress-stat-value">{{ $endDate->format('M d, Y') }}</div>
                </div>
                <div class="dept-progress-stat">
                    <div class="dept-progress-stat-label">Days Remaining</div>
                    <div class="dept-progress-stat-value accent">{{ max(0, $today->diffInDays($endDate, false)) }} days</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ERROR ALERT -->
    @if ($errors->any())
        <div class="dept-alert dept-alert-error dept-animate">
            <svg class="dept-alert-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- PERMISSION ALERT -->
    <div id="permissionAlert" class="dept-alert dept-alert-info dept-animate" style="display: none;">
        <svg class="dept-alert-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 00.063.853l.041.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
        <p><strong>Permission Request Pending</strong> — Your request to edit critical project fields has been submitted to the Department Head for approval.</p>
    </div>

    <!-- MAIN FORM -->
    <form method="POST" action="{{ route('department.projects.update', $project->project_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
            $standardTypes = ['Bridges', 'Buildings and Facilities', 'Flood Control and Drainage', 'Roads', 'Septage and Sewerage Plants', 'Water Provision and Storage'];
            $currentType = old('project_type', $project->project_type);
            $isOtherType = $currentType && !in_array($currentType, $standardTypes);
        @endphp

        <!-- PROJECT INFORMATION CARD -->
        <div class="dept-edit-card dept-animate">
            <div class="dept-edit-card-header">
                <div class="dept-edit-card-icon blue">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                </div>
                <div>
                    <h2>Project Information</h2>
                    <p>Basic details and identification for this project.</p>
                </div>
            </div>
            <div class="dept-edit-card-body">
                <div class="dept-edit-row cols-2">
                    <div class="dept-field">
                        <label class="dept-field-label">Project Code</label>
                        <div class="dept-input-wrap has-icon">
                            <input type="text" name="project_code" value="{{ old('project_code', $project->project_code) }}">
                            <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 3.75h15m-16.5 3.75h15m-16.5 3.75h15"/></svg>
                        </div>
                    </div>
                    <div class="dept-field">
                        <label class="dept-field-label">Project Type <span class="required">*</span></label>
                        <select id="project_type_select" required>
                            <option value="">-- Select Project Type --</option>
                            <option value="Bridges" @selected($currentType == 'Bridges')>Bridges</option>
                            <option value="Buildings and Facilities" @selected($currentType == 'Buildings and Facilities')>Buildings and Facilities</option>
                            <option value="Flood Control and Drainage" @selected($currentType == 'Flood Control and Drainage')>Flood Control and Drainage</option>
                            <option value="Roads" @selected($currentType == 'Roads')>Roads</option>
                            <option value="Septage and Sewerage Plants" @selected($currentType == 'Septage and Sewerage Plants')>Septage and Sewerage Plants</option>
                            <option value="Water Provision and Storage" @selected($currentType == 'Water Provision and Storage')>Water Provision and Storage</option>
                            <option value="Others" @selected($isOtherType)>Others</option>
                        </select>
                        <input type="hidden" id="project_type" name="project_type" value="{{ $currentType }}">
                        <div id="project_type_other_wrapper" class="dept-others-wrap" style="{{ $isOtherType ? '' : 'display: none;' }}">
                            <label>Please specify</label>
                            <input type="text" id="project_type_other" value="{{ $isOtherType ? $currentType : '' }}" placeholder="Enter project type">
                        </div>
                    </div>
                </div>

                <div class="dept-edit-row">
                    <div class="dept-field">
                        <label class="dept-field-label">Project Name <span class="required">*</span></label>
                        <div class="dept-input-wrap has-icon">
                            <input type="text" name="project_name" value="{{ old('project_name', $project->project_name) }}">
                            <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                        </div>
                    </div>
                </div>

                <div class="dept-edit-row">
                    <div class="dept-field">
                        <label class="dept-field-label">Barangay</label>
                        <div class="dept-input-wrap has-icon">
                            <select id="barangay_id" name="barangay_id" disabled>
                                <option value="">-- None / Citywide --</option>
                                @foreach ($barangays as $barangay)
                                    <option value="{{ $barangay->barangay_id }}" @selected(old('barangay_id', $project->barangay_id) == $barangay->barangay_id)>
                                        {{ $barangay->barangay_name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        <p class="dept-field-hint">Automatically detected from map location (locked for consistency)</p>
                    </div>
                </div>

                <!-- LOCKED MAP -->
                <div class="dept-field" style="margin-top:8px;">
                    <label class="dept-field-label">Project Location</label>
                    <div class="dept-map-locked">
                        <div id="project-location-map"></div>
                        <div class="dept-map-overlay">
                            <div class="dept-map-badge">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                Location locked — contact admin to change
                            </div>
                        </div>
                    </div>
                    <div class="dept-input-wrap has-icon" style="margin-top:12px;">
                        <input type="text" id="project-address" readonly value="{{ $project->location_description }}">
                        <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- LOCKED PANEL CARD -->
        <div class="dept-edit-card dept-animate">
            <div class="dept-edit-card-header">
                <div class="dept-edit-card-icon amber">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <div>
                    <h2>Critical Project Details</h2>
                    <p>These fields require Department Head approval to edit.</p>
                </div>
            </div>
            <div class="dept-edit-card-body">
                <div class="dept-locked-panel">
                    <div class="dept-locked-header">
                        <div class="dept-locked-title">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Approval Required
                        </div>
                        <button
                            type="button"
                            id="askPermissionBtn"
                            class="dept-permission-btn {{ ($canEditCriticalFields ?? false) ? 'approved' : (($canRequestPermission ?? true) ? 'request' : 'waiting') }}"
                            @if($canEditCriticalFields ?? false) disabled @endif
                            @if(!($canRequestPermission ?? true)) disabled @endif
                        >
                            @if($canEditCriticalFields ?? false)
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Permission Approved
                            @elseif(!($canRequestPermission ?? true))
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                Awaiting Department Head Approval
                            @else
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                Ask Permission to Edit
                            @endif
                        </button>
                    </div>

                    <div class="dept-edit-row cols-2">
                        <div class="dept-field">
                            <label class="dept-field-label">Start Date <span class="required">*</span></label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" @if(!($canEditCriticalFields ?? false)) disabled @endif required>
                            <p class="dept-locked-hint">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                @if($canEditCriticalFields ?? false)
                                    Permission approved — you can now edit this field.
                                @else
                                    Locked — requires Department Head approval
                                @endif
                            </p>
                        </div>
                        <div class="dept-field">
                            <label class="dept-field-label">Target Completion <span class="required">*</span></label>
                            <input type="date" id="target_end_date" name="target_end_date" value="{{ old('target_end_date', $project->target_end_date?->format('Y-m-d')) }}" @if(!($canEditCriticalFields ?? false)) disabled @endif required>
                            <p class="dept-locked-hint">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                @if($canEditCriticalFields ?? false)
                                    Permission approved — you can now edit this field.
                                @else
                                    Locked — requires Department Head approval
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="dept-edit-row cols-2">
                        <div class="dept-field">
                            <label class="dept-field-label">Approved Budget <span class="required">*</span></label>
                            <div class="dept-input-wrap has-icon">
                                <input type="number" step="0.01" id="approved_budget" name="approved_budget" value="{{ old('approved_budget', $project->approved_budget) }}" @if(!($canEditCriticalFields ?? false)) disabled @endif required>
                                <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="dept-locked-hint">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                @if($canEditCriticalFields ?? false)
                                    Permission approved — you can now edit this field.
                                @else
                                    Locked — requires Department Head approval
                                @endif
                            </p>
                        </div>
                        <div class="dept-field">
                            <label class="dept-field-label">Actual Budget Spent</label>
                            <div class="dept-input-wrap has-icon">
                                <input type="number" step="0.01" id="actual_budget" name="actual_budget" value="{{ old('actual_budget', $project->actual_budget) }}" @if(!($canEditCriticalFields ?? false)) disabled @endif>
                                <svg class="dept-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.582 1.453-1.318V5.253a1.875 1.875 0 00-1.453-1.318A60.062 60.062 0 002.25 1.575v17.175zM6.75 9.75l4.5 4.5 7.5-7.5"/></svg>
                            </div>
                            <p class="dept-locked-hint">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                @if($canEditCriticalFields ?? false)
                                    Permission approved — you can now edit this field.
                                @else
                                    Locked — requires Department Head approval
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PROJECT DETAILS CARD -->
        <div class="dept-edit-card dept-animate">
            <div class="dept-edit-card-header">
                <div class="dept-edit-card-icon emerald">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.429-2.507a2.117 2.117 0 00-1.86-.22m-7.5 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22m-6.44 2.1l.22.22m6.44-2.22l-.22.22M3.75 6.75l7.5-4.5 7.5 4.5M3.75 6.75v10.5a2.25 2.25 0 002.25 2.25h10.5"/></svg>
                </div>
                <div>
                    <h2>Project Details</h2>
                    <p>Status, descriptions, and supporting materials.</p>
                </div>
            </div>
            <div class="dept-edit-card-body">
                <div class="dept-edit-row">
                    <div class="dept-field">
                        <label class="dept-field-label">Current Status</label>
                        @php $status = old('current_status', $project->current_status); @endphp
                        <div class="dept-status-wrap">
                            <span class="dept-status-dot" style="background: #f59e0b;"></span>
                            <select name="current_status">
                                <option class="dept-status-group-option" disabled>Project Lifecycle</option>
                                <option value="Proposed" @selected($status == 'Proposed')>Proposed</option>
                                <option value="For bidding" @selected($status == 'For bidding')>For bidding</option>
                                <option value="Bidding ongoing" @selected($status == 'Bidding ongoing')>Bidding ongoing</option>
                                <option value="Award of contract" @selected($status == 'Award of contract')>Award of contract</option>
                                <option value="Implementation" @selected($status == 'Implementation')>Implementation</option>
                                <option value="Completed" @selected($status == 'Completed')>Completed</option>
                                <optgroup label="Other statuses">
                                    <option value="On Hold" @selected($status == 'On Hold')>On Hold</option>
                                    <option value="Cancelled" @selected($status == 'Cancelled')>Cancelled</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="dept-edit-row">
                    <div class="dept-field">
                        <label class="dept-field-label">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:text-bottom;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Public Description
                        </label>
                        <p class="dept-field-hint">Shown to the public on the transparency portal. Keep this general and non-sensitive.</p>
                        <textarea name="public_description" rows="3">{{ old('public_description', $project->public_description) }}</textarea>
                    </div>
                </div>

                <div class="dept-edit-row">
                    <div class="dept-field">
                        <label class="dept-field-label">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:text-bottom;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Internal Remarks (Private)
                        </label>
                        <p class="dept-field-hint">Encrypted and only visible to authorized staff — not shown publicly.</p>
                        <textarea name="remarks" rows="3" style="background: var(--de-surface); border-style: dashed;">{{ old('remarks', $project->remarks) }}</textarea>
                    </div>
                </div>

                <div class="dept-edit-row">
                    <div class="dept-field">
                        <label class="dept-field-label">Replace Project Image</label>
                        <input type="file" name="project_image" accept="image/*">
                    </div>
                </div>

                <div class="dept-edit-actions">
                    <a href="{{ route('department.projects.show', $project->project_id) }}" class="dept-btn dept-btn-secondary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Cancel
                    </a>
                    <button type="submit" class="dept-btn dept-btn-primary">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- HIDDEN PERMISSION FORM -->
<form id="permissionRequestForm" method="POST" action="{{ route('department.projects.request-edit-permission', $project->project_id) }}" style="display: none;">
    @csrf
    <input type="hidden" name="reason" id="permissionReasonInput">
</form>

<!-- PERMISSION MODAL -->
<div id="permissionModal" class="dept-modal">
    <div class="dept-modal-box">
        <div class="dept-modal-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
        </div>
        <div class="dept-modal-title">Reason for Edit Request</div>
        <div class="dept-modal-desc">Please tell the department head why you need to edit these critical project details.</div>
        <div class="dept-modal-field">
            <label>Explanation</label>
            <textarea id="permissionReasonTextarea" placeholder="Example: We need to update the approved budget due to revised funding."></textarea>
        </div>
        <div class="dept-modal-actions">
            <button type="button" id="cancelPermissionBtn" class="dept-btn dept-btn-secondary">Cancel</button>
            <button type="button" id="submitPermissionBtn" class="dept-btn dept-btn-primary">Send Request</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Project type "Others" toggle
    const projectTypeSelect = document.getElementById('project_type_select');
    const projectTypeHidden = document.getElementById('project_type');
    const projectTypeOtherWrapper = document.getElementById('project_type_other_wrapper');
    const projectTypeOtherInput = document.getElementById('project_type_other');

    function syncProjectType() {
        if (projectTypeSelect.value === 'Others') {
            projectTypeOtherWrapper.style.display = 'block';
            projectTypeHidden.value = projectTypeOtherInput.value;
        } else {
            projectTypeOtherWrapper.style.display = 'none';
            projectTypeHidden.value = projectTypeSelect.value;
        }
    }
    projectTypeSelect.addEventListener('change', syncProjectType);
    projectTypeOtherInput.addEventListener('input', function() {
        projectTypeHidden.value = projectTypeOtherInput.value;
    });
    syncProjectType();

    // Leaflet map (locked display mode)
    const map = L.map('project-location-map').setView([{{ $project->latitude ?? 14.8497 }}, {{ $project->longitude ?? 121.0074 }}], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    @if($project->latitude && $project->longitude)
    L.marker([{{ $project->latitude }}, {{ $project->longitude }}]).addTo(map)
        .bindPopup('{{ $project->location_description }}');
    @endif

    // Lock map interactions
    map.dragging.disable();
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();
    if (map.tap) map.tap.disable();

    // Permission modal
    const permissionModal = document.getElementById('permissionModal');
    const permissionReasonTextarea = document.getElementById('permissionReasonTextarea');
    const permissionReasonInput = document.getElementById('permissionReasonInput');

    document.getElementById('askPermissionBtn').addEventListener('click', function(e) {
        e.preventDefault();
        permissionReasonTextarea.value = '';
        permissionModal.classList.add('show');
        document.body.style.overflow = 'hidden';
    });

    document.getElementById('cancelPermissionBtn').addEventListener('click', function() {
        permissionModal.classList.remove('show');
        document.body.style.overflow = '';
    });

    document.getElementById('submitPermissionBtn').addEventListener('click', function() {
        const reason = permissionReasonTextarea.value.trim();
        if (!reason) {
            alert('Please enter a reason for the edit request.');
            return;
        }
        permissionReasonInput.value = reason;
        permissionModal.classList.remove('show');
        document.body.style.overflow = '';
        document.getElementById('permissionRequestForm').submit();
    });

    // Close on backdrop click
    permissionModal.addEventListener('click', function(e) {
        if (e.target === permissionModal) {
            permissionModal.classList.remove('show');
            document.body.style.overflow = '';
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && permissionModal.classList.contains('show')) {
            permissionModal.classList.remove('show');
            document.body.style.overflow = '';
        }
    });

    // Show permission alert if just requested
    @if(session('permission_requested'))
    const alertBox = document.getElementById('permissionAlert');
    alertBox.style.display = 'flex';
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    @endif
});
</script>
@endsection