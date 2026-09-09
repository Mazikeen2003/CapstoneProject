@php
    $authUser = Auth::user();
    $userName = $authUser ? $authUser->username : 'Guest';
    $isPublicRoute = request()->routeIs('public.*') || request()->is('public') || request()->is('public/*') || request()->is('ProjectTracker/public/*');
    $currentRole = $isPublicRoute ? 'public' : ($authUser?->role_slug ?? 'public');
    $panelTitle = $isPublicRoute ? 'Public Portal' : match($currentRole) {
        'admin' => 'Admin Overview',
        'department' => 'Planning Dashboard',
        'engineering' => 'Engineering',
        'city' => 'City Official Dashboard',
        'barangay' => 'Barangay Dashboard',
        default => 'Public Portal',
    };
    $panelSubtitle = $isPublicRoute ? '' : match($currentRole) {
        'admin' => 'Manage Access and monitor system Activity',
        'department' => 'Workspace for Cabuyao City Government',
        'engineering' => 'Engineering project monitoring and progress updates',
        'city' => 'Citywide project oversight and analytics',
        'barangay' => 'Local Project Management Monitoring',
        default => '',
    };
@endphp

{{--
    SUGGESTED FONT (add to your layout <head>):

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    Or if you prefer something else:
    - 'Space Grotesk'  -> bold, modern, slightly technical
    - 'DM Sans'        -> friendly, professional, very readable
    - 'Syne'           -> artistic, high-impact headlines
    - 'Satoshi'        -> clean geometric sans (self-hosted)
--}}

<style>
/* ===== HEADER DESIGN SYSTEM ===== */
.dept-header-wrap {
    --hd-bg: #ffffff;
    --hd-ink: #0f0d1f;
    --hd-muted: #6b7280;
    --hd-line: rgba(0,0,0,0.06);
    --hd-line-strong: rgba(0,0,0,0.1);
    --hd-surface: #f8f7f5;
    --hd-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.04);
    --hd-gold: #f59e0b;
    --hd-gold-light: #fef3c7;
    --hd-radius: 12px;
    --hd-transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);

    /* FONT VARIABLES - change these to your chosen font */
    --font-display: 'Outfit', 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
    --font-body: 'Inter', 'DM Sans', system-ui, sans-serif;
}
.dark .dept-header-wrap {
    --hd-bg: #141321;
    --hd-ink: #f8f7f5;
    --hd-muted: #94a3b8;
    --hd-line: rgba(255,255,255,0.06);
    --hd-line-strong: rgba(255,255,255,0.1);
    --hd-surface: #1c1b2e;
    --hd-shadow: 0 1px 3px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.15);
}

.dept-header-wrap {
    background: var(--hd-bg);
    border-bottom: 1px solid var(--hd-line);
    box-shadow: var(--hd-shadow);
    position: sticky;
    top: 0;
    z-index: 9000;
    transition: background 0.3s, border-color 0.3s;
}

.dept-header {
    max-width: 1440px;
    margin: 0 auto;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
@media (min-width: 640px) {
    .dept-header { padding: 14px 28px; }
}

/* ===== LEFT: HAMBURGER + TITLE ===== */
.dept-header-left {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
    flex: 1;
}

/* Hamburger */
.dept-header-hamburger {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid var(--hd-line-strong);
    background: var(--hd-surface);
    color: var(--hd-ink);
    cursor: pointer;
    transition: var(--hd-transition);
    flex-shrink: 0;
}
.dept-header-hamburger:hover {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 12px -2px rgba(245,158,11,0.35);
    transform: translateY(-1px);
}
.dept-header-hamburger svg { width: 20px; height: 20px; }
@media (min-width: 1280px) {
    .dept-header-hamburger { display: none; }
}

/* Title block */
.dept-header-title-block {
    min-width: 0;
}
.dept-header-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--hd-ink);
    letter-spacing: -0.02em;
    line-height: 1.15;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
@media (min-width: 640px) {
    .dept-header-title { font-size: 1.5rem; }
}
@media (min-width: 1024px) {
    .dept-header-title { font-size: 1.75rem; }
}
.dept-header-subtitle {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--hd-muted);
    margin-top: 3px;
    letter-spacing: 0.01em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: none;
}
@media (min-width: 640px) {
    .dept-header-subtitle { display: block; }
}

/* ===== RIGHT: ACTIONS ===== */
.dept-header-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}
@media (min-width: 640px) {
    .dept-header-right { gap: 12px; }
}

/* Action button base */
.dept-header-btn {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid var(--hd-line-strong);
    background: var(--hd-surface);
    color: var(--hd-muted);
    cursor: pointer;
    transition: var(--hd-transition);
    flex-shrink: 0;
}
.dept-header-btn:hover {
    background: var(--hd-bg);
    border-color: var(--hd-gold);
    color: var(--hd-gold);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.1), 0 4px 10px -2px rgba(245,158,11,0.15);
    transform: translateY(-1px);
}
.dept-header-btn.active {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 12px -2px rgba(245,158,11,0.35);
}
.dept-header-btn svg { width: 20px; height: 20px; }

/* Notification badge */
.dept-header-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    min-width: 18px;
    height: 18px;
    padding: 0 5px;
    border-radius: 100px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    font-family: var(--font-display);
    font-size: 0.625rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(220,38,38,0.3);
    border: 2px solid var(--hd-bg);
    transition: border-color 0.3s;
}

/* User button */
.dept-header-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 14px 6px 6px;
    border-radius: 100px;
    border: 1px solid var(--hd-line-strong);
    background: var(--hd-surface);
    color: var(--hd-ink);
    cursor: pointer;
    transition: var(--hd-transition);
    font-family: var(--font-body);
}
.dept-header-user:hover {
    border-color: var(--hd-gold);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.08);
}
.dept-header-user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e1b4b, #4338ca);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: 0.6875rem;
    font-weight: 800;
    flex-shrink: 0;
}
.dept-header-user-name {
    font-size: 0.8125rem;
    font-weight: 700;
    white-space: nowrap;
    display: none;
}
@media (min-width: 640px) {
    .dept-header-user-name { display: block; }
}
.dept-header-user-chevron {
    width: 16px;
    height: 16px;
    color: var(--hd-muted);
    transition: transform 0.2s;
}
.dept-header-user[aria-expanded="true"] .dept-header-user-chevron {
    transform: rotate(180deg);
}

/* ===== NOTIFICATION PANEL ===== */
.dept-notif-panel {
    position: fixed;
    z-index: 10001;
    width: min(380px, calc(100vw - 2rem));
    max-height: min(520px, 80vh);
    background: var(--hd-bg);
    border: 1px solid var(--hd-line-strong);
    border-radius: var(--hd-radius);
    box-shadow: 0 24px 48px -12px rgba(0,0,0,0.2), 0 12px 24px -12px rgba(0,0,0,0.1);
    overflow: hidden;
    display: none;
    flex-direction: column;
}
.dept-notif-panel.show { display: flex; }
.dept-notif-header {
    padding: 18px 20px;
    background: linear-gradient(135deg, #1e1b4b, #4338ca);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-shrink: 0;
}
.dept-notif-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.dept-notif-pulse {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #34d399;
    box-shadow: 0 0 0 3px rgba(52,211,153,0.3);
    animation: notifPulse 2s ease-in-out infinite;
}
@keyframes notifPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
}
.dept-notif-header h3 {
    font-family: var(--font-display);
    font-size: 0.9375rem;
    font-weight: 800;
    letter-spacing: -0.01em;
}
.dept-notif-header p {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    color: rgba(255,255,255,0.65);
    margin-top: 2px;
}
.dept-notif-header-actions {
    display: flex;
    gap: 6px;
}
.dept-notif-header-btn {
    padding: 5px 12px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.15);
    background: rgba(255,255,255,0.06);
    color: rgba(255,255,255,0.85);
    font-family: var(--font-body);
    font-size: 0.6875rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--hd-transition);
    white-space: nowrap;
}
.dept-notif-header-btn:hover {
    background: rgba(255,255,255,0.12);
    border-color: rgba(255,255,255,0.25);
}
.dept-notif-list {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    background: var(--hd-surface);
}
.dept-notif-list::-webkit-scrollbar { width: 4px; }
.dept-notif-list::-webkit-scrollbar-track { background: transparent; }
.dept-notif-list::-webkit-scrollbar-thumb { background: rgba(245,158,11,0.15); border-radius: 100px; }

/* Notification card */
.dept-notif-card {
    display: block;
    padding: 14px;
    border-radius: 10px;
    background: var(--hd-bg);
    border: 1px solid var(--hd-line);
    margin-bottom: 8px;
    transition: var(--hd-transition);
    text-decoration: none;
    cursor: pointer;
}
.dept-notif-card:last-child { margin-bottom: 0; }
.dept-notif-card:hover {
    border-color: var(--hd-line-strong);
    box-shadow: 0 4px 12px -2px rgba(0,0,0,0.06);
    transform: translateY(-1px);
}
.dept-notif-card.activity { border-left: 3px solid #10b981; }
.dept-notif-card.info     { border-left: 3px solid #3b82f6; }
.dept-notif-card-inner {
    display: flex;
    gap: 12px;
}
.dept-notif-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.dept-notif-icon.activity { background: #d1fae5; color: #059669; }
.dept-notif-icon.info     { background: #dbeafe; color: #2563eb; }
.dark .dept-notif-icon.activity { background: rgba(16,185,129,0.12); color: #34d399; }
.dark .dept-notif-icon.info     { background: rgba(59,130,246,0.12); color: #60a5fa; }
.dept-notif-icon svg { width: 16px; height: 16px; }
.dept-notif-content { min-width: 0; flex: 1; }
.dept-notif-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 4px;
}
.dept-notif-title {
    font-family: var(--font-display);
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--hd-ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dept-notif-time {
    padding: 2px 8px;
    border-radius: 100px;
    background: var(--hd-surface);
    font-family: var(--font-display);
    font-size: 0.625rem;
    font-weight: 700;
    color: var(--hd-muted);
    white-space: nowrap;
    flex-shrink: 0;
}
.dept-notif-message {
    font-family: var(--font-body);
    font-size: 0.75rem;
    color: var(--hd-muted);
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.dept-notif-empty {
    padding: 32px 20px;
    text-align: center;
    border-radius: 10px;
    border: 1px dashed var(--hd-line-strong);
    background: var(--hd-bg);
}
.dept-notif-empty-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;
    border-radius: 12px;
    background: var(--hd-surface);
    color: var(--hd-muted);
    display: flex;
    align-items: center;
    justify-content: center;
}
.dept-notif-empty-icon svg { width: 22px; height: 22px; }
.dept-notif-empty p {
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--hd-muted);
}

/* ===== ACCOUNT DROPDOWN ===== */
.dept-account-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    width: 220px;
    background: var(--hd-bg);
    border: 1px solid var(--hd-line-strong);
    border-radius: var(--hd-radius);
    box-shadow: 0 24px 48px -12px rgba(0,0,0,0.15), 0 12px 24px -12px rgba(0,0,0,0.08);
    overflow: hidden;
    z-index: 9999;
    display: none;
}
.dept-account-menu.show { display: block; }
.dept-account-header {
    padding: 14px 16px;
    border-bottom: 1px solid var(--hd-line);
    background: var(--hd-surface);
}
.dept-account-header-name {
    font-family: var(--font-display);
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--hd-ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dept-account-header-role {
    font-family: var(--font-body);
    font-size: 0.6875rem;
    color: var(--hd-muted);
    margin-top: 2px;
    text-transform: capitalize;
}
.dept-account-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    font-family: var(--font-body);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--hd-ink-secondary, #374151);
    text-decoration: none;
    transition: var(--hd-transition);
}
.dept-account-link:hover {
    background: var(--hd-surface);
    color: var(--hd-gold);
    padding-left: 20px;
}
.dept-account-link svg { width: 18px; height: 18px; flex-shrink: 0; }

/* ===== ANIMATIONS ===== */
@keyframes hdFadeDown {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.dept-header-animate {
    animation: hdFadeDown 0.35s ease forwards;
}
</style>

<script>
    window.__currentRole = @json($currentRole);
</script>

<div class="dept-header-wrap dept-header-animate">
    <div class="dept-header">
        <!-- Left -->
        <div class="dept-header-left">
            <button type="button" class="dept-header-hamburger" id="sidebarToggle" aria-label="Toggle sidebar">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
            <div class="dept-header-title-block">
                <h1 class="dept-header-title">{{ $panelTitle }}</h1>
                @if(!empty($panelSubtitle))
                    <p class="dept-header-subtitle">{{ $panelSubtitle }}</p>
                @endif
            </div>
        </div>

        <!-- Right -->
        <div class="dept-header-right">
            <!-- Dark Mode -->
            <button type="button" class="dept-header-btn" id="darkModeBtn" aria-label="Enable dark mode" title="Enable dark mode" aria-pressed="false">
                <svg id="darkModeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364-.707-.707M6.343 6.343l-.707-.707m12.728 0-.707.707M6.343 17.657l-.707.707M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </button>

            <!-- Notifications -->
            <div style="position:relative;">
                <button type="button" class="dept-header-btn" id="notificationBtn" aria-label="Notifications" title="Notifications">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="dept-header-badge" id="notificationBadge" style="display:none;">0</span>
                </button>

                <!-- Notification Panel -->
                <div class="dept-notif-panel" id="notificationPanel">
                    <div class="dept-notif-header">
                        <div class="dept-notif-header-left">
                            <span class="dept-notif-pulse"></span>
                            <div>
                                <h3>Notifications</h3>
                                <p>Live project activity</p>
                            </div>
                        </div>
                        <div class="dept-notif-header-actions">
                            <a href="{{ route('notifications.index') }}" class="dept-notif-header-btn">View all</a>
                            <button type="button" class="dept-notif-header-btn" id="clearNotificationsBtn">Clear all</button>
                        </div>
                    </div>
                    <div class="dept-notif-list" id="notificationList">
                        <div class="dept-notif-empty">
                            <div class="dept-notif-empty-icon">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <p>No new notifications</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account -->
            @if(!$isPublicRoute)
                <div style="position:relative;">
                    <button type="button" class="dept-header-user" id="accountMenuBtn" aria-expanded="false" aria-controls="accountMenu">
                        <div class="dept-header-user-avatar">{{ strtoupper(substr($userName, 0, 2)) }}</div>
                        <span class="dept-header-user-name">{{ $userName }}</span>
                        <svg class="dept-header-user-chevron" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
                    </button>
                    <div class="dept-account-menu" id="accountMenu" role="menu">
                        <div class="dept-account-header">
                            <div class="dept-account-header-name">{{ $userName }}</div>
                            <div class="dept-account-header-role">{{ $currentRole }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="dept-account-link" role="menuitem">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                            Change password
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function initializeNavbarControls() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationList = document.getElementById('notificationList');
    const clearNotificationsBtn = document.getElementById('clearNotificationsBtn');
    const darkModeBtn = document.getElementById('darkModeBtn');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const accountMenuBtn = document.getElementById('accountMenuBtn');
    const accountMenu = document.getElementById('accountMenu');
    const storageKey = 'projectTrackerNotifications:' + (window.__currentRole || 'public');
    const cursorKey = 'projectTrackerNotificationCursor:' + (window.__currentRole || 'public');
    const clearedAtKey = 'projectTrackerNotificationsClearedAt:' + (window.__currentRole || 'public');
    const pendingCookieName = 'project_tracker_pending_notification:' + (window.__currentRole || 'public');
    const darkModeKey = 'projectTrackerDarkMode';

    function setDarkMode(enabled) {
        document.documentElement.classList.toggle('dark-mode', enabled);
        document.documentElement.classList.toggle('dark', enabled);
        darkModeBtn.setAttribute('aria-pressed', String(enabled));
        darkModeBtn.setAttribute('aria-label', enabled ? 'Enable light mode' : 'Enable dark mode');
        darkModeBtn.title = enabled ? 'Enable light mode' : 'Enable dark mode';
        if (enabled) {
            darkModeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364-.707-.707M6.343 6.343l-.707-.707m12.728 0-.707.707M6.343 17.657l-.707.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
            darkModeBtn.classList.add('active');
        } else {
            darkModeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />';
            darkModeBtn.classList.remove('active');
        }
    }

    setDarkMode(localStorage.getItem(darkModeKey) === 'true');
    darkModeBtn.addEventListener('click', function() {
        const enabled = !document.documentElement.classList.contains('dark-mode');
        setDarkMode(enabled);
        localStorage.setItem(darkModeKey, String(enabled));
        if (window.location.pathname.includes('/analytics')) {
            const analyticsScrollContainer = document.querySelector('main');
            const scrollPosition = analyticsScrollContainer?.scrollTop || window.scrollY;
            sessionStorage.setItem('analyticsScrollPosition', String(scrollPosition));
        }
        window.dispatchEvent(new CustomEvent('theme:changed'));
    });

    if (accountMenuBtn && accountMenu) {
        accountMenuBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            const isOpen = accountMenu.classList.contains('show');
            accountMenu.classList.toggle('show', !isOpen);
            accountMenuBtn.setAttribute('aria-expanded', String(!isOpen));
        });
    }

    function getStoredNotifications() {
        try { return JSON.parse(localStorage.getItem(storageKey) || '[]'); }
        catch (error) { return []; }
    }
    function saveStoredNotifications(notifications) {
        localStorage.setItem(storageKey, JSON.stringify(notifications));
    }
    function markNotificationAsRead(notificationId) {
        const notifications = getStoredNotifications();
        const notification = notifications.find(item => item.id === notificationId);
        if (!notification) return;
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
        if (!pendingValue) return;
        try { addNotification(JSON.parse(pendingValue)); }
        catch (error) { console.warn('Unable to parse pending notification', error); }
        document.cookie = pendingCookieName + '=; Max-Age=0; path=/';
    }
    function ensurePendingNotificationVisibility() {
        consumePendingNotification();
        renderNotifications();
        updateNotificationBadge();
    }
    async function pollNotifications() {
        const storedCursor = localStorage.getItem(cursorKey);
        const storedNotifications = getStoredNotifications();
        const oldestCachedTime = storedNotifications.map(item => item.time || item.timestamp).filter(Boolean).sort()[0];
        const since = oldestCachedTime || storedCursor || new Date(Date.now() - 30000).toISOString();
        try {
            const response = await fetch('{{ route('api.notifications') }}?since=' + encodeURIComponent(since), {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!response.ok) return;
            const payload = await response.json();
            const latestClearedAt = localStorage.getItem(clearedAtKey);
            const latestStoredNotifications = getStoredNotifications();
            const fetchedNotifications = (payload.notifications || []).filter(notification => {
                return !latestClearedAt || !notification.time || Date.parse(notification.time) > Date.parse(latestClearedAt);
            });
            fetchedNotifications.forEach(notification => {
                const existingIndex = latestStoredNotifications.findIndex(item => item.id === notification.id);
                if (existingIndex >= 0) {
                    latestStoredNotifications[existingIndex] = { ...latestStoredNotifications[existingIndex], ...notification };
                } else {
                    latestStoredNotifications.unshift(notification);
                }
            });
            saveStoredNotifications(latestStoredNotifications);
            localStorage.setItem(cursorKey, new Date().toISOString());
            renderNotifications();
            updateNotificationBadge();
        } catch (error) { console.warn('Unable to refresh notifications', error); }
    }
    function updateNotificationBadge() {
        const unreadCount = getStoredNotifications().filter(n => !n.read).length;
        if (unreadCount > 0) {
            notificationBadge.textContent = unreadCount;
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
        if (!value) return 'Unknown time';
        const timestamp = typeof value === 'string' ? Date.parse(value) : Number(value);
        if (Number.isNaN(timestamp)) return value;
        const diffMs = Date.now() - timestamp;
        const diffMinutes = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        if (diffMinutes < 1) return 'Just now';
        if (diffMinutes < 60) return `${diffMinutes}m ago`;
        if (diffHours < 24) return `${diffHours}h ago`;
        return `${diffDays}d ago`;
    }
    function formatExactNotificationTime(value) {
        const timestamp = typeof value === 'string' ? Date.parse(value) : Number(value);
        if (Number.isNaN(timestamp)) return value;
        return new Date(timestamp).toLocaleString([], { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
    }
    function renderNotifications() {
        const notifications = getStoredNotifications().filter(n => !n.read);
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="dept-notif-empty">
                    <div class="dept-notif-empty-icon"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></div>
                    <p>No new notifications</p>
                </div>`;
            return;
        }
        notificationList.innerHTML = notifications.map(notif => {
            const timestampValue = notif.timestamp || notif.time;
            const displayTime = formatNotificationTime(timestampValue);
            const exactTime = formatExactNotificationTime(timestampValue);
            const isAuditActivity = notif.type === 'audit_activity';
            const typeClass = isAuditActivity ? 'activity' : 'info';
            const iconSvg = isAuditActivity
                ? '<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 12c0 5.591 3.824 10.291 9 11.623C17.176 22.291 21 17.591 21 12c0-1.042-.133-2.052-.382-3.016z"/></svg>'
                : '<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            const tag = notif.url ? 'a' : 'div';
            const href = notif.url ? ` href="${notif.url}"` : '';
            return `<${tag}${href} data-notification-id="${notif.id}" class="dept-notif-card ${typeClass}">
                <div class="dept-notif-card-inner">
                    <div class="dept-notif-icon ${typeClass}">${iconSvg}</div>
                    <div class="dept-notif-content">
                        <div class="dept-notif-title-row">
                            <span class="dept-notif-title">${notif.title}</span>
                            <span class="dept-notif-time" title="${exactTime}">${displayTime}</span>
                        </div>
                        <p class="dept-notif-message">${notif.message}</p>
                    </div>
                </div>
            </${tag}>`;
        }).join('');
        notificationList.querySelectorAll('[data-notification-id]').forEach(card => {
            card.addEventListener('click', () => markNotificationAsRead(card.dataset.notificationId));
        });
    }
    function positionNotificationPanel() {
        const rect = notificationBtn.getBoundingClientRect();
        const panelHeight = Math.min(520, Math.max(220, window.innerHeight - 32));
        const spaceBelow = window.innerHeight - rect.bottom - 16;
        const openAbove = spaceBelow < 220 && rect.top > panelHeight;
        const top = openAbove
            ? Math.max(16, rect.top - panelHeight - 8)
            : Math.min(rect.bottom + 8, window.innerHeight - panelHeight - 16);
        notificationPanel.style.top = `${top}px`;
        notificationPanel.style.left = 'auto';
        notificationPanel.style.right = `${Math.max(12, window.innerWidth - rect.right)}px`;
        notificationPanel.style.maxHeight = `${panelHeight}px`;
        notificationList.style.maxHeight = `${Math.max(140, panelHeight - 78)}px`;
    }
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        positionNotificationPanel();
        notificationPanel.classList.toggle('show');
        renderNotifications();
    });
    clearNotificationsBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        clearNotifications();
    });
    document.addEventListener('click', function(e) {
        if (!notificationBtn.contains(e.target) && !notificationPanel.contains(e.target)) {
            notificationPanel.classList.remove('show');
        }
        if (accountMenuBtn && accountMenu && !accountMenuBtn.contains(e.target) && !accountMenu.contains(e.target)) {
            accountMenu.classList.remove('show');
            accountMenuBtn.setAttribute('aria-expanded', 'false');
        }
    });
    window.addEventListener('resize', function() {
        if (notificationPanel.classList.contains('show')) positionNotificationPanel();
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
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeNavbarControls);
} else {
    initializeNavbarControls();
}

// Sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            if (backdrop) backdrop.classList.toggle('show');
        });
        if (backdrop) {
            backdrop.addEventListener('click', function() {
                sidebar.classList.remove('open');
                backdrop.classList.remove('show');
            });
        }
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1280) {
                    sidebar.classList.remove('open');
                    if (backdrop) backdrop.classList.remove('show');
                }
            });
        });
    }
});
</script>