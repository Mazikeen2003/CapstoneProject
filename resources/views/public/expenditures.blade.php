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
            --bg-body: #fdfcf7;
            --bg-surface: #ffffff;
            --bg-surface-elevated: #ffffff;
            --bg-header: rgba(253, 252, 247, 0.82);
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
            background-color: rgba(253, 252, 247, 0.82);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }
        body.public-expenditures-page > footer.landing-footer {
            background-color: #111111;
            border-color: rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
        }
        body.public-expenditures-page > footer.landing-footer .landing-footer-muted {
            color: #94a3b8;
        }
        body.public-expenditures-page > footer.landing-footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        body.public-expenditures-page > footer.landing-footer a:hover {
            color: #6ee7b7;
        }
        body.public-expenditures-page > footer.landing-footer nav h2,
        body.public-expenditures-page > footer.landing-footer address h2 {
            color: #6ee7b7;
        }
        body.public-expenditures-page > footer.landing-footer .landing-footer-bottom {
            border-color: rgba(255, 255, 255, 0.12);
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
            border-radius: 1.125rem;
            overflow: hidden;
            background-color: var(--bg-surface);
            box-shadow: 0 12px 32px -24px rgba(15, 23, 42, 0.32);
        }

        .expenditure-page-intro {
            padding-top: 0.5rem;
        }

        .expenditure-page-eyebrow {
            margin: 0 0 0.65rem;
            color: var(--text-tertiary);
            font-family: 'Public Sans', sans-serif;
            font-size: 0.625rem;
            font-weight: 700;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }

        .expenditure-page-title {
            margin: 0;
            color: var(--text-primary);
            font-family: 'Manrope', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.055em;
            line-height: 1.12;
        }

        .expenditure-page-description {
            max-width: 42rem;
            margin: 0.9rem 0 0;
            color: var(--text-secondary);
            font-size: 0.9375rem;
            line-height: 1.75;
        }

        .expenditure-page-nav {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .expenditure-page-nav a {
            display: inline-flex;
            align-items: center;
            min-height: 2.75rem;
            border-bottom: 2px solid transparent;
            color: var(--text-tertiary);
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
        }

        .expenditure-page-nav a:hover,
        .expenditure-page-nav a[aria-current="page"] {
            border-bottom-color: var(--accent);
            color: var(--text-primary);
        }

        .directory-toolbar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.125rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-surface);
        }

        .directory-search {
            position: relative;
            width: min(100%, 22rem);
        }

        .directory-search .input-field {
            min-height: 2.625rem;
            border-radius: 0.625rem;
            padding-top: 0.625rem;
            padding-bottom: 0.625rem;
        }

        .directory-toolbar .btn-primary {
            min-height: 2.625rem;
            border-radius: 0.625rem;
            background: #111827;
            box-shadow: none;
        }

        .directory-toolbar .btn-primary:hover {
            background: #047857;
            box-shadow: none;
            filter: none;
            transform: translateY(-1px);
        }

        .directory-analytics-link {
            display: inline-flex;
            min-height: 2.625rem;
            align-items: center;
            gap: 0.5rem;
            margin-left: auto;
            padding: 0 0.9rem;
            border: 1px solid var(--border-color);
            border-radius: 0.625rem;
            background: var(--bg-surface);
            color: var(--text-secondary);
            font-size: 0.8125rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .directory-analytics-link:hover {
            border-color: var(--accent);
            color: var(--accent-text);
        }

        .directory-summary {
            display: flex;
            min-height: 3.5rem;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 1.125rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-body);
        }

        .directory-summary-title {
            color: var(--text-primary);
            font-size: 0.8125rem;
            font-weight: 800;
        }

        .directory-summary-count {
            color: var(--text-tertiary);
            font-size: 0.75rem;
            text-align: right;
        }

        .expenditure-status {
            display: inline-flex;
            align-items: center;
            max-width: 100%;
            padding: 0.3rem 0.6rem;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            background: var(--bg-badge);
            color: var(--text-secondary);
            font-size: 0.6875rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .expenditure-row-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            border: 1px solid var(--border-strong);
            border-radius: 0.625rem;
            padding: 0.5rem 0.7rem;
            color: var(--text-secondary);
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .expenditure-row-action:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
            color: var(--accent-text);
        }

        html.dark-mode .directory-toolbar .btn-primary {
            background: #064e3b;
            color: #d1fae5;
        }

        html.dark-mode .directory-toolbar .btn-primary:hover {
            background: #065f46;
        }

        html.dark-mode .expenditure-row-action:hover {
            color: var(--accent-text);
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

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .filter-bar {
            background: var(--bg-surface);
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
                border: 1px solid var(--border-color);
                box-shadow: var(--shadow-sm);
                background: var(--bg-surface);
            }
            .directory-toolbar {
                align-items: stretch;
                flex-direction: column;
                padding: 1rem;
            }
            .directory-toolbar form,
            .directory-search {
                width: 100%;
            }
            .directory-toolbar form > .flex.shrink-0 {
                width: 100%;
            }
            .directory-toolbar form > .flex.shrink-0 > * {
                flex: 1;
                justify-content: center;
            }
            .directory-analytics-link {
                justify-content: center;
                margin-left: 0;
            }
            .directory-summary {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.25rem;
            }
            .directory-summary-count {
                text-align: left;
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
<body class="public-layout public-expenditures-page min-h-screen antialiased">

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

    <main class="mx-auto max-w-7xl space-y-6 px-4 pb-10 pt-24 sm:px-6 sm:pt-28 lg:px-8">
        <section class="expenditure-page-intro">
            <p class="expenditure-page-eyebrow">City Transparency Portal · Public records</p>
            <h1 class="expenditure-page-title">Project expenditures</h1>
            <p class="expenditure-page-description">
                Browse recorded expenditure by project. Open a project for its monthly and category-level breakdown.
            </p>
            <nav class="expenditure-page-nav" aria-label="Expenditure sections">
                <a href="#project-directory" aria-current="page">Browse</a>
                <a href="#expenditure-disclosure">Disclosure notes</a>
            </nav>
        </section>

        {{-- Project directory --}}
        <section class="data-table-container" id="project-directory" aria-labelledby="project-directory-title">
            <div class="directory-toolbar">
                <form method="GET" action="{{ route('public.expenditures') }}" class="flex min-w-0 flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="directory-search min-w-0 flex-1">
                        <label for="expenditure-search" class="sr-only">Search projects</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)]">search</span>
                            <input id="expenditure-search" name="q" type="search" value="{{ $search }}" maxlength="100" placeholder="Search projects, codes, or barangays..." class="input-field w-full rounded-lg py-2.5 pl-10 pr-4 text-sm">
                        </div>
                    </div>
                    <div class="flex shrink-0 gap-3">
                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 rounded-lg px-5 py-2.5 text-sm transition-all">
                            <span class="material-symbols-outlined text-lg">search</span>
                            Search
                        </button>
                        @if(request()->filled('q'))
                            <a href="{{ route('public.expenditures') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[var(--border-color)] bg-[var(--bg-surface)] px-4 py-2.5 text-sm font-semibold text-[var(--text-secondary)] transition hover:bg-[var(--bg-hover)] hover:text-[var(--text-primary)]">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
                <a href="{{ route('public.analytics') }}" class="directory-analytics-link">
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">bar_chart</span>
                    View analytics
                </a>
            </div>

            <div class="directory-summary">
                <h2 class="directory-summary-title" id="project-directory-title">Projects with recorded expenditure</h2>
                <p class="directory-summary-count">
                    Showing {{ $projects->firstItem() ?? 0 }}–{{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }} {{ \Illuminate\Support\Str::plural('project', $projects->total()) }}
                    @if($search !== '')
                        matching “{{ $search }}”
                    @endif
                </p>
            </div>

            {{-- Project list --}}
            <div class="overflow-x-auto">
                <table class="data-table min-w-[720px]">
                    <caption class="sr-only">Project expenditure records, including location, current status, and recorded expenditure.</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="text-left">Project</th>
                            <th scope="col" class="text-left">Location</th>
                            <th scope="col" class="text-left">Current status</th>
                            <th scope="col" class="text-right">Recorded expenditure</th>
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
                                <td data-label="Location">{{ $project->barangay?->barangay_name ?? 'Citywide' }}</td>
                                <td data-label="Current status"><span class="expenditure-status">{{ $project->current_status ?? 'Not specified' }}</span></td>
                                <td data-label="Recorded expenditure" class="amount-cell text-right">₱{{ number_format($project->actual_budget_total, 2) }}</td>
                                <td data-label="Details" class="text-right">
                                    <a href="{{ route('public.expenditures.project', $project->project_id) }}" class="expenditure-row-action">
                                        Details
                                        <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
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

            <div class="ledger-meta bg-[var(--bg-body)] px-6 py-5 sm:px-8" id="expenditure-disclosure">
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
    <footer class="landing-footer border-t">
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