<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Transparency Portal | Cabuyao</title>
    @include('layouts.favicon')

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
<body class="bg-white font-sans text-slate-900 antialiased">

    {{-- ============ TOP NAV ============ --}}
    <header class="sticky top-0 z-50 glass-nav w-full border-b border-slate-200/50">
        <nav class="relative flex items-center py-4 w-full mx-auto px-12 justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-slate-900 p-2 rounded-lg">
                    <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">account_balance</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tighter text-slate-900" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="text-[10px] uppercase tracking-widest text-slate-500 opacity-70" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </div>

            <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center text-xs uppercase tracking-widest gap-6" style="font-family:'Public Sans',sans-serif;">
                <a href="{{ url('/') }}" class="text-emerald-700 font-bold border-b-2 border-emerald-600 py-2 transition-all">Home</a>
                <a href="{{ route('public.map') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Analytics</a>
            </div>

            <a href="{{ route('login') }}"
                class="bg-slate-900 text-white px-5 py-2.5 rounded-md font-semibold text-sm hover:opacity-90 transition-all duration-200 shrink-0">
                Login
            </a>
        </nav>
    </header>

    <main>
        {{-- ============ HERO ============ --}}
        <section id="home" class="relative min-h-[85vh] flex items-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 hero-gradient z-10"></div>
                <div class="w-full h-full bg-cover bg-center bg-slate-800"
                     style="background-image: url('{{ asset('images/hero-cabuyao.jpg') }}');"></div>
            </div>

            <div class="relative z-20 w-full max-w-7xl mx-auto px-6 py-20 text-white">
                <div class="flex flex-col items-center text-center space-y-8 max-w-4xl mx-auto">
                    <div class="flex items-center gap-6 mb-4">
                        <div class="w-20 h-20 rounded-full bg-white/10 backdrop-blur-md p-2 flex items-center justify-center border border-white/20">
                            <span class="material-symbols-outlined text-4xl">verified_user</span>
                        </div>
                        <div class="w-24 h-24 rounded-full bg-white/10 backdrop-blur-lg p-2 flex items-center justify-center border border-white/20">
                            <span class="material-symbols-outlined text-5xl" style="font-variation-settings: 'FILL' 1;">account_balance</span>
                        </div>
                        <div class="w-20 h-20 rounded-full bg-white/10 backdrop-blur-md p-2 flex items-center justify-center border border-white/20">
                            <span class="material-symbols-outlined text-4xl">visibility</span>
                        </div>
                    </div>

                    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tighter leading-tight" style="font-family:'Manrope',sans-serif;">
                        Cabuyao City <br>
                        <span>Project Tracker System</span>
                    </h1>

                    <p class="text-xl text-slate-200 max-w-2xl leading-relaxed">
                        Empowering citizens with real-time access to official city reports, public budgets, and community project tracking.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
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

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 text-white/50 animate-bounce cursor-pointer">
                <span class="material-symbols-outlined text-3xl">keyboard_double_arrow_down</span>
            </div>
        </section>

        {{-- ============ LIVE STATS (overlapping hero) ============ --}}
        <section class="py-12 px-12 -mt-32 relative z-20 bg-transparent flex justify-center">
            <div class="max-w-7xl mx-auto w-full">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white p-8 rounded-xl shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] border border-slate-200/50 flex flex-col items-center justify-center text-center">
                        <span class="text-4xl font-extrabold mb-2 text-emerald-700" id="totalProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-500" style="font-family:'Public Sans',sans-serif;">Total Projects</span>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] border border-slate-200/50 flex flex-col items-center justify-center text-center">
                        <span class="text-4xl font-extrabold mb-2 text-emerald-700" id="completedProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-500" style="font-family:'Public Sans',sans-serif;">Completed</span>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] border border-slate-200/50 flex flex-col items-center justify-center text-center">
                        <span class="text-4xl font-extrabold mb-2 text-emerald-700" id="ongoingProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-500" style="font-family:'Public Sans',sans-serif;">Ongoing</span>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] border border-slate-200/50 flex flex-col items-center justify-center text-center">
                        <span class="text-4xl font-extrabold mb-2 text-emerald-700" id="totalBudget" style="font-family:'Manrope',sans-serif;">₱0</span>
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-500" style="font-family:'Public Sans',sans-serif;">Budget Allocated</span>
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
                                <span class="material-symbols-outlined text-emerald-700 text-3xl" style="font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-slate-900" style="font-family:'Manrope',sans-serif;">Financial Oversight</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Accessible budget data including granular expense tracking, tax allocations, and community fund distributions.
                            </p>
                            <a href="{{ route('login') }}" class="mt-6 inline-flex items-center gap-2 text-emerald-700 font-semibold text-sm group/link">
                                Audit Transparency
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
                            <h3 class="text-2xl font-bold mb-3 text-slate-900" style="font-family:'Manrope',sans-serif;">Official Reports</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Verified institutional documents, meeting minutes, and legal resolutions available for public review and download.
                            </p>
                            <a href="{{ route('login') }}" class="mt-6 inline-flex items-center gap-2 text-emerald-700 font-semibold text-sm group/link">
                                Document Library
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
        <section class="py-20 bg-slate-900 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500 to-transparent opacity-30"></div>
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tighter" style="font-family:'Manrope',sans-serif;">
                        Our Institutional Commitment
                    </h2>
                    <p class="text-slate-300 text-lg leading-relaxed">
                        Cabuyao City is dedicated to fostering a culture of excellence and integrity in public service, ensuring that every citizen benefits from a government that is both responsive and visionary.
                    </p>
                </div>

                <div class="grid gap-6">
                    <div class="bg-white/5 rounded-2xl p-8 backdrop-blur-sm border border-white/10 flex items-start gap-6">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-emerald-400">flag</span>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-xl mb-2">Mission</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                To promote the general welfare of its inhabitants by providing quality basic services and ensuring a peaceful, orderly, and sustainable environment through transparent, accountable, and participatory governance.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-2xl p-8 backdrop-blur-sm border border-white/10 flex items-start gap-6">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-emerald-400">visibility</span>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-xl mb-2">Vision</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                A premier city of opportunity, character, and resilience, driven by an empowered and healthy citizenry living in a sustainable and globally competitive environment.
                            </p>
                        </div>
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
                        const ongoing = data.features.filter(f => f.properties.status === 'On Going').length;
                        const totalBudget = data.features.reduce((sum, f) => {
                            const budget = Number(f.properties.budget);
                            return sum + (isNaN(budget) ? 0 : budget);
                        }, 0);

                        document.getElementById('totalProjects').textContent = data.features.length;
                        document.getElementById('completedProjects').textContent = completed;
                        document.getElementById('ongoingProjects').textContent = ongoing;
                        document.getElementById('totalBudget').textContent = '₱' + (totalBudget / 1000000).toFixed(1) + 'M';
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
