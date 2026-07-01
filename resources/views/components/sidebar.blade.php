@php
    $authUser = Auth::user();
    $role = $authUser ? $authUser->role_slug : 'public';
    $userName = $authUser ? $authUser->username : 'Guest';
    $userEmail = $authUser ? $authUser->user_email : '';
@endphp

<!-- Mobile backdrop overlay -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden xl:hidden" style="display: none;"></div>

<aside id="sidebar" class="fixed left-0 top-0 h-screen w-80 shrink-0 text-white transform -translate-x-full transition-transform duration-300 overflow-y-auto xl:sticky xl:top-0 xl:self-start xl:translate-x-0 xl:flex xl:flex-col xl:h-screen z-40" style="background-color: #0F172A;">
    <div class="flex h-full flex-col justify-between min-h-screen xl:min-h-0">
        <div class="space-y-8 p-6">
            <div class="space-y-6 text-center">
                <div class="flex flex-col items-center justify-center gap-5 py-2">
                    <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System" class="mx-auto h-20 w-auto object-contain" />
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-300">Project Tracker System</div>
                    </div>
                </div>
                <nav class="mt-8 space-y-1">
                    @if($role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">D</span>
                            Dashboard
                        </a>
                        <a href="{{ url('/admin/users') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/users') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">U</span>
                            User Access
                        </a>
                        <a href="{{ url('/admin/reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/reports') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">R</span>
                            Reports
                        </a>
                        <a href="{{ url('/admin/audit-logs') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/audit-logs') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">A</span>
                            Audit Logs
                        </a>
                    @elseif($role === 'department')
                        <a href="{{ url('/department/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('department/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">D</span>
                            Dashboard
                        </a>
                        <a href="{{ url('/department/projects') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">P</span>
                            Projects
                        </a>
                        <a href="{{ url('/department/map') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">M</span>
                            Map
                        </a>
                        <a href="{{ url('/department/analytics') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">A</span>
                            Analytics
                        </a>
                        <a href="{{ url('/department/reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">R</span>
                            Reports
                        </a>
                    @elseif($role === 'city')
                        <a href="{{ url('/city/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">D</span>
                            Dashboard
                        </a>
                        <a href="{{ url('/city/map') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/map') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">M</span>
                            Map
                        </a>
                        <a href="{{ url('/city/analytics') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/analytics') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">A</span>
                            Analytics
                        </a>
                        <a href="{{ url('/city/reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">R</span>
                            Reports
                        </a>
                    @elseif($role === 'barangay')
                        <a href="{{ url('/barangay/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('barangay/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">D</span>
                            Dashboard
                        </a>
                        <a href="{{ url('/barangay/projects') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">P</span>
                            Projects
                        </a>
                        <a href="{{ url('/barangay/map') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">M</span>
                            Map
                        </a>
                        <a href="{{ url('/barangay/analytics') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">A</span>
                            Analytics
                        </a>
                        <a href="{{ url('/barangay/reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">R</span>
                            Reports
                        </a>
                    @else
                        <a href="{{ url('/public/map') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('public/map') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">M</span>
                            Map
                        </a>
                        <a href="{{ url('/public/analytics') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">A</span>
                            Analytics
                        </a>
                    @endif
                </nav>
            </div>
        </div>
        
        <!-- Logout Button at Bottom (Only for authenticated users, not public) -->
        @if($role !== 'public')
        <div class="border-t border-slate-800 p-6" style="border-color: #162347;">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">{{ strtoupper(substr($userName, 0, 2)) }}</div>
                <div>
                    <div class="text-sm font-semibold text-white">{{ $userName }}</div>
                    <div class="text-xs" style="color: #c9a84c;">{{ $userEmail }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
        @endif
    </div>
</aside>
