<style>
    .glass-nav {
        backdrop-filter: blur(16px);
        background-color: rgba(255, 255, 255, 0.95);
    }
</style>

<header class="sticky top-0 z-50 glass-nav w-full border-b border-slate-200/50">
    <nav class="relative flex items-center py-4 w-full mx-auto px-12 justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 shrink-0 rounded-lg object-contain" width="40" height="40" decoding="async" />
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tighter text-slate-900" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="text-[10px] uppercase tracking-widest text-slate-500 opacity-70" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </a>
        </div>

        <div class="flex items-center gap-2">
            @include('components.public-theme-toggle')
            <span class="text-sm font-semibold text-slate-700 hidden sm:inline">{{ Auth::user()?->username ?? session('mock_user.username') ?? __('Guest') }}</span>
        </div>
    </nav>
</header>
