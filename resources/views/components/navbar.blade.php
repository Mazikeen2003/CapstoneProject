@php
    $authUser = Auth::user();
    $userName = $authUser ? $authUser->username : 'Guest';
    $isPublicRoute = request()->routeIs('public.*') || request()->is('public') || request()->is('public/*') || request()->is('ProjectTracker/public/*');
    $currentRole = $isPublicRoute ? 'public' : ($authUser?->role_slug ?? 'public');
    $panelTitle = $isPublicRoute ? 'Public Portal' : match($currentRole) {
        'admin' => 'Admin Overview',
        'department' => 'Department Dashboard',
        'city' => 'City Official Dashboard',
        'barangay' => 'Barangay Dashboard',
        default => 'Public Portal',
    };
    $panelSubtitle = $isPublicRoute ? '' : match($currentRole) {
        'admin' => 'Manage Access and monitor system Activity',
        'department' => 'Workspace for Cabuyao City Government',
        'city' => 'Citywide project oversight and analytics',
        'barangay' => 'Local Project Management Monitoring',
        default => '',
    };
@endphp

<script>
    window.__currentRole = @json($currentRole);
</script>
<header style="background-color: #F7F9FB; border-color: #E0E7F1;" class="border-b">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-2 sm:gap-4 px-3 py-1.5 sm:px-4 sm:py-2 sm:px-6 lg:px-8">
        <!-- Hamburger Menu (Mobile/Tablet) -->
        <button id="sidebarToggle" class="xl:hidden flex-shrink-0 inline-flex items-center justify-center rounded-md p-1.5 sm:p-2 transition hover:bg-gray-200" style="color: #0F172A;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

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

        <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0 overflow-visible">
            <div class="relative overflow-visible">
                <button id="notificationBtn" class="rounded-2xl p-2 sm:p-3 transition hover:opacity-80 relative" style="background-color: #F0F4F8; color: #0F172A;" title="Notifications" aria-label="Open notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span id="notificationBadge" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full" style="display: none; min-width: 20px; height: 20px;">0</span>
                </button>
                
                <!-- Notification Dropdown Panel -->
                <div id="notificationPanel" class="fixed z-[9999] w-[min(22rem,calc(100vw-2rem))] rounded-lg border border-gray-200 bg-white shadow-2xl" style="display: none; max-height: min(24rem, 70vh); overflow-y: auto;">
                    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-3">
                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                        <button id="clearNotificationsBtn" type="button" class="text-xs font-medium text-blue-600 hover:text-blue-800">Clear</button>
                    </div>
                    <div id="notificationList" class="divide-y divide-gray-200">
                        <div class="p-4 text-center text-sm text-gray-500">No new notifications</div>
                    </div>
                </div>
            </div>
            
            @if(!$isPublicRoute)
                <div class="rounded-full px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold truncate" style="color: #0F172A;">
                    <span class="hidden sm:inline">{{ $userName }}</span>
                    <span class="sm:hidden">User</span>
                </div>
            @endif
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notificationBtn = document.getElementById('notificationBtn');
                const notificationPanel = document.getElementById('notificationPanel');
                const notificationBadge = document.getElementById('notificationBadge');
                const notificationList = document.getElementById('notificationList');
                const clearNotificationsBtn = document.getElementById('clearNotificationsBtn');
                const storageKey = 'projectTrackerNotifications:' + (window.__currentRole || 'public');
                const pendingCookieName = 'project_tracker_pending_notification:' + (window.__currentRole || 'public');

                function getStoredNotifications() {
                    try {
                        return JSON.parse(localStorage.getItem(storageKey) || '[]');
                    } catch (error) {
                        return [];
                    }
                }

                function saveStoredNotifications(notifications) {
                    localStorage.setItem(storageKey, JSON.stringify(notifications));
                }

                function addNotification(notification) {
                    const notifications = getStoredNotifications();
                    if (!notifications.some(item => item.id === notification.id)) {
                        notifications.unshift(notification);
                        saveStoredNotifications(notifications);
                    }
                }

                function getCookie(name) {
                    const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + '=([^;]*)'));
                    return match ? decodeURIComponent(match[1]) : null;
                }

                function consumePendingNotification() {
                    const pendingValue = getCookie(pendingCookieName);
                    if (!pendingValue) {
                        return;
                    }

                    try {
                        const notification = JSON.parse(pendingValue);
                        addNotification(notification);
                    } catch (error) {
                        console.warn('Unable to parse pending notification', error);
                    }

                    document.cookie = pendingCookieName + '=; Max-Age=0; path=/';
                }

                function ensurePendingNotificationVisibility() {
                    consumePendingNotification();
                    renderNotifications();
                    updateNotificationBadge();
                }

                function updateNotificationBadge() {
                    const notifications = getStoredNotifications();
                    if (notifications.length > 0) {
                        notificationBadge.textContent = notifications.length > 9 ? '9+' : notifications.length;
                        notificationBadge.style.display = 'flex';
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                }

                function clearNotifications() {
                    saveStoredNotifications([]);
                    renderNotifications();
                    updateNotificationBadge();
                }

                function renderNotifications() {
                    const notifications = getStoredNotifications();
                    if (notifications.length === 0) {
                        notificationList.innerHTML = '<div class="p-4 text-center text-sm text-gray-500">No new notifications</div>';
                        return;
                    }

                    notificationList.innerHTML = notifications.map(notif => `
                        <div class="mx-3 my-2 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-500 hover:shadow-md">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm font-semibold text-slate-900">${notif.title}</p>
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600">${notif.time}</span>
                                    </div>
                                    <p class="mt-1 text-sm leading-5 text-slate-600">${notif.message}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }

                function positionNotificationPanel() {
                    const rect = notificationBtn.getBoundingClientRect();
                    notificationPanel.style.top = `${rect.bottom + 8}px`;
                    notificationPanel.style.left = `${Math.max(12, rect.right - 320)}px`;
                }

                notificationBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    positionNotificationPanel();
                    notificationPanel.style.display = notificationPanel.style.display === 'none' ? 'block' : 'none';
                    renderNotifications();
                });

                clearNotificationsBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    clearNotifications();
                });

                document.addEventListener('click', function(e) {
                    if (!notificationBtn.contains(e.target) && !notificationPanel.contains(e.target)) {
                        notificationPanel.style.display = 'none';
                    }
                });

                window.addEventListener('storage', function() {
                    renderNotifications();
                    updateNotificationBadge();
                });

                window.addEventListener('notifications:updated', function() {
                    renderNotifications();
                    updateNotificationBadge();
                });

                ensurePendingNotificationVisibility();
            });
        </script>
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
