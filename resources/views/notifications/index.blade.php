@extends($layout)

@section('content')
<div class="mx-auto max-w-5xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Activity center</p>
            <h1 class="mt-1 text-3xl font-bold text-slate-900">All Notifications</h1>
            <p class="mt-1 text-sm text-slate-500">Review activity relevant to your account and projects.</p>
        </div>
    </div>

    <div id="allNotificationsList" class="space-y-3">
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">Loading notifications...</div>
    </div>
    <div id="notificationsPagination" class="flex items-center justify-between gap-4 text-sm text-slate-600"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('allNotificationsList');
    const pagination = document.getElementById('notificationsPagination');
    const storageKey = 'projectTrackerNotifications:' + (window.__currentRole || 'public');
    const clearedAtKey = 'projectTrackerNotificationsClearedAt:' + (window.__currentRole || 'public');
    const pageSize = 10;
    let currentPage = 1;

    function getNotifications() {
        try { return JSON.parse(localStorage.getItem(storageKey) || '[]'); } catch (error) { return []; }
    }

    function saveNotifications(notifications) {
        localStorage.setItem(storageKey, JSON.stringify(notifications));
    }

    function render() {
        const notifications = getNotifications();
        if (!notifications.length) {
            list.innerHTML = '<div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">No notifications yet.</div>';
            pagination.innerHTML = '';
            return;
        }

        const pageCount = Math.ceil(notifications.length / pageSize);
        currentPage = Math.min(currentPage, pageCount);
        const pageNotifications = notifications.slice((currentPage - 1) * pageSize, currentPage * pageSize);

        list.innerHTML = pageNotifications.map(notification => {
            const read = Boolean(notification.read);
            const content = `<div class="flex items-start gap-4"><div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full ${read ? 'bg-slate-200 text-slate-500' : 'bg-emerald-50 text-emerald-600'}"><span class="text-lg">${read ? '&#10003;' : '&#9679;'}</span></div><div class="min-w-0 flex-1"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="font-semibold ${read ? 'text-slate-600' : 'text-slate-900'}">${notification.title || 'Notification'}</h2><time class="text-xs ${read ? 'text-slate-400' : 'text-emerald-600'}">${notification.time || ''}</time></div><p class="mt-1 text-sm ${read ? 'text-slate-500' : 'text-slate-700'}">${notification.message || ''}</p></div></div>`;
            return notification.url
                ? `<a href="${notification.url}" data-id="${notification.id}" class="block rounded-2xl border border-slate-200 border-l-4 ${read ? 'border-l-slate-300 bg-slate-100 opacity-70 grayscale' : 'border-l-emerald-500 bg-white'} p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">${content}</a>`
                : `<div data-id="${notification.id}" class="rounded-2xl border border-slate-200 border-l-4 ${read ? 'border-l-slate-300 bg-slate-100 opacity-70 grayscale' : 'border-l-emerald-500 bg-white'} p-5 shadow-sm">${content}</div>`;
        }).join('');

        pagination.innerHTML = `
            <span>Page ${currentPage} of ${pageCount}</span>
            <div class="flex items-center gap-2">
                <button type="button" data-page="${currentPage - 1}" class="rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 disabled:cursor-not-allowed disabled:opacity-40" ${currentPage === 1 ? 'disabled' : ''}>Previous</button>
                <button type="button" data-page="${currentPage + 1}" class="rounded-full border border-slate-300 bg-white px-3 py-1.5 font-semibold text-slate-700 disabled:cursor-not-allowed disabled:opacity-40" ${currentPage === pageCount ? 'disabled' : ''}>Next</button>
            </div>
        `;

        pagination.querySelectorAll('[data-page]').forEach(button => button.addEventListener('click', () => {
            currentPage = Number(button.dataset.page);
            render();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }));

        list.querySelectorAll('[data-id]').forEach(item => item.addEventListener('click', () => {
            const notifications = getNotifications();
            const match = notifications.find(notification => notification.id === item.dataset.id);
            if (match) {
                match.read = true;
                saveNotifications(notifications);
                render();
            }
        }));
    }

    async function loadAllNotifications() {
        try {
            const since = new Date('2000-01-01T00:00:00Z').toISOString();
            const response = await fetch('{{ route('api.notifications') }}?all=1&since=' + encodeURIComponent(since), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            if (!response.ok) return;

            const payload = await response.json();
            const existing = getNotifications();
            const byId = new Map(existing.map(notification => [notification.id, notification]));
            const clearedAt = localStorage.getItem(clearedAtKey);
            const clearedTimestamp = clearedAt ? Date.parse(clearedAt) : Number.NaN;
            (payload.notifications || []).forEach(notification => {
                const previous = byId.get(notification.id);
                const notificationTimestamp = Date.parse(notification.time || '');
                const wasCleared = !Number.isNaN(clearedTimestamp)
                    && !Number.isNaN(notificationTimestamp)
                    && notificationTimestamp <= clearedTimestamp;

                byId.set(notification.id, {
                    ...previous,
                    ...notification,
                    read: Boolean(previous?.read || wasCleared),
                });
            });
            saveNotifications(Array.from(byId.values()).sort((a, b) => Date.parse(b.time || 0) - Date.parse(a.time || 0)));
            render();
        } catch (error) {
            console.warn('Unable to load all notifications', error);
        }
    }

    render();
    loadAllNotifications();
});
</script>
@endsection