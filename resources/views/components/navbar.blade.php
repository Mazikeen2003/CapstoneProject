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
            <div class="w-full max-w-2xl">
                <div class="px-2 py-1 sm:px-4 sm:py-2">
                    <div class="text-sm font-semibold text-slate-900 sm:text-base lg:text-lg leading-tight">
                        {{ $panelTitle }}
                    </div>
                    @if(!empty($panelSubtitle))
                        <div class="mt-0.5 hidden sm:block text-xs text-slate-600 lg:text-base leading-tight">
                            {{ $panelSubtitle }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0 overflow-visible">
            <div class="relative overflow-visible">
                <button id="notificationBtn" class="rounded-2xl p-2 sm:p-2.5 transition hover:opacity-80 relative" style="background-color: #F0F4F8; color: #0F172A;" title="Notifications" aria-label="Open notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span id="notificationBadge" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold leading-none text-white bg-red-600 rounded-full" style="display: none; min-width: 18px; height: 18px;">0</span>
                </button>
                
                <!-- Notification Dropdown Panel -->
                <div id="notificationPanel" class="fixed z-[9999] w-[min(24rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl" style="display: none; max-height: min(34rem, 75vh);">
                    <div class="flex items-center justify-between border-b border-slate-200 bg-gradient-to-r from-slate-900 to-slate-800 px-5 py-4 text-white">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></span>
                                <h3 class="text-sm font-semibold">Notifications</h3>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-300">Live project activity</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('notifications.index') }}" class="rounded-full border border-white/20 px-3 py-1 text-xs font-semibold text-slate-200 transition hover:bg-white/10 hover:text-white">View all</a>
                            <button id="clearNotificationsBtn" type="button" class="rounded-full border border-white/20 px-3 py-1 text-xs font-semibold text-slate-200 transition hover:bg-white/10 hover:text-white">Clear all</button>
                        </div>
                    </div>
                    <div id="notificationList" class="max-h-[calc(75vh-5rem)] space-y-3 overflow-y-auto bg-slate-50 p-3">
                        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">No new notifications</div>
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
                const cursorKey = 'projectTrackerNotificationCursor:' + (window.__currentRole || 'public');
                const clearedAtKey = 'projectTrackerNotificationsClearedAt:' + (window.__currentRole || 'public');
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

                function markNotificationAsRead(notificationId) {
                    const notifications = getStoredNotifications();
                    const notification = notifications.find(item => item.id === notificationId);
                    if (!notification) {
                        return;
                    }

                    notification.read = true;
                    saveStoredNotifications(notifications);
                    renderNotifications();
                    updateNotificationBadge();
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

                async function pollNotifications() {
                    const storedCursor = localStorage.getItem(cursorKey);
                    const clearedAt = localStorage.getItem(clearedAtKey);
                    const storedNotifications = getStoredNotifications();
                    const oldestCachedTime = storedNotifications
                        .map(item => item.time || item.timestamp)
                        .filter(Boolean)
                        .sort()[0];
                    const since = oldestCachedTime || storedCursor || new Date(Date.now() - 30000).toISOString();

                    try {
                        const response = await fetch('{{ route('api.notifications') }}?since=' + encodeURIComponent(since), {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });
                        if (!response.ok) {
                            return;
                        }

                        const payload = await response.json();
                        const fetchedNotifications = (payload.notifications || []).filter(notification => {
                            return !clearedAt || !notification.time || Date.parse(notification.time) > Date.parse(clearedAt);
                        });
                        // Refresh cached notifications so older entries gain their destination URL.
                        fetchedNotifications.forEach(notification => {
                            const existingIndex = storedNotifications.findIndex(item => item.id === notification.id);
                            if (existingIndex >= 0) {
                                storedNotifications[existingIndex] = { ...storedNotifications[existingIndex], ...notification };
                            } else {
                                storedNotifications.unshift(notification);
                            }
                        });
                        saveStoredNotifications(storedNotifications);
                        localStorage.setItem(cursorKey, new Date().toISOString());
                        renderNotifications();
                        updateNotificationBadge();
                    } catch (error) {
                        console.warn('Unable to refresh notifications', error);
                    }
                }

                function updateNotificationBadge() {
                    const notifications = getStoredNotifications();
                    const unreadCount = notifications.filter(notification => !notification.read).length;
                    if (unreadCount > 0) {
                        notificationBadge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                        notificationBadge.style.display = 'flex';
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                }

                function clearNotifications() {
                    saveStoredNotifications([]);
                    const clearedAt = new Date().toISOString();
                    localStorage.setItem(clearedAtKey, clearedAt);
                    localStorage.setItem(cursorKey, clearedAt);
                    renderNotifications();
                    updateNotificationBadge();
                }

                function formatNotificationTime(value) {
                    if (!value) {
                        return 'Unknown time';
                    }

                    const timestamp = typeof value === 'string' ? Date.parse(value) : Number(value);
                    if (Number.isNaN(timestamp)) {
                        return value;
                    }

                    const diffMs = Date.now() - timestamp;
                    const diffMinutes = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMs / 3600000);
                    const diffDays = Math.floor(diffMs / 86400000);

                    if (diffMinutes < 1) {
                        return 'Just now';
                    }
                    if (diffMinutes < 60) {
                        return `${diffMinutes} minute${diffMinutes === 1 ? '' : 's'} ago`;
                    }
                    if (diffHours < 24) {
                        return `${diffHours} hour${diffHours === 1 ? '' : 's'} ago`;
                    }
                    return `${diffDays} day${diffDays === 1 ? '' : 's'} ago`;
                }

                function formatExactNotificationTime(value) {
                    const timestamp = typeof value === 'string' ? Date.parse(value) : Number(value);
                    if (Number.isNaN(timestamp)) {
                        return value;
                    }

                    return new Date(timestamp).toLocaleString([], {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                    });
                }

                function renderNotifications() {
                    const notifications = getStoredNotifications();
                    if (notifications.length === 0) {
                        notificationList.innerHTML = '<div class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">No new notifications</div>';
                        return;
                    }

                    notificationList.innerHTML = notifications.map(notif => {
                        const timestampValue = notif.timestamp || notif.time;
                        const displayTime = formatNotificationTime(timestampValue);
                        const exactTime = formatExactNotificationTime(timestampValue);
                        const isAuditActivity = notif.type === 'audit_activity';
                        const isRead = Boolean(notif.read);
                        const accentClass = isAuditActivity ? 'border-l-emerald-500' : 'border-l-blue-500';
                        const iconClass = isAuditActivity ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600';
                        const notificationTag = notif.url ? 'a' : 'div';
                        const notificationHref = notif.url ? ` href="${notif.url}"` : '';
                        const clickableClass = notif.url ? 'cursor-pointer hover:bg-slate-50' : '';
                        const readClass = isRead ? 'border-l-slate-300 bg-slate-100 opacity-70 grayscale' : '';
                        const readIconClass = isRead ? 'bg-slate-200 text-slate-500' : iconClass;

                        return `
                        <${notificationTag}${notificationHref} data-notification-id="${notif.id}" class="block rounded-xl border border-slate-200 border-l-4 ${isRead ? 'border-l-slate-300' : accentClass} ${isRead ? 'bg-slate-100 opacity-70 grayscale' : 'bg-white'} p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md ${clickableClass}">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full ${readIconClass}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isAuditActivity ? 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 12c0 5.591 3.824 10.291 9 11.623C17.176 22.291 21 17.591 21 12c0-1.042-.133-2.052-.382-3.016z' : 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'}" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm font-semibold ${isRead ? 'text-slate-600' : 'text-slate-900'}">${notif.title}</p>
                                        <span title="${exactTime}" class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600">${displayTime}</span>
                                    </div>
                                    <p class="mt-1 text-sm leading-5 ${isRead ? 'text-slate-500' : 'text-slate-600'}">${notif.message}</p>
                                </div>
                            </div>
                        </${notificationTag}>
                    `;
                    }).join('');

                    notificationList.querySelectorAll('[data-notification-id]').forEach(card => {
                        card.addEventListener('click', () => markNotificationAsRead(card.dataset.notificationId));
                    });
                }

                function positionNotificationPanel() {
                    const rect = notificationBtn.getBoundingClientRect();
                    const panelHeight = Math.min(544, Math.max(220, window.innerHeight - 32));
                    const spaceBelow = window.innerHeight - rect.bottom - 16;
                    const openAbove = spaceBelow < 220 && rect.top > panelHeight;
                    const top = openAbove
                        ? Math.max(16, rect.top - panelHeight - 8)
                        : Math.min(rect.bottom + 8, window.innerHeight - panelHeight - 16);

                    notificationPanel.style.top = `${top}px`;
                    notificationPanel.style.left = `${Math.max(12, rect.right - 320)}px`;
                    notificationPanel.style.maxHeight = `${panelHeight}px`;
                    notificationList.style.maxHeight = `${Math.max(140, panelHeight - 82)}px`;
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

                window.addEventListener('resize', function() {
                    if (notificationPanel.style.display !== 'none') {
                        positionNotificationPanel();
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
                pollNotifications();
                window.setInterval(pollNotifications, 10000);
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
