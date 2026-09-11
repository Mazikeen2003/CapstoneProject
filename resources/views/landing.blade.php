<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Transparency Portal | Cabuyao</title>
    @include('layouts.favicon')
    @include('components.theme-init')

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Public+Sans:wght@400;600;700&display=swap">
    {{-- Icons --}}
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
        .hero-gradient {
            background: linear-gradient(135deg, rgba(11, 28, 48, 0.95) 0%, rgba(19, 27, 46, 0.8) 100%);
        }
    </style>
</head>
<body class="public-layout bg-white font-sans text-slate-900 antialiased">

    {{-- ============ TOP NAV ============ --}}
    <header class="sticky top-0 z-50 glass-nav w-full border-b border-slate-200/50">
        <nav class="relative flex items-center justify-between w-full mx-auto px-4 py-3 sm:px-6 sm:py-4 lg:px-12">
            <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-9 w-9 sm:h-10 sm:w-10 shrink-0 rounded-lg object-contain" width="40" height="40" decoding="async" />
                <div class="flex flex-col min-w-0">
                    <span class="text-base sm:text-lg md:text-xl font-bold tracking-tighter text-slate-900 leading-tight" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="text-[9px] sm:text-[10px] uppercase tracking-widest text-slate-500 opacity-70 truncate" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </div>

            <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center text-xs uppercase tracking-widest gap-6" style="font-family:'Public Sans',sans-serif;">
                <a href="{{ url('/') }}" class="text-emerald-700 font-bold border-b-2 border-emerald-600 py-2 transition-all">Home</a>
                <a href="{{ route('public.map') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Analytics</a>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                @include('components.public-theme-toggle')
                <a href="{{ route('login') }}" class="public-login-button bg-slate-900 text-white px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-md font-semibold text-xs sm:text-sm hover:opacity-90 transition-all duration-200 shrink-0">Login</a>
            </div>
        </nav>
    </header>

    <main>
        {{-- ============ HERO ============ --}}
        <section id="home" class="relative flex min-h-[560px] items-center overflow-hidden sm:min-h-[620px] lg:min-h-[680px]">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 hero-gradient z-10"></div>
                <div class="w-full h-full bg-cover bg-center bg-slate-800"
                     style="background-image: url('{{ asset('images/hero-cabuyao.jpg') }}');"></div>
            </div>

            <div class="relative z-20 mx-auto w-full max-w-7xl px-6 py-12 text-white sm:py-16 lg:py-20">
                <div class="mx-auto flex max-w-4xl flex-col items-center space-y-6 text-center sm:space-y-7 lg:space-y-8">
                    <div class="mb-4 flex items-center gap-3 sm:gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full border border-white/20 bg-white/10 p-1.5 backdrop-blur-md sm:h-16 sm:w-16">
                            <span class="material-symbols-outlined text-3xl sm:text-4xl">verified_user</span>
                        </div>
                        <div class="flex h-[5.5rem] w-[5.5rem] items-center justify-center rounded-full border border-white/20 bg-white/10 p-1.5 backdrop-blur-lg sm:h-[6.5rem] sm:w-[6.5rem]">
                            <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-20 w-20 rounded-xl object-contain sm:h-24 sm:w-24" width="96" height="96" decoding="async" />
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-full border border-white/20 bg-white/10 p-1.5 backdrop-blur-md sm:h-16 sm:w-16">
                            <span class="material-symbols-outlined text-3xl sm:text-4xl">visibility</span>
                        </div>
                    </div>

                    <h1 class="text-4xl font-extrabold leading-tight tracking-tighter sm:text-5xl lg:text-7xl" style="font-family:'Manrope',sans-serif;">
                        Cabuyao City <br>
                        <span>Project Tracker System</span>
                    </h1>

                    <p class="max-w-2xl text-lg leading-relaxed text-slate-200 sm:text-xl">
                        Empowering citizens with real-time access to official city reports, public budgets, and community project tracking.
                    </p>

                    <div class="flex w-full flex-col gap-4 pt-4 sm:w-auto sm:flex-row lg:pt-6">
                        <a href="{{ route('public.map') }}"
                            class="px-8 py-4 bg-white text-slate-900 font-bold rounded-md hover:bg-slate-100 transition-all flex items-center justify-center gap-2 shadow-xl">
                            <span class="material-symbols-outlined">map</span>
                            View Public Map
                        </a>
                        <a href="{{ route('public.analytics') }}"
                            class="px-8 py-4 bg-transparent border-2 border-white/40 text-white font-bold rounded-md hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">analytics</span>
                            View Analytics
                        </a>
                    </div>
                </div>
            </div>

        </section>

        {{-- ============ LIVE STATS (overlapping hero) ============ --}}
        <section class="relative z-20 -mt-10 flex justify-center bg-transparent px-4 py-5 sm:-mt-14 sm:px-6 sm:py-7 lg:-mt-20 lg:px-8 lg:py-8">
            <div class="mx-auto w-full max-w-7xl">
                <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4 lg:gap-6">
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/60 bg-white p-4 text-center shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] sm:p-6 md:p-8">
                        <span class="mb-2 text-2xl font-extrabold text-emerald-700 sm:text-3xl md:text-4xl" id="totalProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Total Projects</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/60 bg-white p-4 text-center shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] sm:p-6 md:p-8">
                        <span class="mb-2 text-2xl font-extrabold text-emerald-700 sm:text-3xl md:text-4xl" id="completedProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Completed</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/60 bg-white p-4 text-center shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] sm:p-6 md:p-8">
                        <span class="mb-2 text-2xl font-extrabold text-emerald-700 sm:text-3xl md:text-4xl" id="ongoingProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Ongoing</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/60 bg-white p-4 text-center shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] sm:p-6 md:p-8">
                        <span class="mb-2 text-2xl font-extrabold text-emerald-700 sm:text-3xl md:text-4xl" id="totalBudget" style="font-family:'Manrope',sans-serif;">₱0</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Budget Allocated</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============ FEATURES BENTO GRID ============ --}}
        <section id="features" class="bg-white px-6 pt-6 pb-24 flex flex-col items-center">
            <div class="max-w-7xl mx-auto">
                <div class="mb-16 text-center">
                    <span class="text-xs uppercase tracking-[0.2em] text-emerald-700 font-bold mb-4 block" style="font-family:'Public Sans',sans-serif;">Portal Pillars</span>
                    <h2 class="text-4xl font-extrabold text-slate-900 tracking-tighter" style="font-family:'Manrope',sans-serif;">Governance Redefined</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Feature 1 --}}
                    <div class="group bg-slate-50 p-8 rounded-xl hover:bg-white hover:shadow-lg transition-all duration-300 relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="w-14 h-14 rounded-lg bg-emerald-100 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                                <span class="material-symbols-outlined text-emerald-700 text-3xl" style="font-variation-settings: 'FILL' 1;">location_on</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-slate-900" style="font-family:'Manrope',sans-serif;">Public Projects</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Real-time map tracking of all municipal construction and infrastructure developments across Cabuyao's districts.
                            </p>
                            <a href="{{ route('public.map') }}" class="mt-6 inline-flex items-center gap-2 text-emerald-700 font-semibold text-sm group/link">
                                Exploration Dashboard
                                <span class="material-symbols-outlined text-lg transition-transform group-hover/link:translate-x-1">arrow_forward</span>
                            </a>
                        </div>
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <span class="material-symbols-outlined text-8xl">map</span>
                        </div>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="group bg-white p-8 rounded-xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="w-14 h-14 rounded-lg bg-emerald-100 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                                <span class="material-symbols-outlined text-emerald-700 text-3xl" style="font-variation-settings: 'FILL' 1;">bar_chart</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-slate-900" style="font-family:'Manrope',sans-serif;">Project Analytics</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Explore project status distribution, completion trends, and barangay-level activity without exposing budget or expense details.
                            </p>
                            <a href="{{ route('public.analytics') }}" class="mt-6 inline-flex items-center gap-2 text-emerald-700 font-semibold text-sm group/link">
                                Analytics Dashboard
                                <span class="material-symbols-outlined text-lg transition-transform group-hover/link:translate-x-1">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="group bg-slate-50 p-8 rounded-xl hover:bg-white hover:shadow-lg transition-all duration-300 relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="w-14 h-14 rounded-lg bg-slate-900/10 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                                <span class="material-symbols-outlined text-slate-900 text-3xl" style="font-variation-settings: 'FILL' 1;">description</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-slate-900" style="font-family:'Manrope',sans-serif;">Transparency Report</h3>
                            <p class="text-slate-600 leading-relaxed">
                                See how Cabuyao City tracks project documentation, timeliness, and public transparency.
                            </p>
                            <a href="{{ route('public.transparency') }}" class="mt-6 inline-flex items-center gap-2 text-emerald-700 font-semibold text-sm group/link">
                                Transparency Report
                                <span class="material-symbols-outlined text-lg transition-transform group-hover/link:translate-x-1">arrow_forward</span>
                            </a>
                        </div>
                        <div class="absolute bottom-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <span class="material-symbols-outlined text-8xl">verified</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============ MISSION / VISION CTA ============ --}}
        <section class="relative overflow-hidden bg-slate-900 px-6 py-20">
            <div class="relative mx-auto max-w-7xl">
                <div class="mb-10 grid gap-6 border-b border-white/10 pb-7 md:grid-cols-[1fr_auto] md:items-end">
                    <div class="flex items-start gap-4">
                        <span class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-400 text-slate-950">
                            <span class="material-symbols-outlined">explore</span>
                        </span>
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-[0.2em] text-emerald-400">Our Direction</span>
                            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-white" style="font-family:'Manrope',sans-serif;">Vision &amp; Mission</h2>
                            <p class="mt-2 max-w-lg text-sm leading-6 text-slate-400">The principles that guide sustainable development and responsive planning in Cabuyao City.</p>
                        </div>
                    </div>
                    <div class="border-l border-emerald-400/40 pl-4 text-left md:text-right">
                        <span class="block text-[10px] font-bold uppercase tracking-[0.25em] text-emerald-400">Planning framework</span>
                        <span class="mt-1 block text-xs uppercase tracking-widest text-slate-500">Cabuyao City</span>
                    </div>
                </div>

                <div class="grid gap-10 lg:grid-cols-[0.75fr_1.25fr]">
                    <article class="group relative flex min-h-[300px] flex-col justify-between overflow-hidden rounded-xl border border-emerald-400/30 bg-emerald-400/10 p-8 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/30">
                        <div class="relative z-10">
                            <div class="mb-8 flex items-center justify-between">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-400 transition-transform group-hover:scale-110">
                                    <span class="material-symbols-outlined text-2xl text-slate-950">visibility</span>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-300">Vision</span>
                            </div>
                            <p class="text-lg font-semibold leading-8 text-white">
                                The implementation of sustainable development planning towards an entrepreneurial, progressive and environment-friendly City of Cabuyao.
                            </p>
                        </div>
                        <div class="relative z-10 mt-8 flex items-center gap-2 text-xs uppercase tracking-widest text-emerald-300">
                            <span class="h-px w-8 bg-emerald-300"></span>
                            <span>Our future</span>
                        </div>
                        <span class="material-symbols-outlined absolute -bottom-5 -right-4 text-8xl text-emerald-300/10">visibility</span>
                    </article>

                    <div>
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-emerald-400">
                                    <span class="material-symbols-outlined">flag</span>
                                </span>
                                <div>
                                    <h3 class="text-xl font-bold text-white" style="font-family:'Manrope',sans-serif;">Mission</h3>
                                    <p class="mt-1 text-xs uppercase tracking-widest text-slate-400">Four commitments</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-400">01 — 04</span>
                        </div>
                        <ol class="grid gap-3 sm:grid-cols-2">
                            <li class="group flex gap-4 rounded-lg border border-white/10 bg-white/5 p-5 text-sm leading-6 text-slate-300 transition-colors hover:border-emerald-400/40 hover:bg-emerald-400/10">
                                <span class="font-bold text-emerald-400">01</span>
                                <span>To formulate sound plans and programs participated by all sectors;</span>
                            </li>
                            <li class="group flex gap-4 rounded-lg border border-white/10 bg-white/5 p-5 text-sm leading-6 text-slate-300 transition-colors hover:border-emerald-400/40 hover:bg-emerald-400/10">
                                <span class="font-bold text-emerald-400">02</span>
                                <span>To implement Comprehensive Land Use Plan (CLUP) taking into consideration the protection of environment and efficient services to humanity with the continued support of hardworking staff;</span>
                            </li>
                            <li class="group flex gap-4 rounded-lg border border-white/10 bg-white/5 p-5 text-sm leading-6 text-slate-300 transition-colors hover:border-emerald-400/40 hover:bg-emerald-400/10">
                                <span class="font-bold text-emerald-400">03</span>
                                <span>To ensure sustainability in development planning and implementation;</span>
                            </li>
                            <li class="group flex gap-4 rounded-lg border border-white/10 bg-white/5 p-5 text-sm leading-6 text-slate-300 transition-colors hover:border-emerald-400/40 hover:bg-emerald-400/10">
                                <span class="font-bold text-emerald-400">04</span>
                                <span>To provide the public with data and information, best planning regulatory and monitoring services for the development of the City.</span>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- ============ FOOTER ============ --}}
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('{{ route("api.projects.geojson") }}')
                .then(r => r.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        const completed = data.features.filter(f => f.properties.status === 'Completed').length;
                        const ongoingStatuses = new Set([
                            'Proposed',
                            'For bidding',
                            'Bidding ongoing',
                            'Award of contract',
                            'Implementation'
                        ]);
                        const ongoing = data.features.filter(f => ongoingStatuses.has(f.properties.status)).length;
                        const totalBudget = data.features.reduce((sum, f) => {
                            const budget = Number(f.properties.budget);
                            return sum + (isNaN(budget) ? 0 : budget);
                        }, 0);

                        document.getElementById('totalProjects').textContent = data.features.length;
                        document.getElementById('completedProjects').textContent = completed;
                        document.getElementById('ongoingProjects').textContent = ongoing;
                        const budgetDisplay = totalBudget >= 1000000000
                            ? '₱' + (totalBudget / 1000000000).toLocaleString(undefined, { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + 'B'
                            : (totalBudget >= 1000000
                                ? '₱' + (totalBudget / 1000000).toLocaleString(undefined, { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + 'M'
                                : '₱' + totalBudget.toLocaleString(undefined, { maximumFractionDigits: 0 }));
                        document.getElementById('totalBudget').textContent = budgetDisplay;
                    }
                })
                .catch(err => console.error('Error:', err));
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
