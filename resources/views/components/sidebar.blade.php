@php
    $authUser = Auth::user();
    // Force public sidebar when viewing public routes so public pages remain public
    $isPublicRoute = request()->routeIs('public.*') || request()->is('public') || request()->is('public/*') || request()->is('ProjectTracker/public/*');
    $role = $isPublicRoute ? 'public' : ($authUser ? $authUser->role_slug : 'public');
    $userName = $authUser ? $authUser->username : 'Guest';
    $userEmail = $authUser ? $authUser->user_email : '';
@endphp

<!-- Mobile backdrop overlay -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-[9998] hidden xl:hidden" style="display: none;"></div>

<aside id="sidebar" class="fixed left-0 top-0 h-screen w-80 shrink-0 text-white transform -translate-x-full transition-transform duration-300 overflow-y-auto xl:sticky xl:top-0 xl:self-start xl:translate-x-0 xl:flex xl:flex-col xl:h-screen z-[9999]" style="background-color: #0F172A;">
    <div class="flex h-full flex-col justify-between min-h-screen xl:min-h-0">
        <div class="space-y-8 p-6">
            <div class="space-y-6 text-center">
                <div class="flex flex-col items-center justify-center gap-5 py-2">
                    <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="mx-auto h-20 w-auto object-contain" />
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-300">Project Tracker System</div>
                    </div>
                </div>
                <nav class="mt-8 space-y-1">
                    @if($role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Admin Dashboard">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/admin/users') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/users') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="User Access Management">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Users">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.64 2.26 1.59 2.97 2.95V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            </span>
                            User Access
                        </a>
                        <a href="{{ url('/admin/project-permissions') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/project-permissions') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Project Edit Permissions">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Permissions">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.25 3.44 10.17 9 12 5.56-1.83 9-6.75 9-12V5l-9-4zm0 2.18l6 2.67v4.15c0 4.15-2.74 8.16-6 9.49-3.26-1.33-6-5.34-6-9.49V5.85l6-2.67zm-1 4.84h2v5h-2zm0 6h2v2h-2z"/></svg>
                            </span>
                            Permissions
                        </a>
                        <a href="{{ url('/admin/reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/reports') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="System Reports">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                        <a href="{{ url('/admin/audit-logs') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/audit-logs') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Audit Logs">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Audit Logs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            </span>
                            Audit Logs
                        </a>
                    @elseif($role === 'department')
                        <a href="{{ url('/department/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('department/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Department Dashboard">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/department/projects') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Projects">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M11 7h2v2h-2zm0 4h2v2h-2zm-4-4h2v2H7zm0 4h2v2H7zM7 3h10c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2zm12-2v18H3V1h18z"/></svg>
                            </span>
                            Projects
                        </a>
                        <a href="{{ url('/department/map') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Map">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/department/analytics') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Analytics">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                            </span>
                            Analytics
                        </a>
                        <a href="{{ url('/department/reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Reports">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                    @elseif($role === 'city')
                        <a href="{{ url('/city/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="City Dashboard">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/city/map') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/map') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="City Map">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/city/analytics') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/analytics') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="City Analytics">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                            </span>
                            Analytics
                        </a>
                        <a href="{{ url('/city/reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="City Reports">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                    @elseif($role === 'barangay')
                        <a href="{{ url('/barangay/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('barangay/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Barangay Dashboard">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/barangay/projects') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Projects">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M11 7h2v2h-2zm0 4h2v2h-2zm-4-4h2v2H7zm0 4h2v2H7zM7 3h10c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2zm12-2v18H3V1h18z"/></svg>
                            </span>
                            Projects
                        </a>
                        <a href="{{ url('/barangay/map') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Map">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/barangay/analytics') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Analytics">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                            </span>
                            Analytics
                        </a>
                        <a href="{{ url('/barangay/reports') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Reports">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                    @else
                        <a href="{{ url('/public/map') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('public/map') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Public Map">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/public/analytics') }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Public Analytics">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                            </span>
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
