<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Public monthly expenditure details for {{ $project->project_name }}.">
    <title>{{ $project->project_name }} Expenditure | City Transparency Portal</title>
    @include('layouts.favicon')
    @include('components.theme-init')

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Public+Sans:wght@400;600;700&display=swap">
    {{-- Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Base ── */
        body {
            background-color: #fdfcf7;
            color: #111827;
            font-family: 'Inter', sans-serif;
        }
        html.dark-mode body {
            background-color: #111111;
            color: #f8fafc;
        }
        html.dark-mode body.public-layout {
            background-color: #1a1a1a !important;
        }
        html.dark-mode body.public-layout > main {
            background-color: #1a1a1a !important;
        }
        html:not(.dark-mode) body {
            background-color: #fdfcf7 !important;
            color: #111827 !important;
        }
        html:not(.dark-mode) .expenditure-nav-title {
            color: #0f172a !important;
        }
        html:not(.dark-mode) .expenditure-card .text-slate-900,
        html:not(.dark-mode) .expenditure-summary-card .text-slate-900 {
            color: #0f172a !important;
        }
        html:not(.dark-mode) .expenditure-summary-card,
        html.dark-mode .expenditure-summary-card {
            align-items: flex-start;
            min-height: 112px;
            padding: 14px 0 0;
            border: 0;
            border-top: 2px solid var(--summary-accent);
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            text-align: left;
        }
        html:not(.dark-mode) .expenditure-summary-card--total,
        html.dark-mode .expenditure-summary-card--total {
            --summary-accent: #e11d48;
        }
        html:not(.dark-mode) .expenditure-summary-card--months,
        html.dark-mode .expenditure-summary-card--months {
            --summary-accent: #f59e0b;
        }
        html:not(.dark-mode) .expenditure-summary-card--categories,
        html.dark-mode .expenditure-summary-card--categories {
            --summary-accent: #be185d;
        }
        html:not(.dark-mode) .summary-icon-badge,
        html.dark-mode .summary-icon-badge {
            display: none;
        }
        html:not(.dark-mode) .expenditure-summary-card .summary-value,
        html.dark-mode .expenditure-summary-card .summary-value {
            margin-top: 0;
            font-size: 2rem;
            line-height: 1.2;
        }
        html:not(.dark-mode) .expenditure-summary-card .summary-label,
        html.dark-mode .expenditure-summary-card .summary-label {
            margin-top: 8px;
            font-size: 0.75rem;
            font-weight: 400;
            letter-spacing: 0;
            text-transform: none;
        }
        html:not(.dark-mode) .expenditure-summary-card .summary-value {
            color: #111827 !important;
        }
        html:not(.dark-mode) .expenditure-summary-card .summary-label {
            color: #64748b !important;
        }
        html.dark-mode .expenditure-summary-card .summary-value {
            color: #f8fafc !important;
        }
        html.dark-mode .expenditure-summary-card .summary-label {
            color: #a3a3a3 !important;
        }
        html.dark-mode .expenditure-summary-card--total .summary-value {
            color: #a7f3d0 !important;
        }
        html.dark-mode .expenditure-nav-title {
            color: #f8fafc !important;
        }

        /* ── Navigation ── */
        .glass-nav {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background-color: rgba(248, 249, 255, 0.8);
        }
        html.dark-mode .glass-nav {
            background-color: rgba(17, 17, 17, 0.82) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.dark-mode body .landing-login-button {
            border: 1px solid #065f46 !important;
            background-color: #222222 !important;
            color: #e5e7eb !important;
        }
        html.dark-mode body .landing-login-button:hover {
            background-color: #1a1a1a !important;
            border-color: #047857 !important;
        }

        /* ── Hero header ── */
        .expenditure-hero {
            background:
                linear-gradient(90deg, rgba(9, 20, 43, 0.92) 0%, rgba(9, 20, 43, 0.78) 45%, rgba(9, 20, 43, 0.35) 100%),
                linear-gradient(0deg, rgba(8, 13, 23, 0.55) 0%, transparent 60%),
                var(--project-hero-image, linear-gradient(135deg, #243b53, #111827)) center 30% / cover no-repeat;
        }
        .expenditure-hero-title {
            max-width: 52rem;
            text-wrap: balance;
            text-shadow: 0 3px 24px rgba(0, 0, 0, 0.38);
        }
        .expenditure-hero-meta {
            text-shadow: 0 2px 14px rgba(0, 0, 0, 0.42);
        }
        .expenditure-hero-meta > span {
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 9999px;
            background: rgba(15, 23, 42, 0.32);
            padding: 0.35rem 0.7rem;
            backdrop-filter: blur(8px);
        }

        /* ── Cards ── */
        .expenditure-card {
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid #e7e5dc;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
        }
        html.dark-mode .expenditure-card {
            background: #222222;
            border-color: #303030;
            box-shadow: 0 20px 25px -8px rgba(0, 0, 0, 0.55);
        }
        html.dark-mode .expenditure-card .text-slate-900,
        html.dark-mode .expenditure-card .text-slate-800 {
            color: #f8fafc !important;
        }
        html.dark-mode .expenditure-card .text-slate-700,
        html.dark-mode .expenditure-card .text-slate-600,
        html.dark-mode .expenditure-card .text-slate-500 {
            color: #cbd5e1 !important;
        }

        /* ── Summary stats ── */
        .expenditure-summary-card {
            background: #ffffff;
            border: 1px solid #dbe2e8;
            box-shadow: 0 14px 28px -12px rgba(15, 23, 42, 0.28);
        }
        html.dark-mode .expenditure-summary-card {
            background: #1a1a1a;
            border-color: #303030;
            box-shadow: 0 20px 25px -8px rgba(0, 0, 0, 0.55);
        }
        html.dark-mode .expenditure-summary-card .text-slate-900 {
            color: #f8fafc !important;
        }
        html.dark-mode .expenditure-summary-card .text-slate-500 {
            color: #94a3b8 !important;
        }

        /* ── Tables ── */
        .expenditure-table thead th {
            color: #64748b;
            font-family: 'Public Sans', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        html.dark-mode .expenditure-table thead th {
            color: #94a3b8;
        }
        .expenditure-table tbody tr {
            border-top: 1px solid #e2e8f0;
            transition: background-color 0.15s ease;
        }
        .expenditure-table tbody tr:hover {
            background-color: #f8fafc;
        }
        html.dark-mode .expenditure-table tbody tr {
            border-color: #404040;
        }
        html.dark-mode .expenditure-table tbody tr:hover {
            background-color: #262626;
        }

        /* ── Month sections ── */
        .month-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        html.dark-mode .month-header {
            background-color: #1c2420;
            border-color: rgba(255, 255, 255, 0.1);
        }
        .expenditure-disclaimer {
            background-color: #ffffff;
            border-color: #e7e5dc;
        }
        html.dark-mode .expenditure-disclaimer {
            background-color: #1a1a1a;
            border-color: #303030;
        }

        /* ── Misc text ── */
        .expenditure-muted {
            color: #64748b;
        }
        html.dark-mode .expenditure-muted {
            color: #a3a3a3 !important;
        }
        html.dark-mode .expenditure-table thead th {
            color: #a3a3a3 !important;
        }
        html.dark-mode .expenditure-table tbody td.text-slate-700 {
            color: #d4d4d4 !important;
        }
        html.dark-mode .expenditure-table tbody td.text-slate-600 {
            color: #a3a3a3 !important;
        }
        html.dark-mode .expenditure-empty-icon,
        html.dark-mode .expenditure-disclaimer .text-slate-500 {
            color: #737373 !important;
        }

        /* ── Legacy / warning boxes ── */
        .legacy-box {
            background-color: #fefce8;
            border: 1px solid #fde047;
        }
        html.dark-mode .legacy-box {
            background-color: #292524;
            border-color: #57534e;
        }
        .warning-box {
            background-color: #fffbeb;
            border: 1px solid #fcd34d;
        }
        html.dark-mode .warning-box {
            background-color: #292524;
            border-color: #78350f;
        }
        html.dark-mode .warning-box p {
            color: #fcd34d;
        }

        /* ── Footer ── */
        .landing-footer {
            background-color: #111111 !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: #e2e8f0;
        }
        .landing-footer .landing-footer-muted {
            color: #94a3b8;
        }
        .landing-footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .landing-footer a:hover {
            color: #6ee7b7;
        }
        .landing-footer-bottom {
            border-color: rgba(255, 255, 255, 0.12);
        }

        /* ── Material symbols ── */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .expenditure-summary-card .material-symbols-outlined {
            display: inline-flex;
            width: 24px;
            height: 24px;
            flex: 0 0 24px;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        /* ── Scroll shadow for tables ── */
        .table-scroll {
            position: relative;
        }
        .table-scroll::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 30px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.9));
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        html.dark-mode .table-scroll::after {
            background: linear-gradient(to right, transparent, rgba(34,34,34,0.9));
        }
        .table-scroll.can-scroll::after {
            opacity: 1;
        }
    </style>
</head>
<body class="public-layout min-h-screen antialiased">

    {{-- ============ TOP NAV ============ --}}
    <header class="sticky top-0 z-50 glass-nav w-full border-b border-slate-200/50">
        <nav class="relative mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-3 sm:px-6 sm:py-4 lg:px-12">
            <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-2 sm:gap-4">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-9 w-9 shrink-0 rounded-lg object-contain sm:h-10 sm:w-10" width="40" height="40" decoding="async">
                <span class="flex min-w-0 flex-col">
                    <span class="expenditure-nav-title truncate text-base font-bold leading-tight tracking-tighter text-slate-900 sm:text-lg md:text-xl" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="truncate text-[9px] uppercase tracking-widest text-slate-500 opacity-70 sm:text-[10px]" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </span>
            </a>

            <div class="hidden items-center gap-6 text-xs uppercase tracking-widest md:flex" style="font-family:'Public Sans',sans-serif;">
                <a href="{{ url('/') }}" class="py-2 font-semibold text-slate-500 transition-colors hover:text-emerald-700 dark:hover:text-emerald-400">Home</a>
                <a href="{{ route('public.map') }}" class="py-2 font-semibold text-slate-500 transition-colors hover:text-emerald-700 dark:hover:text-emerald-400">Public Map</a>
                <a href="{{ route('public.expenditures') }}" class="border-b-2 border-emerald-600 py-2 font-bold text-emerald-700 dark:text-emerald-400">Expenditure</a>
            </div>

            <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
                @include('components.public-theme-toggle')
                <a href="{{ route('login') }}" class="public-login-button landing-login-button rounded-md bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white transition-all duration-200 hover:opacity-90 sm:px-5 sm:py-2.5 sm:text-sm">Login</a>
            </div>
        </nav>
    </header>

    {{-- ============ HERO HEADER ============ --}}
    <section class="expenditure-hero relative flex min-h-[280px] items-end overflow-hidden sm:min-h-[340px] lg:min-h-[380px]" @if ($project->project_image) style="--project-hero-image: url('{{ asset('storage/' . $project->project_image) }}');" @endif>
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 pb-10 pt-16 sm:px-6 sm:pb-14 sm:pt-20 lg:px-12 lg:pb-16">
            <a href="{{ route('public.expenditures') }}" class="mb-5 inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/10 px-3.5 py-2 text-xs font-semibold text-white backdrop-blur-md transition hover:bg-white/20">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                All project expenditures
            </a>
            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-emerald-200 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Public project expenditure</p>
            <h1 class="expenditure-hero-title mt-3 text-3xl font-extrabold leading-[1.08] tracking-tight text-white sm:text-4xl lg:text-6xl" style="font-family:'Manrope',sans-serif;">
                {{ $project->project_name }}
            </h1>
            <p class="expenditure-hero-meta mt-3 max-w-2xl text-sm leading-6 text-slate-200 sm:text-base">
                Follow the recorded spending for this project, organized by month and category.
            </p>
            <div class="expenditure-hero-meta mt-5 flex flex-wrap gap-2 text-xs font-medium text-slate-100 sm:text-sm">
                @if ($project->project_code)
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base opacity-70">tag</span>
                        {{ $project->project_code }}
                    </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base opacity-70">location_on</span>
                    {{ $project->barangay?->barangay_name ?? 'Citywide' }}
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base opacity-70">info</span>
                    {{ $project->current_status ?? 'Status not specified' }}
                </span>
            </div>
        </div>
    </section>

    {{-- ============ SUMMARY STATS (overlapping hero) ============ --}}
    <section class="relative z-20 mt-8 flex justify-center bg-transparent px-4 sm:mt-10 sm:px-6 lg:mt-12 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4 lg:gap-6">
                <div class="expenditure-summary-card expenditure-summary-card--total flex flex-col items-center justify-center rounded-xl p-5 text-center sm:p-6 md:p-8">
                    <span class="summary-icon-badge mb-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </span>
                    <span class="summary-value mt-1 text-2xl font-extrabold text-emerald-700 dark:text-emerald-400 sm:text-3xl" style="font-family:'Manrope',sans-serif;">₱{{ number_format($totalExpenditure, 2) }}</span>
                    <span class="summary-label mt-1 text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Total recorded expenditure</span>
                </div>
                <div class="expenditure-summary-card expenditure-summary-card--months flex flex-col items-center justify-center rounded-xl p-5 text-center sm:p-6 md:p-8">
                    <span class="summary-icon-badge mb-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400">
                        <span class="material-symbols-outlined">calendar_month</span>
                    </span>
                    <span class="summary-value mt-1 text-2xl font-extrabold text-slate-900 dark:text-white sm:text-3xl" style="font-family:'Manrope',sans-serif;">{{ $monthlyExpenditure->count() }}</span>
                    <span class="summary-label mt-1 text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Months with records</span>
                </div>
                <div class="expenditure-summary-card expenditure-summary-card--categories flex flex-col items-center justify-center rounded-xl p-5 text-center sm:p-6 md:p-8">
                    <span class="summary-icon-badge mb-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-violet-50 text-violet-700 dark:bg-violet-950/40 dark:text-violet-400">
                        <span class="material-symbols-outlined">category</span>
                    </span>
                    <span class="summary-value mt-1 text-2xl font-extrabold text-slate-900 dark:text-white sm:text-3xl" style="font-family:'Manrope',sans-serif;">
                        {{ $monthlyExpenditure->sum(fn($cats) => count($cats)) }}
                    </span>
                    <span class="summary-label mt-1 text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Category entries</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ MAIN CONTENT ============ --}}
    <main class="mx-auto max-w-7xl space-y-8 px-4 py-10 sm:px-6 sm:py-14 lg:px-12">

        {{-- Monthly breakdown --}}
        <section class="expenditure-card overflow-hidden rounded-2xl">
            <div class="flex items-center gap-3 border-b border-slate-200 p-5 dark:border-neutral-700 sm:p-6">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400">
                    <span class="material-symbols-outlined">table_chart</span>
                </span>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white sm:text-xl" style="font-family:'Manrope',sans-serif;">Monthly expenditure breakdown</h2>
                    <p class="expenditure-muted mt-0.5 text-xs sm:text-sm">Monthly totals grouped by published spending category.</p>
                </div>
            </div>

            @if ($monthlyExpenditure->isEmpty() && $unclassifiedLegacyExpenditure <= 0)
                <div class="flex flex-col items-center justify-center p-10 text-center sm:p-14">
                    <span class="expenditure-empty-icon material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">receipt_long</span>
                    <p class="expenditure-muted mt-4 text-sm">No monthly expenditure has been published for this project yet.</p>
                </div>
            @else
                <div class="divide-y divide-slate-200 dark:divide-neutral-700">
                    @foreach ($monthlyExpenditure as $month => $categories)
                        <div>
                            <div class="month-header flex items-center justify-between px-5 py-3 sm:px-6">
                                <h3 class="flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white sm:text-base" style="font-family:'Manrope',sans-serif;">
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">event</span>
                                    {{ $month === 'undated' ? 'Month not recorded' : \Carbon\Carbon::createFromFormat('!Y-m', $month)->format('F Y') }}
                                </h3>
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                                    ₱{{ number_format($categories->sum('amount'), 2) }}
                                </span>
                            </div>
                            <div class="table-scroll overflow-x-auto px-5 pb-4 sm:px-6">
                                <table class="expenditure-table w-full min-w-[480px] text-left text-sm">
                                    <thead>
                                        <tr>
                                            <th class="py-3 pr-4">Category</th>
                                            <th class="py-3 pr-4 text-right">Entries</th>
                                            <th class="py-3 text-right">Monthly total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category => $details)
                                            <tr>
                                                <td class="py-3.5 pr-4 font-medium text-slate-700 dark:text-slate-200">{{ $category }}</td>
                                                <td class="py-3.5 pr-4 text-right text-slate-600 dark:text-slate-400">{{ number_format($details['entries']) }}</td>
                                                <td class="py-3.5 text-right font-bold text-emerald-700 dark:text-emerald-400">₱{{ number_format($details['amount'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($unclassifiedLegacyExpenditure > 0)
                <div class="legacy-box border-t p-5 dark:border-neutral-700 sm:p-6">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400">
                            <span class="material-symbols-outlined">history</span>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Previously recorded, unitemized expenditure</h3>
                            <p class="expenditure-muted mt-1 text-xs sm:text-sm">A legacy project total exists without matching monthly category entries.</p>
                            <p class="mt-3 text-xl font-extrabold text-yellow-700 dark:text-yellow-400 sm:text-2xl" style="font-family:'Manrope',sans-serif;">₱{{ number_format($unclassifiedLegacyExpenditure, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @unless ($trackingAvailable)
                <div class="warning-box border-t p-5 sm:p-6">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">warning</span>
                        <p class="text-sm font-medium text-amber-900 dark:text-amber-200">
                            Monthly itemized records are not enabled for this database. The total above reflects the recorded project-level amount.
                        </p>
                    </div>
                </div>
            @endunless
        </section>

        {{-- Disclaimer --}}
        <div class="expenditure-disclaimer flex items-start gap-3 rounded-xl border p-4">
            <span class="material-symbols-outlined text-slate-400 dark:text-slate-500">info</span>
            <p class="expenditure-muted text-xs leading-5">
                Information is displayed for public transparency subject to applicable laws and disclosure requirements. Contact the responsible public office regarding official records.
            </p>
        </div>
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="landing-footer mt-16 border-t">
        <div class="mx-auto grid w-full max-w-6xl gap-10 px-6 py-12 sm:px-8 md:grid-cols-[1.4fr_0.8fr_0.8fr_1fr] md:gap-12 md:py-16">
            <div>
                <a href="{{ url('/') }}" class="mb-5 inline-flex items-center gap-3">
                    <img src="{{ asset('images/cabuyao-logo.png') }}" alt="" class="h-9 w-9 rounded-full bg-white object-contain p-0.5" width="36" height="36" decoding="async">
                    <span class="font-bold text-white" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                </a>
                <p class="landing-footer-muted max-w-sm text-sm leading-6">A civic technology capstone project led by students of Pamantasan ng Cabuyao, making public projects and expenditures easier for the community to follow.</p>
                <p class="landing-footer-muted mt-4 max-w-sm text-sm leading-6">Built in partnership with the City Planning Department of Cabuyao.</p>
            </div>

            <nav aria-label="Public resources">
                <h2 class="mb-4 text-[10px] font-bold uppercase tracking-[0.22em] text-emerald-300">Public resources</h2>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('public.map') }}">Public Map</a></li>
                    <li><a href="{{ route('public.expenditures') }}">Project Expenditures</a></li>
                    <li><a href="{{ route('public.analytics') }}">Analytics</a></li>
                </ul>
            </nav>

            <nav aria-label="Account">
                <h2 class="mb-4 text-[10px] font-bold uppercase tracking-[0.22em] text-emerald-300">Account</h2>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </nav>

            <address class="not-italic">
                <h2 class="mb-4 text-[10px] font-bold uppercase tracking-[0.22em] text-emerald-300">Contacts</h2>
                <ul class="space-y-3 text-sm leading-6">
                    <li><a href="https://bettercabuyao.org/" target="_blank" rel="noopener noreferrer">bettercabuyao.org</a></li>
                    <li class="landing-footer-muted">F.B. Bailon St., Rosario Village, Brgy. Sala, Cabuyao City, 4025 Laguna</li>
                </ul>
            </address>
        </div>
        <div class="landing-footer-bottom border-t">
            <div class="landing-footer-muted mx-auto flex w-full max-w-6xl flex-col gap-2 px-6 py-5 text-xs sm:px-8 md:flex-row md:items-center md:justify-between">
                <span>© {{ date('Y') }} Cabuyao City Government. All rights reserved.</span>
            </div>
        </div>
    </footer>

    <script>
        // Sticky nav shadow on scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 20) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });

        // Table scroll hint
        document.querySelectorAll('.table-scroll').forEach(el => {
            if (el.scrollWidth > el.clientWidth) {
                el.classList.add('can-scroll');
            }
        });
    </script>
</body>
</html>
