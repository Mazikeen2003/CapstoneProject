<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics | City Transparency Portal</title>
    @include('layouts.favicon')
    @include('components.theme-init')

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Public+Sans:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-nav {
            backdrop-filter: blur(16px);
            background-color: rgba(248, 249, 255, 0.8);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* ===== ANALYTICS DESIGN SYSTEM ===== */
        .an-container {
            --an-surface: #ffffff;
            --an-raised: #f8fafc;
            --an-ink: #0f172a;
            --an-ink-secondary: #334155;
            --an-muted: #64748b;
            --an-line: rgba(15, 23, 42, 0.08);
            --an-line-strong: rgba(15, 23, 42, 0.14);
            --an-shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --an-shadow-md: 0 8px 16px -4px rgba(0,0,0,0.08), 0 4px 8px -4px rgba(0,0,0,0.04);
            width: 100%;
            max-width: 1400px !important;
            box-sizing: border-box;
        }
        html.dark-mode .an-container {
            --an-surface: #0f172a;
            --an-raised: #1e293b;
            --an-ink: #f8fafc;
            --an-ink-secondary: #cbd5e1;
            --an-muted: #94a3b8;
            --an-line: rgba(148, 163, 184, 0.2);
            --an-line-strong: rgba(148, 163, 184, 0.35);
        }

        .dark .an-container {
            --an-surface: #0f172a;
            --an-raised: #1e293b;
            --an-ink: #f8fafc;
            --an-ink-secondary: #cbd5e1;
            --an-muted: #94a3b8;
            --an-line: rgba(148, 163, 184, 0.2);
            --an-line-strong: rgba(148, 163, 184, 0.35);
        }

        .an-hero {
            position: relative;
            background: linear-gradient(135deg, #0f0d23 0%, #1e1b4b 30%, #047857 70%, #10b981 100%);
            border-radius: 20px;
            padding: 36px 40px;
            overflow: hidden;
            box-shadow: var(--an-shadow-md);
        }
        @media (min-width: 640px) { .an-hero { padding: 44px 52px; } }
        .an-hero::before {
            content: "";
            position: absolute; inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.5;
            pointer-events: none;
        }
        .an-hero::after {
            content: "";
            position: absolute; top: -40%; right: -5%;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(16,185,129,0.22) 0%, transparent 60%);
            pointer-events: none;
            animation: anGlow 10s ease-in-out infinite;
        }
        @keyframes anGlow {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.15); opacity: 1; }
        }
        .an-hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 100px;
            font-size: 0.6875rem; font-weight: 700;
            color: rgba(255,255,255,0.85);
            text-transform: uppercase; letter-spacing: 0.08em;
        }
        .an-hero-badge .material-symbols-outlined { font-size: 16px; }
        .an-hero-title {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(1.75rem, 4vw, 2.75rem);
            font-weight: 800; color: #ffffff;
            line-height: 1.15; letter-spacing: -0.03em;
        }
        .an-hero-subtitle { font-size: 1rem; color: rgba(255,255,255,0.68); font-family: 'Public Sans', sans-serif; }
        .an-hero-subtitle .material-symbols-outlined { font-size: 18px; vertical-align: -3px; opacity: 0.8; }

        .an-card {
            background: var(--an-surface);
            border: 1px solid var(--an-line);
            border-radius: 16px;
            box-shadow: var(--an-shadow-sm);
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
            position: relative;
            overflow: hidden;
        }
        .an-card:hover { transform: translateY(-3px); box-shadow: var(--an-shadow-md); }
        .an-card::before {
            content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            opacity: 0; transition: opacity 0.25s;
        }
        .an-card:hover::before { opacity: 1; }
        .an-card.accent-indigo::before { background: linear-gradient(90deg,#1e1b4b,#4338ca); }
        .an-card.accent-emerald::before { background: linear-gradient(90deg,#10b981,#059669); }
        .an-card.accent-blue::before { background: linear-gradient(90deg,#3b82f6,#2563eb); }
        .an-card.accent-rose::before { background: linear-gradient(90deg,#ef4444,#dc2626); }
        .an-card.accent-amber::before { background: linear-gradient(90deg,#f59e0b,#d97706); }

        .an-icon-chip {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .an-icon-chip .material-symbols-outlined { font-size: 22px; }
        .an-icon-chip.indigo { background: #e0e7ff; color: #4338ca; }
        .an-icon-chip.emerald { background: #d1fae5; color: #059669; }
        .an-icon-chip.blue { background: #dbeafe; color: #2563eb; }
        .an-icon-chip.rose { background: #ffe4e6; color: #e11d48; }
        .an-icon-chip.amber { background: #fef3c7; color: #b45309; }
        html.dark-mode .an-icon-chip.indigo { background: rgba(99,102,241,0.14); color: #a5b4fc; }
        html.dark-mode .an-icon-chip.emerald { background: rgba(16,185,129,0.14); color: #34d399; }
        html.dark-mode .an-icon-chip.blue { background: rgba(59,130,246,0.14); color: #60a5fa; }
        html.dark-mode .an-icon-chip.rose { background: rgba(244,63,94,0.14); color: #fb7185; }
        html.dark-mode .an-icon-chip.amber { background: rgba(251,191,36,0.14); color: #fbbf24; }

        .an-label {
            font-family: 'Public Sans', sans-serif;
            font-size: 0.6875rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--an-muted);
        }
        .an-value {
            font-family: 'Manrope', sans-serif;
            font-size: 2rem; font-weight: 800;
            letter-spacing: -0.02em; line-height: 1.1;
            color: var(--an-ink);
        }
        .an-kpi-value.indigo { color: #1e1b4b; }
        .an-kpi-value.emerald { color: #059669; }
        .an-kpi-value.blue { color: #2563eb; }
        .an-kpi-value.rose { color: #dc2626; }
        .an-kpi-value.amber { color: #f59e0b; }

        html.dark-mode .an-kpi-value.indigo,
        .dark .an-kpi-value.indigo { color: #312e81 !important; }
        html.dark-mode .an-kpi-value.emerald,
        .dark .an-kpi-value.emerald { color: #10b981 !important; }
        html.dark-mode .an-kpi-value.blue,
        .dark .an-kpi-value.blue { color: #2563eb !important; }
        html.dark-mode .an-kpi-value.rose,
        .dark .an-kpi-value.rose { color: #ef4444 !important; }
        html.dark-mode .an-kpi-value.amber,
        .dark .an-kpi-value.amber { color: #fbbf24 !important; }
        .an-value.sm { font-size: 1.5rem; }

        .an-lifecycle-item {
            background: var(--an-raised);
            border: 1px solid var(--an-line);
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
        }
        .an-lifecycle-item:hover {
            background: var(--an-surface);
            border-color: var(--an-line-strong);
            box-shadow: var(--an-shadow-md);
            transform: translateY(-2px);
        }
        .an-track { height: 8px; background: var(--an-line); border-radius: 100px; overflow: hidden; }
        .an-fill { height: 100%; border-radius: 100px; transition: width 0.8s cubic-bezier(0.4,0,0.2,1); position: relative; }
        .an-fill::after {
            content: ""; position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            animation: anShimmer 2.5s ease-in-out infinite;
        }
        @keyframes anShimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

        .an-topbar {
            content: ""; height: 3px; border-radius: 16px 16px 0 0;
            background: linear-gradient(90deg,#f59e0b,#d97706,#8b5cf6,#3b82f6,#10b981);
        }

        .an-select {
            min-width: 11rem; height: 2.5rem;
            border-radius: 10px;
            border: 1px solid var(--an-line-strong);
            background: var(--an-raised);
            color: var(--an-ink);
            padding: 0 12px;
            font-size: 0.8125rem; font-weight: 600;
            font-family: 'Public Sans', sans-serif;
        }
        .an-select:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.14); }
        .an-filter-btn {
            height: 2.5rem; padding: 0 20px;
            border-radius: 10px; border: none;
            background: linear-gradient(135deg, #047857, #10b981);
            color: #fff; font-size: 0.8125rem; font-weight: 700;
            font-family: 'Public Sans', sans-serif;
            white-space: nowrap;
            transition: all 0.15s;
        }
        .an-filter-btn:hover { background: linear-gradient(135deg, #059669, #34d399); transform: translateY(-1px); box-shadow: 0 4px 12px -2px rgba(5,150,105,0.35); }

        .an-chart-header {
            display: grid !important;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .an-chart-header > .flex.items-center { min-width: 0; }
        .an-chart-header > .flex.items-center > div:last-child { min-width: 0; }
        .an-chart-header h2 { overflow-wrap: anywhere; }
        .an-chart-header form {
            display: flex !important;
            flex-direction: row !important;
            align-items: center;
            width: auto !important;
            gap: 12px !important;
        }

        .an-chart-header .an-select { min-width: 11rem; }

        @media (max-width: 479px) {
            .an-chart-header {
                grid-template-columns: 1fr;
                align-items: stretch;
            }

            .an-chart-header form {
                width: 100% !important;
            }

            .an-chart-header .an-select { min-width: 0; flex: 1; }
        }

        .an-container > * { min-width: 0; }
        .an-hero-subtitle { line-height: 1.5; }
        .an-hero-subtitle > span { min-width: 0; }
        .an-hero-subtitle .material-symbols-outlined { flex-shrink: 0; }
        .an-card { min-width: 0; }

        @media (max-width: 1023px) {
            .an-container { padding-left: 24px !important; padding-right: 24px !important; }
            .an-container > .grid.grid-cols-2.lg\:grid-cols-5 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .an-container .an-hero { padding: 36px 40px; }
        }

        @media (min-width: 1024px) {
            .an-container { padding-left: 32px !important; padding-right: 32px !important; }
        }

        @media (max-width: 767px) {
            .an-container {
                padding: 24px 16px !important;
                gap: 24px;
            }

            .an-hero {
                padding: 28px 24px !important;
                border-radius: 16px;
            }

            .an-hero-title { font-size: clamp(1.65rem, 8vw, 2.25rem); }
            .an-hero-subtitle { display: grid; gap: 8px; font-size: 0.875rem; }
            .an-hero-badge { margin-bottom: 12px; }

            .an-container > .grid.grid-cols-2.lg\:grid-cols-5,
            .an-container > section.grid.grid-cols-2.lg\:grid-cols-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }

            .an-card { padding: 16px !important; border-radius: 14px; }
            .an-icon-chip { width: 40px; height: 40px; }
            .an-value { font-size: 1.65rem; overflow-wrap: anywhere; }
            .an-value.sm { font-size: 1.35rem; }
            .an-label { font-size: 0.625rem; letter-spacing: 0.07em; }

            .an-lifecycle-item { padding: 12px; }
            .an-lifecycle-item-label { font-size: 0.75rem; }
            .an-lifecycle-item-count { font-size: 0.875rem; }

            .an-card > .flex.flex-col.gap-3.sm\:flex-row {
                align-items: stretch;
            }

            .an-card > .flex.flex-col.gap-3.sm\:flex-row > .flex.items-center.gap-3 {
                min-width: 0;
            }

            .an-card > .flex.flex-col.gap-3.sm\:flex-row form {
                width: 100%;
            }

            .an-select { min-width: 0; flex: 1; width: 100%; }
            .an-filter-btn { width: auto; min-width: 82px; }
        }

        @media (max-width: 479px) {
            .an-container > .grid.grid-cols-2.lg\:grid-cols-5,
            .an-container > section.grid.grid-cols-2.lg\:grid-cols-4 {
                grid-template-columns: 1fr;
            }

            .an-hero { padding: 24px 20px !important; }
            .an-hero-title { font-size: 1.7rem; }
            .an-hero-subtitle { font-size: 0.8125rem; }

            .an-card > .flex.flex-col.gap-3.sm\:flex-row form {
                flex-direction: column;
                align-items: stretch;
            }

            .an-select, .an-filter-btn { width: 100%; }
        }

        @keyframes anFadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .an-animate { animation: anFadeUp 0.5s cubic-bezier(0.4,0,0.2,1) forwards; opacity: 0; }
        .an-animate:nth-child(1) { animation-delay: 0.04s; }
        .an-animate:nth-child(2) { animation-delay: 0.08s; }
        .an-animate:nth-child(3) { animation-delay: 0.12s; }
        .an-animate:nth-child(4) { animation-delay: 0.16s; }
        .an-animate:nth-child(5) { animation-delay: 0.20s; }

        @media (prefers-reduced-motion: reduce) {
            .an-animate { animation: none; opacity: 1; }
            .an-fill::after { animation: none; }
        }
    </style>
</head>
<body class="public-layout bg-white font-sans text-slate-900 antialiased">

    {{-- ============ TOP NAV ============ --}}
    <header class="sticky top-0 z-50 glass-nav w-full border-b border-slate-200/50">
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
                <a href="{{ route('public.map') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="text-emerald-700 font-bold border-b-2 border-emerald-600 py-2 transition-all">Analytics</a>
            </div>

            <div class="flex items-center gap-2">
                @include('components.public-theme-toggle')
                <a href="{{ route('login') }}" class="public-login-button bg-slate-900 text-white px-5 py-2.5 rounded-md font-semibold text-sm hover:opacity-90 transition-all duration-200 shrink-0">Login</a>
            </div>
        </nav>
        <div class="md:hidden border-t border-slate-200 bg-white">
            <div class="flex flex-wrap items-center justify-center gap-3 px-4 py-3 text-xs uppercase tracking-widest text-slate-600">
                <a href="{{ url('/') }}" class="hover:text-emerald-700 transition-colors">Home</a>
                <a href="{{ route('public.map') }}" class="hover:text-emerald-700 transition-colors">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="text-emerald-700 font-semibold">Analytics</a>
            </div>
        </div>
    </header>

    @php
        $statusOrder = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed', 'On Hold', 'Cancelled'];
        $statusColors = ['#fbbf24', '#f59e0b', '#3b82f6', '#8b5cf6', '#0ea5e9', '#10b981', '#ef4444', '#64748b'];
        $statusAliases = ['Planning' => 'Proposed', 'Procurement' => 'For bidding', 'Bidding - Success' => 'Award of contract', 'On Going' => 'Implementation'];
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

    <main class="an-container max-w-7xl mx-auto px-6 md:px-12 py-10 space-y-8">

        {{-- HERO --}}
        <div class="an-hero an-animate">
            <div class="relative z-10">
                <div class="an-hero-badge mb-4">
                    <span class="material-symbols-outlined">monitoring</span>
                    Transparency Tool
                </div>
                <h1 class="an-hero-title mb-2">Public Project Analytics</h1>
                <div class="an-hero-subtitle flex flex-wrap items-center gap-5">
                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined">bar_chart</span>Visual overview of project progress and funding</span>
                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined">payments</span>Real-time budget tracking</span>
                </div>
            </div>
        </div>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            @foreach ([
                ['label' => 'Total Projects', 'value' => $stats['total_projects'], 'icon' => 'indigo', 'accent' => 'accent-indigo', 'symbol' => 'dataset'],
                ['label' => 'Completed',      'value' => $stats['completed'],      'icon' => 'emerald', 'accent' => 'accent-emerald', 'symbol' => 'task_alt'],
                ['label' => 'Ongoing',        'value' => $stats['ongoing'],        'icon' => 'blue', 'accent' => 'accent-blue', 'symbol' => 'sync'],
                ['label' => 'On Hold',        'value' => $stats['on_hold'],        'icon' => 'rose', 'accent' => 'accent-rose', 'symbol' => 'pause_circle'],
                ['label' => 'Total Budget',   'value' => $totalBudgetDisplay,      'icon' => 'amber', 'accent' => 'accent-amber', 'symbol' => 'payments'],
            ] as $kpi)
                <div class="an-card {{ $kpi['accent'] }} an-animate p-5">
                    <div class="an-icon-chip {{ $kpi['icon'] }} mb-4">
                        <span class="material-symbols-outlined">{{ $kpi['symbol'] }}</span>
                    </div>
                    <p class="an-label mb-2">{{ $kpi['label'] }}</p>
                    <p class="an-value an-kpi-value {{ $kpi['icon'] }}">{{ $kpi['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- INSIGHT CARDS --}}
        <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([
                ['label' => 'Needs attention',   'value' => $insights['overdue'], 'caption' => 'Overdue projects', 'icon' => 'rose', 'accent' => 'accent-rose', 'symbol' => 'warning', 'color' => '#dc2626'],
                ['label' => 'Coming up',         'value' => $insights['due_soon'], 'caption' => 'Due within 30 days', 'icon' => 'amber', 'accent' => 'accent-amber', 'symbol' => 'schedule', 'color' => '#d97706'],
                ['label' => 'Missing updates',   'value' => $insights['without_updates'], 'caption' => 'Active projects', 'icon' => 'blue', 'accent' => 'accent-blue', 'symbol' => 'sync_problem', 'color' => '#2563eb'],
                ['label' => 'Budget used',       'value' => number_format($insights['budget_utilization'], 1) . '%', 'caption' => 'Actual versus approved', 'icon' => 'emerald', 'accent' => 'accent-emerald', 'symbol' => 'trending_up', 'color' => '#059669'],
            ] as $insight)
                <div class="an-card {{ $insight['accent'] }} an-animate p-5">
                    <div class="an-icon-chip {{ $insight['icon'] }} mb-3" style="width:38px;height:38px;">
                        <span class="material-symbols-outlined" style="font-size:19px;">{{ $insight['symbol'] }}</span>
                    </div>
                    <p class="an-label mb-2">{{ $insight['label'] }}</p>
                    <p class="an-value sm mb-1" style="color: {{ $insight['color'] }};">{{ $insight['value'] }}</p>
                    <p class="text-xs font-semibold" style="color: var(--an-muted); font-family:'Public Sans',sans-serif;">{{ $insight['caption'] }}</p>
                </div>
            @endforeach
        </section>

        {{-- LIFECYCLE OVERVIEW --}}
        <section class="an-card an-animate p-6 md:p-7">
            <div class="an-topbar absolute top-0 left-0 right-0"></div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="an-icon-chip indigo" style="background:#ede9fe;color:#7c3aed;">
                        <span class="material-symbols-outlined">timeline</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold" style="font-family:'Manrope',sans-serif;color:var(--an-ink);">Lifecycle Overview</h2>
                        <p class="text-sm" style="color:var(--an-muted);">Projects grouped by their current lifecycle stage.</p>
                    </div>
                </div>
                <span class="text-xs font-semibold px-3 py-1.5 rounded-full" style="background:var(--an-raised);border:1px solid var(--an-line);color:var(--an-muted);font-family:'Public Sans',sans-serif;">Completed and exception statuses remain visible</span>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($statusOrder as $statusIndex => $lifecycleStatus)
                    @php
                        $lifecycleCount = $insights['lifecycle_counts'][$lifecycleStatus] ?? 0;
                        $pct = $stats['total_projects'] > 0 ? min(100, ($lifecycleCount / $stats['total_projects']) * 100) : 0;
                    @endphp
                    <div class="an-lifecycle-item">
                        <div class="flex items-center justify-between gap-3 text-sm mb-3">
                            <span class="font-semibold" style="color:var(--an-ink-secondary);">{{ $lifecycleStatus }}</span>
                            <span class="font-bold" style="font-family:'Manrope',sans-serif;color:var(--an-ink);">{{ $lifecycleCount }}</span>
                        </div>
                        <div class="an-track">
                            <div class="an-fill" style="width: {{ $pct }}%; background: linear-gradient(90deg, {{ $statusColors[$statusIndex] }}, {{ $statusColors[$statusIndex] }}dd);"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- CHARTS --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section class="an-card an-animate p-6">
                <div class="an-chart-header mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="an-icon-chip blue">
                            <span class="material-symbols-outlined">pie_chart</span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold" style="font-family:'Manrope',sans-serif;color:var(--an-ink);">Project Status Distribution</h2>
                            <p class="text-xs" style="color:var(--an-muted);">Breakdown by lifecycle stage</p>
                        </div>
                    </div>
                    <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-end sm:gap-3 w-full sm:w-auto">
                        <input type="hidden" name="budget_year" value="{{ $budgetYear }}">
                        <select aria-label="Filter project status by year" name="status_year" class="an-select">
                            <option value="">All years</option>
                            @foreach ($availableYears as $year)
                                <option value="{{ $year }}" @selected((string) $statusYear === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="an-filter-btn">Filter</button>
                    </form>
                </div>
                <div class="h-80"><canvas id="statusChart"></canvas></div>
            </section>

            <section class="an-card an-animate p-6">
                <div class="an-chart-header mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="an-icon-chip amber">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <div>
                            <h2 class="text-base font-bold" style="font-family:'Manrope',sans-serif;color:var(--an-ink);">Budget Comparison</h2>
                            <p class="text-xs" style="color:var(--an-muted);">Allocated vs spent vs remaining</p>
                        </div>
                    </div>
                    <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-end sm:gap-3 w-full sm:w-auto">
                        <input type="hidden" name="status_year" value="{{ $statusYear }}">
                        <select aria-label="Filter budget comparison by year" name="budget_year" class="an-select">
                            <option value="">All years</option>
                            @foreach ($availableYears as $year)
                                <option value="{{ $year }}" @selected((string) $budgetYear === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="an-filter-btn">Filter</button>
                    </form>
                </div>
                <div class="h-80"><canvas id="budgetChart"></canvas></div>
            </section>
        </div>

        {{-- BARANGAY BUDGET SHARE --}}
        @if (isset($byBarangay))
            <section class="an-card an-animate p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="an-icon-chip emerald">
                        <span class="material-symbols-outlined">location_city</span>
                    </div>
                    <div>
                        <h2 class="barangay-budget-share-heading text-base font-bold" style="font-family:'Manrope',sans-serif;color:var(--an-ink);">Barangay Budget Share</h2>
                        <p class="text-xs" style="color:var(--an-muted);">Top 10 barangays by allocated budget</p>
                    </div>
                </div>
                <div class="h-96"><canvas id="barangayChart"></canvas></div>
            </section>
        @endif

        {{-- EMPTY STATE --}}
        @if ($stats['total_projects'] === 0)
            <div class="an-animate flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                <span class="material-symbols-outlined">info</span>
                No project data is available yet.
            </div>
        @endif
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-slate-100 border-t border-slate-200 mt-12">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('theme:changed', () => window.location.reload());

        const analyticsScrollPosition = sessionStorage.getItem('analyticsScrollPosition');
        if (analyticsScrollPosition !== null) {
            sessionStorage.removeItem('analyticsScrollPosition');
            window.scrollTo(0, Number(analyticsScrollPosition));
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
        Chart.defaults.font.family = "'Public Sans', sans-serif";
        const statusColors = darkMode
            ? ['#fcd34d', '#fbbf24', '#60a5fa', '#c4b5fd', '#38bdf8', '#34d399', '#fb7185', '#94a3b8']
            : @json($statusColors);
        const peso = value => '₱' + Number(value || 0).toLocaleString();
        const smoothAnimation = { duration: 1300, easing: 'easeOutQuart' };
        const smoothHover = { mode: 'nearest', intersect: true, animationDuration: 420 };

        new Chart(document.getElementById('statusChart'), {
            type: 'polarArea',
            data: { labels: statusLabels, datasets: [{ data: statusCounts, backgroundColor: statusColors, hoverOffset: 18, borderWidth: 2, borderColor: darkMode ? '#1e293b' : '#ffffff' }] },
            options: { responsive: true, maintainAspectRatio: false, animation: smoothAnimation, hover: smoothHover, scales: { r: { ticks: { precision: 0, color: chartTextColor }, grid: { color: chartGridColor } } }, plugins: { legend: { position: 'bottom', labels: { color: chartTextColor, padding: 18, usePointStyle: true, pointStyle: 'circle' } } } }
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
</body>
</html>