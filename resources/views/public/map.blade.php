<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Projects Map | Cabuyao</title>
    @include('layouts.favicon')
    @include('components.theme-init')

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Public+Sans:wght@400;600;700&display=swap">
    {{-- Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Leaflet for map --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />

    <style>
        .glass-nav {
            backdrop-filter: blur(16px);
            background-color: rgba(248, 249, 255, 0.8);
        }

        .public-map-header {
            position: sticky;
            top: 0;
            z-index: 1100;
        }

        #map {
            min-height: 55vh;
            height: 55vh;
        }

        @media (min-width: 640px) {
            #map {
                min-height: 62vh;
                height: 62vh;
            }
        }

        @media (min-width: 1024px) {
            #map {
                min-height: calc(100vh - 18rem);
                height: calc(100vh - 18rem);
            }
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .public-project-details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .public-project-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #dbe4f0;
            border-radius: 10px;
            background: #edf3ff;
            padding: 12px;
        }

        .public-project-detail-label {
            color: #64748b;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .public-project-detail-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            flex-shrink: 0;
            font-size: 18px;
        }

        .public-project-detail-icon.blue { background: #dbeafe; color: #2563eb; }
        .public-project-detail-icon.emerald { background: #d1fae5; color: #059669; }
        .public-project-detail-icon.purple { background: #ede9fe; color: #7c3aed; }
        .public-project-detail-icon.amber { background: #fef3c7; color: #b45309; }
        .public-project-detail-icon.rose { background: #ffe4e6; color: #e11d48; }

        .public-project-detail-value {
            color: #0f172a;
            font-size: 0.875rem;
            font-weight: 700;
            margin-top: 4px;
            overflow-wrap: anywhere;
        }

        .public-project-detail-wide { grid-column: 1 / -1; }

        .public-project-details-card {
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 12px;
            background: #edf3ff;
            box-shadow: 0 10px 20px -16px rgba(15, 23, 42, 0.22);
        }

        .public-project-description-card {
            margin-top: 18px;
            padding: 16px 18px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 12px;
            background: #edf3ff;
            box-shadow: 0 10px 20px -16px rgba(15, 23, 42, 0.22);
        }

        .public-project-description-card p {
            margin: 0;
            font-size: 0.875rem;
            line-height: 1.6;
            color: #475569;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .public-project-lightbox-trigger {
            cursor: zoom-in;
        }

        .public-image-lightbox {
            position: fixed;
            inset: 0;
            z-index: 100000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(2, 6, 23, 0.86);
            backdrop-filter: blur(6px);
        }

        .public-image-lightbox.is-open {
            display: flex;
        }

        .public-image-lightbox-image {
            max-width: min(92vw, 1200px);
            max-height: 84vh;
            border-radius: 10px;
            object-fit: contain;
            transform: scale(1);
            transition: transform 0.15s ease;
            user-select: none;
        }

        .public-image-lightbox-toolbar {
            position: fixed;
            top: 18px;
            right: 18px;
            display: flex;
            gap: 8px;
        }

        .public-image-lightbox-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(15, 23, 42, 0.8);
            color: #fff;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .public-image-lightbox-button:hover {
            background: rgba(51, 65, 85, 0.95);
        }

        .public-map-viewall {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 18px;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #dbe4f0;
            background: #edf3ff;
            color: #475569;
            font-size: 0.875rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .public-map-viewall:hover {
            background: #f8fafc;
            border-color: #f59e0b;
            color: #d97706;
            transform: translateY(-1px);
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .public-project-image-wrap {
            position: relative;
            overflow: hidden;
            border-radius: 0;
            background: #f8fafc;
        }

        .public-project-image {
            display: block;
            width: 100%;
            height: 160px;
            object-fit: cover;
            transition: transform 0.25s ease;
        }

        .public-project-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.08) 0%, rgba(15, 23, 42, 0.18) 100%);
        }

        .public-collapsed-project-card:hover .public-project-image {
            transform: scale(1.04);
        }

        .public-status-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            border: 1px solid transparent;
            padding: 0.125rem 0.5rem;
            font-size: 0.625rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .public-status-proposed { background: rgba(37, 99, 235, 0.10); color: #2563eb; border-color: rgba(37, 99, 235, 0.25); }
        .public-status-bidding { background: rgba(245, 158, 11, 0.10); color: #f59e0b; border-color: rgba(245, 158, 11, 0.25); }
        .public-status-ongoing { background: rgba(6, 182, 212, 0.10); color: #06b6d4; border-color: rgba(6, 182, 212, 0.25); }
        .public-status-award { background: rgba(139, 92, 246, 0.10); color: #8b5cf6; border-color: rgba(139, 92, 246, 0.25); }
        .public-status-implementation { background: rgba(15, 118, 110, 0.10); color: #0f766e; border-color: rgba(15, 118, 110, 0.25); }
        .public-status-completed { background: rgba(22, 163, 74, 0.10); color: #16a34a; border-color: rgba(22, 163, 74, 0.25); }
        .public-status-hold { background: rgba(220, 38, 38, 0.10); color: #dc2626; border-color: rgba(220, 38, 38, 0.25); }
        .public-status-cancelled { background: rgba(100, 116, 139, 0.10); color: #64748b; border-color: rgba(100, 116, 139, 0.25); }

        html.dark-mode .public-status-proposed,
        .dark .public-status-proposed { background: rgba(37, 99, 235, 0.12); color: #60a5fa; border-color: rgba(37, 99, 235, 0.25); }
        html.dark-mode .public-status-bidding,
        .dark .public-status-bidding { background: rgba(245, 158, 11, 0.12); color: #fbbf24; border-color: rgba(245, 158, 11, 0.25); }
        html.dark-mode .public-status-ongoing,
        .dark .public-status-ongoing { background: rgba(6, 182, 212, 0.12); color: #67e8f9; border-color: rgba(6, 182, 212, 0.25); }
        html.dark-mode .public-status-award,
        .dark .public-status-award { background: rgba(139, 92, 246, 0.12); color: #a78bfa; border-color: rgba(139, 92, 246, 0.25); }
        html.dark-mode .public-status-implementation,
        .dark .public-status-implementation { background: rgba(15, 118, 110, 0.12); color: #5eead4; border-color: rgba(15, 118, 110, 0.25); }
        html.dark-mode .public-status-completed,
        .dark .public-status-completed { background: rgba(22, 163, 74, 0.12); color: #4ade80; border-color: rgba(22, 163, 74, 0.25); }
        html.dark-mode .public-status-hold,
        .dark .public-status-hold { background: rgba(220, 38, 38, 0.12); color: #f87171; border-color: rgba(220, 38, 38, 0.25); }
        html.dark-mode .public-status-cancelled,
        .dark .public-status-cancelled { background: rgba(100, 116, 139, 0.12); color: #cbd5e1; border-color: rgba(100, 116, 139, 0.25); }

        html.dark-mode .public-project-details-card,
        .dark .public-project-details-card {
            border-color: rgba(255, 255, 255, 0.08) !important;
            background: #0f172a !important;
        }

        html.dark-mode .public-project-detail,
        .dark .public-project-detail {
            border-color: rgba(255, 255, 255, 0.08);
            background: #0f172a;
        }

        html.dark-mode .public-project-detail-label,
        .dark .public-project-detail-label { color: #94a3b8; }

        html.dark-mode .public-project-detail-icon.blue,
        .dark .public-project-detail-icon.blue { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
        html.dark-mode .public-project-detail-icon.emerald,
        .dark .public-project-detail-icon.emerald { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        html.dark-mode .public-project-detail-icon.purple,
        .dark .public-project-detail-icon.purple { background: rgba(139, 92, 246, 0.15); color: #a78bfa; }
        html.dark-mode .public-project-detail-icon.amber,
        .dark .public-project-detail-icon.amber { background: rgba(251, 191, 36, 0.15); color: #fbbf24; }
        html.dark-mode .public-project-detail-icon.rose,
        .dark .public-project-detail-icon.rose { background: rgba(244, 63, 94, 0.15); color: #fb7185; }

        html.dark-mode .public-project-detail-value,
        .dark .public-project-detail-value { color: #f8fafc; }

        html.dark-mode .public-project-description-card,
        .dark .public-project-description-card {
            border-color: rgba(255,255,255,0.08);
            background: #0f172a;
        }

        html.dark-mode .public-project-description-card p,
        .dark .public-project-description-card p {
            color: #cbd5e1;
        }

        html.dark-mode .public-map-viewall,
        .dark .public-map-viewall {
            border-color: rgba(255,255,255,0.08);
            background: #0f172a;
            color: #cbd5e1;
        }

        html.dark-mode .public-map-viewall:hover,
        .dark .public-map-viewall:hover {
            border-color: #fbbf24;
            color: #fbbf24;
        }

        .public-map-project-card {
            position: relative;
        }

        .public-collapsed-project-card {
            position: relative;
            border-color: rgba(15, 23, 42, 0.2) !important;
            background: #edf3ff !important;
            color: #1e1b4b;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .public-collapsed-project-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            bottom: 0;
            background: linear-gradient(180deg, #f59e0b, #d97706);
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .public-collapsed-project-card:hover {
            border-color: rgba(15, 23, 42, 0.28) !important;
            box-shadow: 0 14px 30px -18px rgba(15, 23, 42, 0.28) !important;
            transform: translateY(-3px);
        }

        .public-collapsed-project-card:hover::before {
            opacity: 1;
        }

        .public-collapsed-project-card .public-project-details-card {
            background: #edf3ff !important;
            border-color: rgba(15, 23, 42, 0.2) !important;
        }

        .public-collapsed-project-card .public-collapsed-barangay {
            font-size: 0.75rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.4;
        }

        html.dark-mode .public-collapsed-project-card,
        .dark .public-collapsed-project-card {
            border-color: rgba(255, 255, 255, 0.18) !important;
            background: #0f172a !important;
            color: #f8fafc;
        }

        html.dark-mode .public-collapsed-project-card:hover,
        .dark .public-collapsed-project-card:hover {
            border-color: rgba(255, 255, 255, 0.24) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.4), 0 2px 4px -2px rgb(0 0 0 / 0.4) !important;
        }

        html.dark-mode .public-collapsed-project-card .public-project-details-card,
        .dark .public-collapsed-project-card .public-project-details-card {
            background: #0f172a !important;
            border-color: rgba(255, 255, 255, 0.18) !important;
        }

        html.dark-mode .public-collapsed-project-card .public-collapsed-barangay,
        .dark .public-collapsed-project-card .public-collapsed-barangay {
            color: #f8fafc;
        }

        .public-lifecycle-current {
            background: #f59e0b;
            border-color: #d97706;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.18), 0 0 14px rgba(245, 158, 11, 0.35);
        }
        .public-lifecycle-complete {
            background: #10b981;
            border-color: #059669;
            color: #ffffff;
        }
        .public-lifecycle-pending {
            background: #ffffff;
            border-color: #d1d5db;
            color: #9ca3af;
        }
        .public-lifecycle-label-active { color: #b45309; }
        .public-lifecycle-label-muted { color: #9ca3af; }
        html.dark-mode .public-lifecycle-pending,
        .dark .public-lifecycle-pending {
            background: #1a1929;
            border-color: #475569;
            color: #94a3b8;
        }
        html.dark-mode .public-lifecycle-label-active,
        .dark .public-lifecycle-label-active { color: #fbbf24; }

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
        @media (max-width: 420px) {
            .public-project-details-grid { grid-template-columns: 1fr; }
        }
    </style>

    <style>
        /* Constrain project details sidebar on wide screens so a single project card
           doesn't expand to cover most of the viewport. Keep small-screen behavior unchanged. */
        @media (min-width: 1024px) {
            #projectSidebar {
                width: 460px;
                flex: 0 0 460px;
            }

            /* Ensure project cards fill the sidebar width but don't force wider layout */
            #projectSidebar .department-project-card {
                width: 100%;
                max-width: 100%;
            }
        }

        @media (min-width: 1280px) {
            #projectSidebar {
                width: 560px;
                flex-basis: 560px;
            }

            #projectSidebar .public-map-project-card .text-xs {
                font-size: 0.8rem;
                line-height: 1.25rem;
            }

            #projectSidebar .public-map-project-card .text-sm {
                font-size: 0.9rem;
                line-height: 1.4rem;
            }
        }
    </style>
</head>
<body class="public-layout bg-white font-sans text-slate-900 antialiased">

    {{-- ============ TOP NAV (same as landing page) ============ --}}
    <header class="public-map-header glass-nav w-full border-b border-slate-200/50">
        <nav class="relative flex items-center py-4 w-full mx-auto px-12 justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 shrink-0 rounded-lg object-contain" width="40" height="40" decoding="async" />
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tighter text-slate-900" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="text-[10px] uppercase tracking-widest text-slate-500 opacity-70" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </div>

            <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center text-xs uppercase tracking-widest gap-6" style="font-family:'Public Sans',sans-serif;">
                <a href="{{ url('/') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Home</a>
                <a href="{{ route('public.map') }}" class="text-emerald-700 font-bold border-b-2 border-emerald-600 py-2 transition-all">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Analytics</a>
            </div>

            <div class="flex items-center gap-2">
                @include('components.public-theme-toggle')
                <a href="{{ route('login') }}" class="public-login-button bg-slate-900 text-white px-5 py-2.5 rounded-md font-semibold text-sm hover:opacity-90 transition-all duration-200 shrink-0">Login</a>
            </div>
        </nav>
        <div class="md:hidden border-t border-slate-200 bg-white">
            <div class="flex flex-wrap items-center justify-center gap-3 px-4 py-3 text-xs uppercase tracking-widest text-slate-600">
                <a href="{{ url('/') }}" class="hover:text-emerald-700 transition-colors">Home</a>
                <a href="{{ route('public.map') }}" class="text-emerald-700 font-semibold">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="hover:text-emerald-700 transition-colors">Analytics</a>
            </div>
        </div>
    </header>

    {{-- ============ MAP CONTENT ============ --}}
    <main class="px-4 py-5 md:px-6 md:py-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-4 overflow-hidden rounded-3xl border border-gray-300 shadow-sm">
            <div class="flex-1 min-w-0 w-full relative z-0" id="map" style="background-color: #f0f0f0;">
                @include('components.map-status-legend')
            </div>

            <div id="projectSidebar" class="rounded-3xl border border-gray-200 bg-white shadow-sm overflow-hidden order-3 md:order-2 flex flex-col" style="max-height: calc(100vh - 18rem);">
                <div class="p-6 border-b border-gray-200 bg-white">
                    <div>
                        <h2 class="text-lg font-bold text-black">Projects Overview</h2>
                        <p class="text-sm text-gray-500 mt-1">Tap a barangay on the map or browse all projects.</p>
                    </div>
                    <div id="departmentSidebarAction" class="mt-4"></div>
                </div>
                <div id="departmentProjectList" class="space-y-4 overflow-y-auto bg-slate-50 p-4 min-h-0 flex-1"></div>
            </div>
        </div>
    </main>

    {{-- ============ FOOTER (same as landing page) ============ --}}
    <footer class="bg-slate-100 border-t border-slate-200">
        <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 w-full max-w-7xl mx-auto">
            <div class="mb-8 md:mb-0">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-slate-900 rounded flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xs">account_balance</span>
                    </div>
                    <span class="font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                </div>
                <p class="text-xs uppercase tracking-widest text-slate-500">© {{ date('Y') }} Cabuyao City Government. All rights reserved.</p>
            </div>

            <div class="flex flex-wrap justify-center gap-8 text-xs uppercase tracking-widest">
                <a href="{{ route('public.map') }}" class="text-slate-500 hover:text-emerald-600 transition-all duration-300 underline decoration-emerald-500/30 underline-offset-4">Public Map</a>
                <a href="{{ route('login') }}" class="text-slate-500 hover:text-emerald-600 transition-all duration-300 underline decoration-emerald-500/30 underline-offset-4">Login</a>
            </div>
        </div>
    </footer>

    <div class="public-image-lightbox" id="publicMapProjectLightbox" aria-hidden="true">
        <div class="public-image-lightbox-toolbar">
            <button type="button" class="public-image-lightbox-button" id="publicMapProjectZoomOut" aria-label="Zoom out" title="Zoom out">−</button>
            <button type="button" class="public-image-lightbox-button" id="publicMapProjectZoomReset" aria-label="Reset zoom" title="Reset zoom">1:1</button>
            <button type="button" class="public-image-lightbox-button" id="publicMapProjectZoomIn" aria-label="Zoom in" title="Zoom in">+</button>
            <button type="button" class="public-image-lightbox-button" id="publicMapProjectLightboxClose" aria-label="Close image" title="Close">×</button>
        </div>
        <img class="public-image-lightbox-image" id="publicMapProjectLightboxImage" alt="Full-size project image">
    </div>

    {{-- Leaflet JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectList = document.getElementById('departmentProjectList');
            const selectedClass = 'bg-slate-50 border border-slate-200';
            const lightbox = document.getElementById('publicMapProjectLightbox');
            const lightboxImage = document.getElementById('publicMapProjectLightboxImage');
            const closeButton = document.getElementById('publicMapProjectLightboxClose');
            const zoomInButton = document.getElementById('publicMapProjectZoomIn');
            const zoomOutButton = document.getElementById('publicMapProjectZoomOut');
            const zoomResetButton = document.getElementById('publicMapProjectZoomReset');
            let selectedProjectIndex = null;
            let map = null;
            let boundedArea = null;
            let projectFeatures = [];
            let barangayLayer = null;
            let selectedBarangayLayer = null;
            let selectedBarangayName = null;
            const markersByBarangay = {}; // barangay name -> array of Leaflet markers
            let allMarkers = null; // featureGroup holding every marker
            let publicProjectZoom = 1;

            function barangayColor(name) {
                let hash = 0;
                for (let i = 0; i < name.length; i++) {
                    hash = name.charCodeAt(i) + ((hash << 5) - hash);
                }
                const hue = Math.abs(hash) % 360;
                return `hsl(${hue}, 65%, 55%)`;
            }

            function formatCurrency(value) {
                return `₱${Number(value || 0).toLocaleString()}`;
            }

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function setPublicProjectZoom(value) {
                publicProjectZoom = Math.min(4, Math.max(0.5, value));
                lightboxImage.style.transform = 'scale(' + publicProjectZoom + ')';
            }

            function closePublicProjectLightbox() {
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
                lightboxImage.removeAttribute('src');
                setPublicProjectZoom(1);
            }

            function bindPublicProjectImageLightboxTriggers() {
                document.querySelectorAll('.public-project-lightbox-trigger').forEach(function (image) {
                    image.addEventListener('click', function (event) {
                        event.stopPropagation();
                        lightboxImage.src = image.dataset.fullImage || image.src;
                        lightbox.classList.add('is-open');
                        lightbox.setAttribute('aria-hidden', 'false');
                        setPublicProjectZoom(1);
                    });
                });
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

            function getStatusClass(status) {
                const map = {
                    'Proposed': 'public-status-proposed',
                    'Planning': 'public-status-proposed',
                    'For bidding': 'public-status-bidding',
                    'Procurement': 'public-status-bidding',
                    'Bidding ongoing': 'public-status-ongoing',
                    'Bidding - Success': 'public-status-award',
                    'Award of contract': 'public-status-award',
                    'Implementation': 'public-status-implementation',
                    'On Going': 'public-status-implementation',
                    'Completed': 'public-status-completed',
                    'On Hold': 'public-status-hold',
                    'Cancelled': 'public-status-cancelled'
                };

                return map[status] || 'public-status-proposed';
            }

            function renderLifecycleStepper(status) {
                const steps = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed'];
                const stageByStatus = {
                    Proposed: 0,
                    'For bidding': 1,
                    'Bidding ongoing': 2,
                    'Award of contract': 3,
                    Implementation: 4,
                    Completed: 5,
                    Planning: 0,
                    Procurement: 1,
                    'Bidding - Success': 3,
                    'On Going': 4
                };
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
                const allocatedBudget = Number(props.budget || 0);
                const expenditure = Number(props.actual_budget || 0);
                const expenditureProgress = allocatedBudget > 0 ? Math.min(100, Math.max(0, (expenditure / allocatedBudget) * 100)) : 0;
                const startDate = props.start_date ? new Date(props.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                const targetDate = props.target_end_date ? new Date(props.target_end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                const lifecycleHtml = renderLifecycleStepper(props.status);
                const statusClass = getStatusClass(props.status);
                const description = escapeHtml(props.description || 'No description available.');

                const imageHtml = props.image
                    ? `<img src="${props.image}" alt="${props.name}" class="public-project-lightbox-trigger h-40 w-full rounded-2xl object-cover bg-slate-100" data-full-image="${props.image}">`
                    : '<div class="h-40 w-full rounded-2xl bg-gray-100 flex items-center justify-center text-xs text-gray-500">No image</div>';
                const collapsedImageHtml = props.image
                    ? `<div class="public-project-image-wrap"><img src="${props.image}" alt="${props.name}" class="public-project-image"><div class="public-project-image-overlay"></div></div>`
                    : '<div class="h-40 w-full rounded-2xl bg-gray-100 flex items-center justify-center text-xs text-gray-500">No image</div>';

                if (isSingle) {
                    return `
                        <div class="public-map-project-card department-project-card cursor-pointer overflow-hidden rounded-[24px] border-2 border-slate-300 bg-white text-slate-800 shadow-md" data-index="${index}">
                            <div class="p-5 sm:p-6">
                                <div class="mb-4 flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500">Selected project</p>
                                        <h3 class="mt-2 text-xl font-semibold text-slate-900">${props.name}</h3>
                                    </div>
                                    <span class="public-status-badge ${statusClass}">${props.status || 'Unknown'}</span>
                                </div>
                                <div class="mb-4 overflow-hidden rounded-2xl bg-slate-50">${imageHtml}</div>
                                ${lifecycleHtml}
                                <div class="public-project-details-card rounded-2xl p-4">
                                    <div class="mb-3 flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-slate-900">Project Details</h4>
                                    </div>
                                    <div class="public-project-details-grid">
                                        <div class="public-project-detail"><span class="public-project-detail-icon purple material-symbols-outlined">location_on</span><div><div class="public-project-detail-label">Barangay</div><div class="public-project-detail-value">${props.barangay || 'Not specified'}</div></div></div>
                                        <div class="public-project-detail"><span class="public-project-detail-icon emerald material-symbols-outlined">payments</span><div><div class="public-project-detail-label">Allocated Budget</div><div class="public-project-detail-value">${formatCurrency(allocatedBudget)}</div></div></div>
                                        <div class="public-project-detail"><span class="public-project-detail-icon amber material-symbols-outlined">trending_up</span><div><div class="public-project-detail-label">Reported Progress</div><div class="public-project-detail-value">${reportedProgress === null ? 'Not reported' : reportedProgress.toFixed(1) + '%'}</div></div></div>
                                        <div class="public-project-detail"><span class="public-project-detail-icon rose material-symbols-outlined">account_balance_wallet</span><div><div class="public-project-detail-label">Expenditure</div><div class="public-project-detail-value">${formatCurrency(expenditure)}</div></div></div>
                                        </div>
                                        <div class="mt-3 border-t border-slate-300 pt-3"><div class="flex items-center justify-between text-xs text-slate-500"><span>Expenditure progress</span><span class="font-semibold text-slate-700">${expenditureProgress.toFixed(1)}%</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-emerald-500" style="width: ${expenditureProgress}%"></div></div></div>
                                        <div class="mt-3 pt-3 border-t border-slate-300">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-xs font-semibold text-slate-600">Timeline</span>
                                            <span class="text-xs font-bold text-slate-700">${timelineProgress.toFixed(1)}%</span>
                                        </div>
                                        <div class="h-2 bg-gray-300 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-300" style="width: ${timelineProgress}%; background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-slate-500 mt-1">
                                            <span>Start: ${startDate}</span>
                                            <span>Target: ${targetDate}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="public-project-description-card">
                                    <p>${description}</p>
                                </div>
                                <button type="button" data-barangay="${props.barangay || ''}" class="public-map-viewall show-all-projects-btn"><span class="material-symbols-outlined text-[17px]">grid_view</span>View all projects</button>
                            </div>
                        </div>
                    `;
                }

                return `
                    <div class="public-map-project-card public-collapsed-project-card department-project-card cursor-pointer overflow-hidden rounded-3xl border-2 shadow-md transition hover:shadow-md" data-index="${index}">
                        <div class="overflow-hidden">${collapsedImageHtml}</div>
                        <div class="p-4">
                            <h3 class="text-base font-semibold text-slate-900">${props.name}</h3>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span class="public-status-badge ${statusClass}">${props.status || 'Unknown'}</span>
                                <p class="public-collapsed-barangay">${props.barangay || 'Barangay not specified'}</p>
                            </div>
                            <div class="public-project-details-card mt-4 rounded-2xl p-3">
                                <div class="mb-3 flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-slate-900">Project Details</h4>
                                </div>
                                <div class="public-project-details-grid">
                                    <div class="public-project-detail"><span class="public-project-detail-icon amber material-symbols-outlined">trending_up</span><div><div class="public-project-detail-label">Reported Progress</div><div class="public-project-detail-value">${reportedProgress === null ? 'Not reported' : reportedProgress.toFixed(1) + '%'}</div></div></div>
                                    <div class="public-project-detail"><span class="public-project-detail-icon emerald material-symbols-outlined">payments</span><div><div class="public-project-detail-label">Budget</div><div class="public-project-detail-value">${formatCurrency(props.budget)}</div></div></div>
                                </div>
                            </div>
                            <div class="public-project-description-card mt-3">
                                <p class="max-h-20 overflow-hidden break-words" style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow-wrap:anywhere;">${description}</p>
                            </div>
                        </div>
                    </div>
                `;
            }

            function updateSidebarAction() {
                const actionContainer = document.getElementById('departmentSidebarAction');

                if (selectedBarangayName) {
                    actionContainer.innerHTML = `
                        <button type="button" id="backToAllBarangays" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to all barangays
                        </button>
                    `;
                    document.getElementById('backToAllBarangays').addEventListener('click', resetToAllBarangays);
                } else {
                    actionContainer.innerHTML = '';
                }
            }

            function clearSelection() {
                document.querySelectorAll('.department-project-card').forEach(function(card) {
                    card.classList.remove('border', 'border-slate-200', 'bg-slate-50');
                });
            }

            function highlightProject(index) {
                selectedProjectIndex = index;
                clearSelection();
                const card = document.querySelector(`.department-project-card[data-index="${index}"]`);
                if (card) {
                    card.classList.add('border', 'border-slate-200', 'bg-slate-50');
                    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }

            closeButton.addEventListener('click', closePublicProjectLightbox);
            zoomInButton.addEventListener('click', function () { setPublicProjectZoom(publicProjectZoom + 0.25); });
            zoomOutButton.addEventListener('click', function () { setPublicProjectZoom(publicProjectZoom - 0.25); });
            zoomResetButton.addEventListener('click', function () { setPublicProjectZoom(1); });
            lightbox.addEventListener('click', function (event) { if (event.target === lightbox) closePublicProjectLightbox(); });
            lightboxImage.addEventListener('wheel', function (event) {
                event.preventDefault();
                setPublicProjectZoom(publicProjectZoom + (event.deltaY < 0 ? 0.25 : -0.25));
            }, { passive: false });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && lightbox.classList.contains('is-open')) {
                    closePublicProjectLightbox();
                }
            });

            function renderProjectList(projects) {
                const isSingle = projects.length === 1;
                updateSidebarAction();

                if (projects.length === 0) {
                    projectList.innerHTML = `<div class="p-6 text-sm text-gray-500">No public projects recorded in ${selectedBarangayName} yet.</div>`;
                    return;
                }

                projectList.innerHTML = projects.map(function(project) {
                    return renderProjectCard(project, project.originalIndex, isSingle);
                }).join('');

                bindPublicProjectImageLightboxTriggers();

                const cards = document.querySelectorAll('.department-project-card');
                cards.forEach(function(card) {
                    card.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-index'), 10);
                        selectProject(projectFeatures[index], index);
                    });
                });

                document.querySelectorAll('.show-all-projects-btn').forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation();
                        const barangay = this.dataset.barangay || null;
                        showAllProjects(barangay);
                    });
                });
            }

            function showAllProjects() {
                selectedProjectIndex = null;
                // If a specific barangay is requested, show only that barangay's projects and focus markers.
                if (typeof arguments[0] === 'string' && arguments[0]) {
                    const targetBarangay = arguments[0];
                    selectedBarangayName = targetBarangay;

                    focusBarangayByName(targetBarangay);
                    return;
                }

                const activeList = selectedBarangayName
                    ? projectFeatures.filter(p => p.properties.barangay === selectedBarangayName)
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

                if (allMarkers) {
                    map.addLayer(allMarkers);
                }

                selectedProjectIndex = null;
                renderProjectList(projectFeatures);

                if (map && boundedArea) {
                    map.fitBounds(boundedArea, { padding: [24, 24] });
                }
            }

            function selectBarangayOnMap(layer, name) {
                if (selectedBarangayLayer) {
                    barangayLayer.resetStyle(selectedBarangayLayer);
                }
                selectedBarangayLayer = layer;
                layer.setStyle({ fillOpacity: 0.75, weight: 3, color: '#162347' });
                selectedBarangayName = name;
                selectedProjectIndex = null;

                map.fitBounds(layer.getBounds(), { padding: [40, 40] });

                // Show only markers belonging to this barangay
                if (allMarkers) {
                    map.removeLayer(allMarkers);
                }
                (markersByBarangay[name] || []).forEach(marker => marker.addTo(map));

                const filtered = projectFeatures.filter(p => p.properties.barangay === name);
                renderProjectList(filtered);
            }

            function focusBarangayByName(name) {
                let targetLayer = null;

                if (barangayLayer) {
                    barangayLayer.eachLayer(function(layer) {
                        if (layer.feature?.properties?.name === name) {
                            targetLayer = layer;
                        }
                    });
                }

                if (targetLayer) {
                    selectBarangayOnMap(targetLayer, name);
                    return;
                }

                // Keep the project list useful even if the map data has no matching polygon.
                selectedBarangayName = name;
                selectedProjectIndex = null;
                const filtered = projectFeatures.filter(p => p.properties.barangay === name);
                renderProjectList(filtered);
            }

            fetch('{{ asset('data/cabuyao-map.geojson') }}')
                .then(response => response.json())
                .then(function(geojson) {
                    const cabuyaoBounds = L.geoJSON(geojson).getBounds();
                    boundedArea = cabuyaoBounds.pad(0.02);
                    map = L.map('map', {
                        maxBounds: boundedArea,
                        maxBoundsViscosity: 1.0
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: 'OpenStreetMap contributors',
                        maxZoom: 19,
                        minZoom: 11
                    }).addTo(map);

                    // Draw barangay shapes
                    barangayLayer = L.geoJSON(geojson, {
                        style: (feature) => ({
                            fillColor: barangayColor(feature.properties.name),
                            fillOpacity: 0.35,
                            color: '#ffffff',
                            weight: 1.5,
                        }),
                        onEachFeature: (feature, layer) => {
                            const name = feature.properties.name;
                            layer.bindTooltip(name, { sticky: true, className: 'barangay-tooltip' });
                            layer.on({
                                mouseover: (e) => {
                                    if (layer !== selectedBarangayLayer) e.target.setStyle({ fillOpacity: 0.6, weight: 2.5 });
                                },
                                mouseout: (e) => {
                                    if (layer !== selectedBarangayLayer) barangayLayer.resetStyle(e.target);
                                },
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
                            'Cancelled': '#64748b'
                        };
                        return colors[String(status ?? '').trim()] || '#64748b';
                    }

                    allMarkers = L.featureGroup();
                    projectFeatures = [];
                    window.projectFeatures = projectFeatures;

                    fetch('{{ route('api.public.projects.geojson') }}')
                        .then(response => response.json())
                        .then(function(projectData) {
                            if (!projectData || !projectData.features) {
                                throw new Error('Invalid project data');
                            }

                            projectData.features.forEach(function(project, index) {
                                const coords = project.geometry && project.geometry.coordinates;
                                if (!coords || coords.length < 2) {
                                    return;
                                }

                                const marker = L.circleMarker([coords[1], coords[0]], {
                                    radius: 12,
                                    fillColor: getMarkerColor(project.properties.status),
                                    color: '#ffffff',
                                    weight: 2,
                                    opacity: 1,
                                    fillOpacity: 0.9
                                });

                                marker.bindPopup(`<div class="text-sm" style="color: ${document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#000'}"><h4 class="font-bold" style="color: ${document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#000'}">${project.properties.name}</h4><p class="text-xs" style="color: ${document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#4b5563'}">${project.properties.status || 'Unknown'}</p></div>`);
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

                            function restoreListOnMapClick() {
                                if (selectedProjectIndex !== null && !selectedBarangayName) {
                                    showAllProjects();
                                }
                            }

                            map.on('click', restoreListOnMapClick);
                            allMarkers.addTo(map);
                            renderProjectList(projectFeatures);
                        })
                        .catch(function(error) {
                            console.error(error);
                            projectList.innerHTML = '<div class="p-6 text-sm text-gray-500">Unable to load projects.</div>';
                        });

                    map.fitBounds(boundedArea, { padding: [24, 24] });
                    map.setMaxBounds(boundedArea);
                    map.setMinZoom(map.getZoom());
                    setTimeout(() => map.invalidateSize(), 100);

                    window.addEventListener('resize', function() {
                        if (map) {
                            setTimeout(() => map.invalidateSize(), 100);
                        }
                    });
                })
                .catch(console.error);
        });

        // Sticky nav shadow on scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 20) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>
