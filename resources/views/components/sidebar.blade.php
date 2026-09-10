@php
    $authUser = Auth::user();
    // Force public sidebar when viewing public routes so public pages remain public
    $isPublicRoute = request()->routeIs('public.*') || request()->is('public') || request()->is('public/*') || request()->is('ProjectTracker/public/*');
    $role = $isPublicRoute ? 'public' : ($authUser ? $authUser->role_slug : 'public');
    $userName = $authUser ? $authUser->username : 'Guest';
    $userEmail = $authUser ? $authUser->user_email : '';
@endphp

<!-- Mobile backdrop overlay -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-[9998] hidden xl:hidden"></div>

<script>
    try {
        if (localStorage.getItem('projectTrackerSidebarCollapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    } catch (error) {}
</script>

<aside id="sidebar" class="fixed left-0 top-0 h-screen w-72 sm:w-72 xl:w-80 shrink-0 overflow-x-hidden overflow-y-auto text-white transform -translate-x-full transition-transform duration-300 xl:sticky xl:top-0 xl:self-start xl:translate-x-0 xl:flex xl:flex-col xl:h-screen z-[9999]" style="background: linear-gradient(180deg, #0B1220 0%, #070C16 100%); border-right: 1px solid rgba(148, 163, 184, 0.12); box-shadow: 18px 0 45px rgba(2, 6, 23, 0.22);">
    <div class="flex h-full flex-col justify-between min-h-screen xl:min-h-0">
        <div class="sidebar-content space-y-6 p-4 sm:p-6">
            <div class="space-y-4 text-center">
                <div class="hidden xl:flex justify-end">
                    <button id="sidebarCollapseBtn" type="button" class="sidebar-collapse-toggle inline-flex h-8 w-12 items-center rounded-full border border-white/10 bg-white/5 p-1 text-slate-200 transition hover:bg-white/10" aria-label="Collapse sidebar" aria-pressed="false" title="Collapse sidebar">
                        <span class="sidebar-toggle-thumb inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-700 shadow-sm">
                            <svg id="sidebarCollapseIcon" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    </button>
                </div>
                <div class="flex flex-col items-center justify-center gap-4 py-2">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-[#141F33] shadow-[0_12px_30px_rgba(0,0,0,0.3)] ring-1 ring-[#F4C95D]/10">
                        <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-10 w-10 object-contain" width="40" height="40" decoding="sync" fetchpriority="high" />
                    </div>
                    <div class="sidebar-brand-name">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-300">Project Tracker System</div>
                    </div>
                </div>
                <nav class="mt-6 space-y-2 border-t border-white/[0.06] pt-5">
                    @if($role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 rounded-3xl px-3 py-3 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Admin Dashboard">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/admin/users') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/users') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="User Access Management">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Users">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.64 2.26 1.59 2.97 2.95V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            </span>
                            User Access
                        </a>
                        <a href="{{ url('/admin/reports') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/reports') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="System Reports">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                        @if(auth()->user()->hasPermission('can_manage_backups'))
                            <a href="{{ url('/admin/backups') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/backups') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Database Backups">
                                <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Backups">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-1-5h2v2h-2zm0-10h2v8h-2z"/></svg>
                                </span>
                                Backups
                            </a>
                        @endif
                        <a href="{{ url('/admin/audit-logs') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('admin/audit-logs') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Audit Logs">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Audit Logs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            </span>
                            Audit Logs
                        </a>
                    @elseif($role === 'department')
                        <a href="{{ url('/department/dashboard') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('department.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Department Dashboard">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/department/projects') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('department.projects*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Projects">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 20h18v2H3v-2Zm2-2V9l7-4 7 4v9h-3v-6h-2v6h-4v-6H8v6H5Zm7-10.7L8.5 10h7L12 7.3Z"/></svg>
                            </span>
                            Projects
                        </a>
                        <a href="{{ url('/department/map') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('department.map*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Map">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="m9 4 6-2 6 2v16l-6 2-6-2-6 2V4l6-2Zm0 2.1L5 7.43v11.74l4-1.33V6.1Zm2 0v11.74l3 1V6.1l-3 1Zm5-.67v11.74l3-.99V6.43l-3-.99Z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/department/analytics') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('department.analytics*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Analytics">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                            </span>
                            Analytics
                        </a>
                        @if(Auth::check() && Auth::user()->isDepartmentHead())
                            <a href="{{ url('/department/project-permissions') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('department.project-permissions.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Permission Requests">
                                <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Permission Requests">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.25 3.44 10.17 9 12 5.56-1.83 9-6.75 9-12V5l-9-4zm0 2.18l6 2.67v4.15c0 4.15-2.74 8.16-6 9.49-3.26-1.33-6-5.34-6-9.49V5.85l6-2.67zm-1 4.84h2v5h-2zm0 6h2v2h-2z"/></svg>
                                </span>
                                Permission Requests
                            </a>
                        @endif
                        <a href="{{ url('/department/reports') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('department.reports*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Reports">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                    @elseif($role === 'engineering')
                        <a href="{{ url('/engineering/dashboard') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('engineering.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Engineering Dashboard">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/engineering/projects') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('engineering.projects*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Projects">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 20h18v2H3v-2Zm2-2V9l7-4 7 4v9h-3v-6h-2v6h-4v-6H8v6H5Zm7-10.7L8.5 10h7L12 7.3Z"/></svg>
                            </span>
                            Projects
                        </a>
                        <a href="{{ url('/engineering/map') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('engineering.map*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Map">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="m9 4 6-2 6 2v16l-6 2-6-2-6 2V4l6-2Zm0 2.1L5 7.43v11.74l4-1.33V6.1Zm2 0v11.74l3 1V6.1l-3 1Zm5-.67v11.74l3-.99V6.43l-3-.99Z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/engineering/analytics') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('engineering.analytics*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Analytics">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                            </span>
                            Analytics
                        </a>
                        <a href="{{ url('/engineering/reports') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->routeIs('engineering.reports*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Reports">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                    @elseif($role === 'city')
                        <a href="{{ url('/city/dashboard') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="City Dashboard">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/city/projects') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/projects*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="City Projects">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 20h18v2H3v-2Zm2-2V9l7-4 7 4v9h-3v-6h-2v6h-4v-6H8v6H5Zm7-10.7L8.5 10h7L12 7.3Z"/></svg>
                            </span>
                            Projects
                        </a>
                        <a href="{{ url('/city/map') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/map') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="City Map">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="m9 4 6-2 6 2v16l-6 2-6-2-6 2V4l6-2Zm0 2.1L5 7.43v11.74l4-1.33V6.1Zm2 0v11.74l3 1V6.1l-3 1Zm5-.67v11.74l3-.99V6.43l-3-.99Z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/city/analytics') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/analytics') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="City Analytics">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                            </span>
                            Analytics
                        </a>
                        <a href="{{ url('/city/reports') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('city/reports*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="City Reports">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                    @elseif($role === 'barangay')
                        <a href="{{ url('/barangay/dashboard') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('barangay/dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Barangay Dashboard">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/barangay/projects') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('barangay/projects*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Projects">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Projects">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 20h18v2H3v-2Zm2-2V9l7-4 7 4v9h-3v-6h-2v6h-4v-6H8v6H5Zm7-10.7L8.5 10h7L12 7.3Z"/></svg>
                            </span>
                            Projects
                        </a>
                        <a href="{{ url('/barangay/map') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('barangay/map') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Map">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="m9 4 6-2 6 2v16l-6 2-6-2-6 2V4l6-2Zm0 2.1L5 7.43v11.74l4-1.33V6.1Zm2 0v11.74l3 1V6.1l-3 1Zm5-.67v11.74l3-.99V6.43l-3-.99Z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/barangay/analytics') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('barangay/analytics') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Analytics">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/></svg>
                            </span>
                            Analytics
                        </a>
                        <a href="{{ url('/barangay/reports') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('barangay/reports') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Reports">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Reports">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm3 6H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                            </span>
                            Reports
                        </a>
                    @else
                        <a href="{{ url('/public/map') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold transition hover:bg-slate-800 {{ request()->is('public/map') ? 'bg-slate-800 text-white' : 'text-slate-300' }}" title="Public Map">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="m9 4 6-2 6 2v16l-6 2-6-2-6 2V4l6-2Zm0 2.1L5 7.43v11.74l4-1.33V6.1Zm2 0v11.74l3 1V6.1l-3 1Zm5-.67v11.74l3-.99V6.43l-3-.99Z"/></svg>
                            </span>
                            Map
                        </a>
                        <a href="{{ url('/public/analytics') }}" class="flex items-center gap-2 sm:gap-3 rounded-3xl px-3 py-3 sm:px-4 text-sm font-semibold text-slate-300 transition hover:bg-slate-800" title="Public Analytics">
                            <span class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;" aria-label="Analytics">
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
        <div class="border-t border-slate-800 p-4 sm:p-5" style="border-color: #162347;">
            <div class="sidebar-user-summary mb-4 flex items-center gap-2 sm:gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl" style="background-color: #162347; color: #c9a84c;">{{ strtoupper(substr($userName, 0, 2)) }}</div>
                <div class="sidebar-user-meta min-w-0">
                    <div class="text-sm font-semibold text-white truncate">{{ $userName }}</div>
                    <div class="text-[11px] text-slate-300 truncate">{{ $userEmail }}</div>
                </div>
            </div>
            <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="button" id="logoutTriggerBtn" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition font-semibold text-sm">
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

<style>
    #sidebarBackdrop {
        display: none;
    }

    #sidebarBackdrop.show {
        display: block;
    }

    @media (max-width: 1279px) {
        #sidebar {
            transform: translateX(-100%);
        }

        #sidebar.open {
            transform: translateX(0);
        }
    }

    #sidebar nav a > span {
        border: 1px solid rgba(244, 201, 93, 0.16);
        background: linear-gradient(145deg, #1D2B45 0%, #141F33 100%) !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06), 0 6px 16px rgba(0, 0, 0, 0.2);
        color: #F4C95D !important;
        transition: transform 180ms ease, border-color 180ms ease, box-shadow 180ms ease;
    }

    #sidebar nav a:hover {
        background-color: #141F33 !important;
    }

    #sidebar nav a.bg-slate-800 {
        background-color: #1D2B45 !important;
    }

    .sidebar-toggle-thumb {
        transform: translateX(1rem);
        transition: transform 200ms ease, background-color 200ms ease;
    }

    .sidebar-brand-name {
        max-height: 2rem;
        overflow: hidden;
        opacity: 1;
        transform: translateY(0);
        transition: max-height 200ms ease, opacity 160ms ease, transform 200ms ease;
        white-space: nowrap;
    }

    .sidebar-user-meta {
        max-width: 12rem;
        max-height: 2.5rem;
        overflow: hidden;
        opacity: 1;
        transform: translateY(0);
        transition: max-width 200ms ease, max-height 200ms ease, opacity 160ms ease, transform 200ms ease;
        white-space: nowrap;
    }

    .sidebar-collapse-toggle.is-collapsed .sidebar-toggle-thumb {
        transform: translateX(0);
        background-color: #1D2B45;
    }

    #sidebar nav a:hover > span,
    #sidebar nav a.bg-slate-800 > span {
        transform: translateY(-1px);
        border-color: rgba(244, 201, 93, 0.52);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 8px 20px rgba(0, 0, 0, 0.28), 0 0 0 3px rgba(201, 168, 76, 0.06);
    }

    @media (min-width: 1280px) {
        #sidebar {
            transition: none;
        }

        #sidebar.sidebar-collapse-ready {
            transition: width 300ms ease, transform 300ms ease;
        }

        html.sidebar-collapsed #sidebar {
            width: 6.5rem !important;
        }

        html.sidebar-collapsed #sidebar .sidebar-content {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        html.sidebar-collapsed #sidebar .hidden.xl\:flex.justify-end {
            justify-content: center !important;
        }

        html.sidebar-collapsed #sidebar .sidebar-collapse-toggle {
            margin-left: auto;
            margin-right: auto;
        }

        html.sidebar-collapsed #sidebar .sidebar-brand-name {
            max-height: 0;
            opacity: 0;
            transform: translateY(-0.25rem);
        }

        html.sidebar-collapsed #sidebar .sidebar-user-meta {
            max-width: 0;
            max-height: 0;
            opacity: 0;
            transform: translateY(-0.25rem);
        }

        html.sidebar-collapsed #sidebar .sidebar-user-summary {
            justify-content: center;
            gap: 0;
        }

        html.sidebar-collapsed #sidebar nav a {
            justify-content: center;
            gap: 0;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            font-size: 0;
        }

        html.sidebar-collapsed #sidebar #logoutTriggerBtn {
            width: auto;
            margin: 0 auto;
            gap: 0;
            font-size: 0;
        }
    }
</style>

@if($role !== 'public')
<!-- Logout Confirmation Modal -->
<div id="logoutConfirmModal" class="fixed inset-0 z-[20000] hidden items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-200">
    <div id="logoutConfirmDialog" class="w-full max-w-sm rounded-2xl bg-white p-7 shadow-2xl mx-4 scale-95 opacity-0 transition-all duration-200">
        <div class="flex flex-col items-center text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-full mb-4" style="background-color: #fee2e2;">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-black mb-1">Log out of your account?</h3>
            <p class="text-sm text-gray-500 mb-6">You'll need to sign in again to access the Project Tracker System.</p>
        </div>
        <div class="flex gap-3">
            <button type="button" id="logoutCancelBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm transition-colors">
                Cancel
            </button>
            <button type="button" id="logoutConfirmBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-colors shadow-sm shadow-red-200">
                Yes, Logout
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const collapseBtn = document.getElementById('sidebarCollapseBtn');
        const collapseIcon = document.getElementById('sidebarCollapseIcon');
        const sidebarStateKey = 'projectTrackerSidebarCollapsed';

        function setSidebarCollapsed(collapsed) {
            document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
            sidebar.classList.toggle('sidebar-collapsed', collapsed);
            collapseBtn.classList.toggle('is-collapsed', collapsed);
            collapseBtn.setAttribute('aria-pressed', String(collapsed));
            collapseBtn.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
            collapseBtn.title = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
            collapseIcon.innerHTML = collapsed
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />';
        }

        if (sidebar && collapseBtn && collapseIcon) {
            setSidebarCollapsed(localStorage.getItem(sidebarStateKey) === 'true');
            requestAnimationFrame(function() {
                sidebar.classList.add('sidebar-collapse-ready');
            });
            collapseBtn.addEventListener('click', function() {
                const collapsed = !sidebar.classList.contains('sidebar-collapsed');
                setSidebarCollapsed(collapsed);
                localStorage.setItem(sidebarStateKey, String(collapsed));
            });
        }

        const triggerBtn = document.getElementById('logoutTriggerBtn');
        const cancelBtn = document.getElementById('logoutCancelBtn');
        const confirmBtn = document.getElementById('logoutConfirmBtn');
        const modal = document.getElementById('logoutConfirmModal');
        const dialog = document.getElementById('logoutConfirmDialog');
        const form = document.getElementById('logoutForm');

        if (!triggerBtn || !modal) return;

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Force reflow so the transition plays
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                dialog.classList.remove('opacity-0', 'scale-95');
                dialog.classList.add('opacity-100', 'scale-100');
            });
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            dialog.classList.remove('opacity-100', 'scale-100');
            dialog.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        triggerBtn.addEventListener('click', openModal);
        cancelBtn.addEventListener('click', closeModal);
        confirmBtn.addEventListener('click', function() {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = 'Logging out...';
            form.submit();
        });

        // Close modal when clicking outside the dialog
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
        });
    });
</script>
@endif
