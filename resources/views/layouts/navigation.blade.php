<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 shrink-0 rounded-full border border-slate-200 bg-white p-1 object-contain" width="40" height="40" />
                <div class="flex min-w-0 flex-col">
                    <span class="text-base font-bold tracking-tight text-slate-900 sm:text-xl" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="text-[9px] uppercase tracking-widest text-slate-500 sm:text-[10px]" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </a>
        </div>

        <div class="ml-auto flex items-center gap-3 sm:gap-5">
            <span class="text-sm font-semibold text-slate-700">{{ Auth::user()?->username ?? session('mock_user.username') ?? __('Guest') }}</span>
            @include('components.public-theme-toggle')
        </div>

        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()?->username ?? session('mock_user.username') ?? __('Guest') }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()?->email ?? session('mock_user.email') ?? '' }}</div>
            </div>
        </div>
    </div>
</nav>
