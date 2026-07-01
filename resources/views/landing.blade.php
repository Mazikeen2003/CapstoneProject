<x-guest-layout>
    <div class="min-h-screen bg-white text-slate-900">
        <main class="mx-auto flex min-h-screen max-w-4xl flex-col justify-center px-6 py-16">
            <div class="rounded-[32px] border border-slate-200 bg-white p-10 shadow-[0_20px_80px_-30px_rgba(15,23,42,0.15)] sm:p-12">
                <div class="text-center">
                    <p class="text-sm uppercase tracking-[0.35em] text-sky-600">Project Tracker System</p>
                    <h1 class="mt-4 text-4xl font-semibold text-slate-900 sm:text-5xl">Project Tracker System</h1>
                    <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600">
                        Select your access path to continue: browse public projects or sign in for government management.
                    </p>
                </div>

                <div class="mt-12 grid gap-4 sm:grid-cols-2">
                    <a href="{{ route('public.map') }}" class="group flex min-h-[220px] flex-col justify-between rounded-[28px] border border-slate-200 bg-slate-50 p-6 text-left transition hover:-translate-y-1 hover:bg-slate-100">
                        <div class="space-y-4">
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">Public</p>
                            <h2 class="text-2xl font-semibold text-slate-900">Visit Public Map</h2>
                            <p class="text-sm leading-6 text-slate-600">Browse locations and project updates without login.</p>
                        </div>
                        <div class="mt-6 rounded-full bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700 transition group-hover:bg-sky-200">
                            Go to Public
                        </div>
                    </a>

                    <a href="{{ route('login') }}" class="group flex min-h-[220px] flex-col justify-between rounded-[28px] border border-slate-200 bg-slate-50 p-6 text-left transition hover:-translate-y-1 hover:bg-slate-100">
                        <div class="space-y-4">
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Government</p>
                            <h2 class="text-2xl font-semibold text-slate-900">Government Login</h2>
                            <p class="text-sm leading-6 text-slate-600">Secure access for officials and administrators.</p>
                        </div>
                        <div class="mt-6 rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700 transition group-hover:bg-emerald-200">
                            Go to Login
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</x-guest-layout>
