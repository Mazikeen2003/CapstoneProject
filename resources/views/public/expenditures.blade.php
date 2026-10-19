<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse public project expenditure summaries and open a project for monthly, category-level details.">
    <title>Project Expenditures | City Transparency Portal</title>
    @include('layouts.favicon')
    @include('components.theme-init')

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=Public+Sans:wght@400;500;600;700&display=swap">
    {{-- Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-surface: #ffffff;
            --bg-surface-elevated: #ffffff;
            --bg-header: rgba(248, 250, 252, 0.85);
            --bg-input: #ffffff;
            --bg-hover: #f1f5f9;
            --bg-badge: #f1f5f9;
            --border-color: #e2e8f0;
            --border-strong: #cbd5e1;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #64748b;
            --text-muted: #94a3b8;
            --accent: #059669;
            --accent-hover: #047857;
            --accent-soft: #ecfdf5;
            --accent-text: #065f46;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.04);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.04), 0 2px 4px -2px rgb(0 0 0 / 0.04);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.04), 0 4px 6px -4px rgb(0 0 0 / 0.04);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.04), 0 8px 10px -6px rgb(0 0 0 / 0.04);
            --radius: 1rem;
            --radius-sm: 0.625rem;
        }

        html.dark-mode {
            --bg-body: #1a1a1a;
            --bg-surface: #222222;
            --bg-surface-elevated: #2a2a2a;
            --bg-header: rgba(17, 17, 17, 0.72);
            --bg-input: #2a2a2a;
            --bg-hover: rgba(255, 255, 255, 0.04);
            --bg-badge: rgba(255, 255, 255, 0.06);
            --border-color: rgba(255, 255, 255, 0.1);
            --border-strong: rgba(255, 255, 255, 0.18);
            --text-primary: #f8fafc;
            --text-secondary: #e5e5e5;
            --text-tertiary: #a3a3a3;
            --text-muted: #737373;
            --accent: #34d399;
            --accent-hover: #10b981;
            --accent-soft: rgba(52, 211, 153, 0.12);
            --accent-text: #6ee7b7;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.22);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.28), 0 2px 4px -2px rgb(0 0 0 / 0.2);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.3), 0 4px 6px -4px rgb(0 0 0 / 0.22);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.36), 0 8px 10px -6px rgb(0 0 0 / 0.28);
        }

        * {
            transition-property: color, background-color, border-color, box-shadow;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-primary);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        html.dark-mode body.public-layout {
            background-color: var(--bg-body) !important;
            color: var(--text-primary);
        }

        .page-header {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background-color: rgba(248, 249, 255, 0.8);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }

        html.dark-mode .page-header {
            background-color: var(--bg-header) !important;
            border-color: var(--border-color) !important;
        }

        html.dark-mode .page-header .text-slate-900 { color: #f8fafc !important; }
        html.dark-mode .page-header .text-slate-500 { color: #a3a3a3 !important; }
        html.dark-mode .page-header .hover\:text-emerald-700:hover,
        html.dark-mode .page-header .text-emerald-700 { color: #34d399 !important; }

        html.dark-mode body.public-layout > main {
            background-color: var(--bg-body) !important;
        }

        html.dark-mode .public-login-button {
            border: 1px solid #065f46 !important;
            background-color: #171717 !important;
            color: #34d399 !important;
            box-shadow: 0 0 14px rgba(16, 185, 129, 0.12) !important;
        }

        html.dark-mode .public-login-button:hover {
            border-color: #047857 !important;
            background-color: #222222 !important;
            color: #6ee7b7 !important;
        }

        html.dark-mode body.public-layout > footer {
            background-color: #111111 !important;
            border-color: #303030 !important;
        }

        html.dark-mode body.public-layout > footer .text-slate-900 { color: #f8fafc !important; }
        html.dark-mode body.public-layout > footer .text-slate-500 { color: #a3a3a3 !important; }

        html.dark-mode .input-field {
            background-color: var(--bg-input) !important;
            border-color: var(--border-color);
            color: var(--text-primary);
            color-scheme: dark;
        }

        .surface {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-lg);
        }

        .surface-elevated {
            background-color: var(--bg-surface-elevated);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-xl);
        }

        .input-field {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .input-field:hover {
            border-color: var(--border-strong);
        }

        .input-field:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: white;
            font-weight: 700;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 14px 0 rgba(5, 150, 105, 0.35);
            border: none;
        }

        html.dark-mode .btn-primary {
            box-shadow: 0 4px 14px 0 rgba(52, 211, 153, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(5, 150, 105, 0.45);
            filter: brightness(1.08);
        }

        html.dark-mode .btn-primary:hover {
            box-shadow: 0 6px 20px 0 rgba(52, 211, 153, 0.35);
        }

        .stat-card {
            background: linear-gradient(145deg, var(--bg-surface), var(--bg-surface-elevated));
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), transparent);
            opacity: 0.6;
        }

        html.dark-mode .stat-card::before {
            opacity: 0.8;
            background: linear-gradient(90deg, var(--accent), rgba(52,211,153,0.1));
        }

        .data-table-container {
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            overflow: hidden;
            background-color: var(--bg-surface);
            box-shadow: var(--shadow-lg);
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .data-table th {
            background-color: var(--bg-body);
            color: var(--text-tertiary);
            font-family: 'Public Sans', sans-serif;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .data-table td {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 0.9375rem;
            line-height: 1.5;
        }

        .data-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .data-table tbody tr:hover {
            background-color: var(--bg-hover);
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            background-color: var(--bg-badge);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }

        .category-badge.unclassified {
            background-color: rgba(245, 158, 11, 0.1);
            color: #d97706;
            border-color: rgba(245, 158, 11, 0.2);
        }

        html.dark-mode .category-badge.unclassified {
            background-color: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.25);
        }

        .amount-cell {
            font-variant-numeric: tabular-nums;
            font-feature-settings: 'tnum';
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        .hero-section {
            background: linear-gradient(135deg, #020617 0%, #0f172a 40%, #064e3b 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(52, 211, 153, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(52, 211, 153, 0.3), transparent);
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .filter-bar {
            background: linear-gradient(145deg, var(--bg-surface), var(--bg-body));
            border: 1px solid var(--border-color);
        }

        .empty-state {
            background: linear-gradient(145deg, var(--bg-surface), var(--bg-body));
        }

        .notice-banner {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(245, 158, 11, 0.03));
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        html.dark-mode .notice-banner {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(245, 158, 11, 0.04));
            border-color: rgba(245, 158, 11, 0.25);
        }

        .page-footer {
            background-color: var(--bg-surface);
            border-top: 1px solid var(--border-color);
        }

        .nav-link {
            position: relative;
            color: var(--text-tertiary);
        }

        .nav-link:hover {
            color: var(--accent);
        }

        .nav-link.active {
            color: var(--accent);
            font-weight: 700;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            border-radius: 2px;
        }

        .mobile-nav {
            background-color: var(--bg-surface);
            border-top: 1px solid var(--border-color);
        }

        .ledger-meta {
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
        }

        /* Smooth scroll & focus */
        html {
            scroll-behavior: smooth;
        }

        *:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* Pagination styling wrapper */
        .pagination-wrapper nav > div {
            color: var(--text-secondary);
        }

        .pagination-wrapper nav > div span,
        .pagination-wrapper nav > div a {
            background-color: var(--bg-surface);
            border-color: var(--border-color);
            color: var(--text-secondary);
        }

        .pagination-wrapper nav > div a:hover {
            background-color: var(--bg-hover);
            color: var(--accent);
        }

        html.dark-mode .pagination-wrapper nav > div span[aria-current="page"] {
            background-color: var(--accent-soft);
            color: var(--accent-text);
            border-color: var(--accent);
        }

        @media (max-width: 640px) {
            .data-table-container {
                border-radius: var(--radius-sm);
                border: none;
                box-shadow: none;
                background: transparent;
            }
            .data-table {
                min-width: unset;
            }
            .data-table thead {
                display: none;
            }
            .data-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid var(--border-color);
                border-radius: var(--radius-sm);
                background-color: var(--bg-surface);
                box-shadow: var(--shadow-sm);
                padding: 1rem;
            }
            .data-table tbody tr:hover {
                background-color: var(--bg-surface);
            }
            .data-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.625rem 0;
                border-bottom: 1px dashed var(--border-color);
                text-align: right;
            }
            .data-table td:last-child {
                border-bottom: none;
            }
            .data-table td::before {
                content: attr(data-label);
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.6875rem;
                letter-spacing: 0.08em;
                color: var(--text-tertiary);
                margin-right: 1rem;
                text-align: left;
            }
            .data-table .amount-cell {
                font-size: 1.125rem;
                color: var(--accent);
            }
        }
    </style>
</head>
<body class="public-layout min-h-screen antialiased">

    {{-- Header --}}
    <header class="page-header fixed top-0 z-50 w-full border-b border-slate-200/50">
        <nav class="relative mx-auto flex w-full items-center justify-between px-4 py-3 sm:px-6 sm:py-4 lg:px-12">
            <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-2 sm:gap-4">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-9 w-9 shrink-0 rounded-lg object-contain sm:h-10 sm:w-10" width="40" height="40" decoding="async">
                <div class="flex min-w-0 flex-col">
                    <span class="truncate text-base font-bold leading-tight tracking-tighter text-slate-900 sm:text-lg md:text-xl" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="truncate text-[9px] uppercase tracking-widest text-slate-500 opacity-70 sm:text-[10px]" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </a>

            <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center gap-6 text-xs uppercase tracking-widest" style="font-family:'Public Sans',sans-serif;">
                <a href="{{ url('/') }}" class="py-2 font-semibold text-slate-500 transition-colors hover:text-emerald-700">Home</a>
                <a href="{{ route('public.map') }}" class="py-2 font-semibold text-slate-500 transition-colors hover:text-emerald-700">Public Map</a>
                <a href="{{ route('public.expenditures') }}" aria-current="page" class="border-b-2 border-emerald-600 py-2 font-bold text-emerald-700 transition-all">Expenditures</a>
            </div>

            <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
                @include('components.public-theme-toggle')
                <a href="{{ route('login') }}" class="public-login-button bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white rounded-md transition-all duration-200 hover:opacity-90 sm:px-5 sm:py-2.5 sm:text-sm">Login</a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl space-y-8 px-4 pb-8 pt-24 sm:px-6 lg:px-8">

        {{-- Hero --}}
        <section class="hero-section relative overflow-hidden rounded-3xl p-8 text-white shadow-2xl sm:p-12 lg:p-14">
            <div class="relative z-10 flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
                <div class="max-w-2xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-emerald-100 backdrop-blur-sm">
                        <span class="material-symbols-outlined text-base">account_balance_wallet</span>
                        Public financial records
                    </div>
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl" style="font-family:'Manrope',sans-serif;">
                        Project Expenditures
                    </h1>
                    <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-300 sm:text-lg">
                        Browse expenditure totals by project. Open a project to see its monthly and category-level details, subject to applicable disclosure laws.
                    </p>
                </div>
                <a href="{{ route('public.analytics') }}" class="inline-flex shrink-0 items-center gap-2 self-start rounded-xl border border-white/20 bg-white/5 px-5 py-3 text-sm font-bold text-white backdrop-blur-md transition hover:bg-white/10 hover:border-white/30">
                    <span class="material-symbols-outlined">bar_chart</span>
                    View analytics
                </a>
            </div>
        </section>

        {{-- Project directory --}}
        <section class="data-table-container">
            <div class="border-b border-[var(--border-color)] p-6 sm:p-8">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-2xl font-extrabold tracking-tight text-[var(--text-primary)]" style="font-family:'Manrope',sans-serif;">Projects with recorded expenditure</h2>
                        <p class="mt-1 text-sm text-[var(--text-tertiary)]">Select a project to view its detailed monthly expenditure breakdown.</p>
                    </div>
                    @if($projects->hasPages())
                        <div class="pagination-wrapper shrink-0">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Filters --}}
            <div class="filter-bar border-b border-[var(--border-color)] p-5 sm:p-6">
                <form method="GET" action="{{ route('public.expenditures') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                    <div class="relative min-w-0 flex-1">
                        <label for="expenditure-search" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[var(--text-tertiary)]">Search projects</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)]">search</span>
                            <input id="expenditure-search" name="q" type="search" value="{{ $search }}" maxlength="100" placeholder="Project name, code, or barangay..." class="input-field w-full rounded-xl py-2.5 pl-10 pr-4 text-sm">
                        </div>
                    </div>
                    <div class="flex shrink-0 gap-3">
                        <button type="submit" class="btn-primary inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm transition-all">
                            <span class="material-symbols-outlined text-lg">search</span>
                            Search
                        </button>
                        @if(request()->filled('q'))
                            <a href="{{ route('public.expenditures') }}" class="inline-flex items-center gap-2 rounded-xl border border-[var(--border-color)] bg-[var(--bg-surface)] px-4 py-2.5 text-sm font-semibold text-[var(--text-secondary)] transition hover:bg-[var(--bg-hover)] hover:text-[var(--text-primary)]">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Project list --}}
            <div class="overflow-x-auto">
                <table class="data-table min-w-[720px]">
                    <thead>
                        <tr>
                            <th scope="col" class="text-left">Project</th>
                            <th scope="col" class="text-left">Barangay</th>
                            <th scope="col" class="text-left">Status</th>
                            <th scope="col" class="text-right">Total expenditure</th>
                            <th scope="col" class="text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td data-label="Project">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-bold text-[var(--text-primary)]">{{ $project->project_name }}</span>
                                        @if ($project->project_code)
                                            <span class="inline-flex items-center gap-1 text-xs text-[var(--text-muted)]">
                                                <span class="material-symbols-outlined text-[14px]">tag</span>
                                                {{ $project->project_code }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Barangay">{{ $project->barangay?->barangay_name ?? 'Citywide' }}</td>
                                <td data-label="Status">{{ $project->current_status ?? 'Not specified' }}</td>
                                <td data-label="Total expenditure" class="amount-cell text-right">₱{{ number_format($project->actual_budget_total, 2) }}</td>
                                <td data-label="Details" class="text-right">
                                    <a href="{{ route('public.expenditures.project', $project->project_id) }}" class="btn-primary inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm">
                                        View project details
                                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state flex flex-col items-center justify-center py-16 text-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--bg-hover)] text-[var(--text-muted)]">
                                            <span class="material-symbols-outlined text-3xl">search_off</span>
                                        </div>
                                        <p class="mt-4 text-base font-semibold text-[var(--text-primary)]">No projects found</p>
                                        <p class="mt-1 max-w-xs text-sm text-[var(--text-tertiary)]">No projects with recorded expenditure match your search.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($projects->hasPages())
                <div class="pagination-wrapper border-t border-[var(--border-color)] p-5 sm:p-6">
                    {{ $projects->links() }}
                </div>
            @endif

            @unless ($trackingAvailable)
                <div class="p-6 sm:p-8">
                    <div class="notice-banner flex flex-col items-start gap-4 rounded-2xl p-6 sm:flex-row sm:items-center sm:gap-5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                            <span class="material-symbols-outlined text-2xl">info</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-amber-900">Monthly expenditure details unavailable</h3>
                            <p class="mt-1 text-sm leading-6 text-amber-800/80">
                                Project totals remain available, but itemized monthly records are not enabled for this database.
                            </p>
                        </div>
                    </div>
                </div>
            @endunless

            <div class="ledger-meta bg-[var(--bg-body)] px-6 py-5 sm:px-8">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined shrink-0 text-base text-[var(--text-muted)]">info</span>
                    <p class="text-xs leading-5">
                        Public disclosure of project expenditure is subject to applicable laws and disclosure requirements. Internal descriptions and staff details are not published.
                    </p>
                </div>
            </div>
        </section>
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-slate-100 border-t border-slate-200">
        <div class="flex w-full max-w-7xl flex-col items-center justify-between px-8 py-12 mx-auto md:flex-row">
            <div class="mb-8 md:mb-0">
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex items-center justify-center w-8 h-8 bg-slate-900 rounded">
                        <span class="material-symbols-outlined text-xs text-white">account_balance</span>
                    </div>
                    <span class="font-bold text-slate-900" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                </div>
                <p class="text-xs uppercase tracking-widest text-slate-500">© {{ date('Y') }} Cabuyao City Government. All rights reserved.</p>
            </div>

            <div class="flex flex-wrap justify-center gap-8 text-xs uppercase tracking-widest">
                <a href="{{ route('public.map') }}" class="text-slate-500 transition-all duration-300 underline decoration-emerald-500/30 underline-offset-4 hover:text-emerald-600">Public Map</a>
                <a href="{{ route('public.expenditures') }}" class="text-slate-500 transition-all duration-300 underline decoration-emerald-500/30 underline-offset-4 hover:text-emerald-600">Project Expenditures</a>
                <a href="{{ route('login') }}" class="text-slate-500 transition-all duration-300 underline decoration-emerald-500/30 underline-offset-4 hover:text-emerald-600">Login</a>
            </div>
        </div>
    </footer>

    <script>
        // Sticky header shadow on scroll
        const header = document.querySelector('.page-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                header.style.boxShadow = 'var(--shadow-lg)';
            } else {
                header.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>