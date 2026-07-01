@php
    $authUser = Auth::user();
    $userName = $authUser ? $authUser->username : 'Guest';
    // Treat public routes as public so navbar title/subtitle remain appropriate
    $isPublicRoute = request()->routeIs('public.*') || request()->is('public') || request()->is('public/*') || request()->is('ProjectTracker/public/*');
    $currentRole = $isPublicRoute ? 'public' : ($authUser?->role_slug ?? 'public');
@endphp

<header style="background-color: #F7F9FB; border-color: #E0E7F1;" class="border-b overflow-hidden">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-2 sm:gap-4 px-3 py-1.5 sm:px-4 sm:py-2 sm:px-6 lg:px-8">
        <!-- Hamburger Menu (Mobile/Tablet) -->
        <button id="sidebarToggle" class="xl:hidden flex-shrink-0 inline-flex items-center justify-center rounded-md p-1.5 sm:p-2 transition hover:bg-gray-200" style="color: #0F172A;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        @php
            $panelTitle = match($currentRole) {
                'admin' => 'Admin Overview',
                'department' => 'Department Dashboard',
                'city' => 'City Official Dashboard',
                'barangay' => 'Barangay Dashboard',
                default => 'Public Portal',
            };
            $panelSubtitle = match($currentRole) {
                'admin' => 'Manage Access and monitor system Activity',
                'department' => 'Workspace for Cabuyao City Government',
                'city' => 'Citywide project oversight and analytics',
                'barangay' => 'Local Project Management Monitoring',
                default => '',
            };
        @endphp

        <div class="flex flex-1 items-center gap-2 sm:gap-4 min-w-0">
            <div class="hidden sm:block w-full max-w-2xl">
                <div class="px-3 py-1.5 sm:px-4 sm:py-2">
                    <div class="text-sm sm:text-base lg:text-lg font-semibold text-slate-900 leading-tight">
                        {{ $panelTitle }}
                    </div>
                    @if(!empty($panelSubtitle))
                        <div class="mt-0.5 text-xs sm:text-sm lg:text-base text-slate-600 leading-tight">
                            {{ $panelSubtitle }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
            <button class="rounded-2xl p-2 sm:p-3 transition hover:opacity-80" style="background-color: #F0F4F8; color: #0F172A;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <div class="rounded-full px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold truncate" style="color: #0F172A;">
                <span class="hidden sm:inline">{{ $userName }}</span>
                <span class="sm:hidden">User</span>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.style.display = backdrop.style.display === 'none' ? 'block' : 'none';
            });

            // Close sidebar when backdrop is clicked
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.style.display = 'none';
                });
            }

            // Close sidebar when clicking on a navigation link
            const navLinks = sidebar.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1280) { // xl breakpoint
                        sidebar.classList.add('-translate-x-full');
                        backdrop.style.display = 'none';
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1280) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.style.display = 'none';
                }
            });
        }
    });
</script>
