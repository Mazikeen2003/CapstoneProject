<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transparency Report | City Transparency Portal</title>
    @include('layouts.favicon')
    @include('components.theme-init')

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Public+Sans:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-nav { backdrop-filter: blur(16px); background-color: rgba(248,249,255,0.8); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .an-container { --an-surface: #ffffff; --an-raised: #f8fafc; --an-ink: #0f172a; --an-ink-secondary: #334155; --an-muted: #64748b; --an-line: rgba(15,23,42,0.08); --an-line-strong: rgba(15,23,42,0.14); --an-shadow-sm: 0 1px 3px rgba(0,0,0,0.06); --an-shadow-md: 0 8px 16px -4px rgba(0,0,0,0.08), 0 4px 8px -4px rgba(0,0,0,0.04); width: min(1400px, calc(100vw - 48px)); margin: 5px auto; box-sizing: border-box; }
        html.dark-mode .an-container { --an-surface: #0f172a; --an-raised: #1e293b; --an-ink: #f8fafc; --an-ink-secondary: #cbd5e1; --an-muted: #94a3b8; --an-line: rgba(148,163,184,0.2); --an-line-strong: rgba(148,163,184,0.35); }
        .an-hero { position: relative; background: linear-gradient(135deg, #0f0d23 0%, #1e1b4b 30%, #047857 70%, #10b981 100%); border-radius: 20px; padding: 36px 40px; overflow: hidden; box-shadow: var(--an-shadow-md); }
        .an-hero::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px); background-size: 24px 24px; opacity: 0.5; pointer-events: none; }
        .an-hero::after { content: ''; position: absolute; top:-40%; right:-5%; width:450px; height:450px; background: radial-gradient(circle, rgba(16,185,129,0.22), transparent 60%); pointer-events: none; }
        .an-hero-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.14); border-radius:100px; font-size:.6875rem; font-weight:700; color:rgba(255,255,255,0.85); text-transform:uppercase; letter-spacing:.08em; }
        .an-hero-title { font-family:'Manrope',sans-serif; font-size:clamp(1.75rem,4vw,2.75rem); font-weight:800; color:#fff; line-height:1.15; letter-spacing:-0.03em; }
        .an-hero-subtitle { font-size:1rem; color:rgba(255,255,255,0.68); font-family:'Public Sans',sans-serif; }
        .an-grid { display:grid; grid-template-columns: repeat(4, minmax(180px, 1fr)); gap:16px; margin-top:18px; }
        .an-card { background: var(--an-surface); border: 1px solid var(--an-line); border-radius: 16px; box-shadow: var(--an-shadow-sm); transition:all 0.25s; position:relative; overflow:hidden; }
        .an-card:hover { transform: translateY(-3px); box-shadow: var(--an-shadow-md); }
        .an-card::before { content: ''; position:absolute; top:0; left:0; right:0; height:3px; opacity:0; transition:opacity 0.25s; }
        .an-card:hover::before { opacity:1; }
        .an-card.accent-emerald::before { background: linear-gradient(90deg,#10b981,#059669); }
        .an-card.accent-blue::before { background: linear-gradient(90deg,#3b82f6,#2563eb); }
        .an-card.accent-indigo::before { background: linear-gradient(90deg,#1e1b4b,#4338ca); }
        .an-card.accent-amber::before { background: linear-gradient(90deg,#f59e0b,#d97706); }
        .an-card-body { padding:22px; }
        .an-card-label { font-family:'Public Sans',sans-serif; font-size:.6875rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--an-muted); }
        .an-card-value { font-family:'Manrope',sans-serif; font-size:2rem; font-weight:800; letter-spacing:.02em; line-height:1.1; color:var(--an-ink); }
        .an-icon-chip { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; }
        .an-icon-chip.emerald { background:#d1fae5; color:#059669; }
        .an-icon-chip.blue { background:#dbeafe; color:#2563eb; }
        .an-icon-chip.indigo { background:#e0e7ff; color:#4338ca; }
        .an-icon-chip.amber { background:#fef3c7; color:#b45309; }
        .an-table { width:100%; border-collapse:collapse; margin-top:24px; table-layout:fixed; }
        .an-table th, .an-table td { padding:14px 16px; border-bottom:1px solid var(--an-line); color:var(--an-ink-secondary); font-family:'Public Sans',sans-serif; }
        .an-table th { font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--an-muted); }
        .an-table td { font-size:.875rem; font-weight:600; }
        .an-table thead th:nth-child(1), .an-table tbody td:nth-child(1) { width:34%; text-align:left; }
        .an-table thead th:nth-child(2), .an-table tbody td:nth-child(2) { width:33%; text-align:center; }
        .an-table thead th:nth-child(3), .an-table tbody td:nth-child(3) { width:33%; text-align:right; }
        @media (max-width: 900px) { .an-grid { grid-template-columns: repeat(2, minmax(160px,1fr)); } }
        @media (max-width: 640px) { .an-grid { grid-template-columns: 1fr; } .an-container { width: min(100vw, calc(100vw - 24px)); } }
    </style>
</head>
<body class="public-layout bg-white font-sans text-slate-900 antialiased">
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
                <a href="{{ route('public.map') }}" class="hover:text-emerald-700 transition-colors">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="hover:text-emerald-700 transition-colors">Analytics</a>
            </div>
        </div>
    </header>

    <main class="an-container" style="margin-top:28px;">
        <section class="an-hero">
            <div class="an-hero-badge"><span class="material-symbols-outlined">verified_user</span> Public Transparency</div>
            <div style="margin-top:14px;">
                <h1 class="an-hero-title">Transparency Report</h1>
                <p class="an-hero-subtitle" style="margin-top:14px; max-width:850px;">
                    This page shows how well Cabuyao City documents, updates, and reports on its local development projects — key indicators the City tracks as part of good governance practices.
                </p>
            </div>
        </section>

        <section class="an-grid" style="margin-top:24px;">
            <div class="an-card accent-emerald">
                <div class="an-card-body">
                    <div class="flex items-center justify-between">
                        <span class="an-icon-chip emerald"><span class="material-symbols-outlined">description</span></span>
                    </div>
                    <div class="an-card-label" style="margin-top:16px;">Documentation Rate</div>
                    <div class="an-card-value" style="margin-top:10px;">{{ number_format($metrics['documentation_rate'], 1) }}%</div>
                </div>
            </div>
            <div class="an-card accent-blue">
                <div class="an-card-body">
                    <div class="flex items-center justify-between">
                        <span class="an-icon-chip blue"><span class="material-symbols-outlined">update</span></span>
                    </div>
                    <div class="an-card-label" style="margin-top:16px;">Up-to-Date Rate</div>
                    <div class="an-card-value" style="margin-top:10px;">{{ number_format($metrics['up_to_date_rate'], 1) }}%</div>
                </div>
            </div>
            <div class="an-card accent-indigo">
                <div class="an-card-body">
                    <div class="flex items-center justify-between">
                        <span class="an-icon-chip indigo"><span class="material-symbols-outlined">visibility</span></span>
                    </div>
                    <div class="an-card-label" style="margin-top:16px;">Transparency Rate</div>
                    <div class="an-card-value" style="margin-top:10px;">{{ number_format($metrics['transparency_rate'], 1) }}%</div>
                </div>
            </div>
            <div class="an-card accent-amber">
                <div class="an-card-body">
                    <div class="flex items-center justify-between">
                        <span class="an-icon-chip amber"><span class="material-symbols-outlined">task_alt</span></span>
                    </div>
                    <div class="an-card-label" style="margin-top:16px;">Completion Rate</div>
                    <div class="an-card-value" style="margin-top:10px;">{{ number_format($metrics['completion_rate'], 1) }}%</div>
                </div>
            </div>
        </section>

        <section class="an-card" style="margin-top:24px;">
            <div class="an-card-body">
                <div class="flex items-center justify-between" style="margin-bottom:14px;">
                    <div>
                        <div class="an-card-label">Barangay Breakdown</div>
                        <div class="an-card-value sm" style="font-size:1.5rem; margin-top:8px;">Documentation by Barangay</div>
                    </div>
                </div>
                <table class="an-table">
                    <thead>
                        <tr>
                            <th>Barangay</th>
                            <th>Project Count</th>
                            <th>Documentation Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($byBarangay as $name => $row)
                            <tr>
                                <td>{{ $name }}</td>
                                <td>{{ $row['count'] ?? 0 }}</td>
                                <td>{{ number_format($row['documentation_rate'] ?? 0, 1) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center;">No barangay data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
