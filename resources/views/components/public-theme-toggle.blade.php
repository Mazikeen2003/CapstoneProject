<button id="publicDarkModeBtn" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 transition hover:bg-slate-100" title="Enable dark mode" aria-label="Enable dark mode" aria-pressed="false">
    <svg id="publicDarkModeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
    </svg>
</button>

<script>
    function initializePublicDarkModeToggle() {
        const button = document.getElementById('publicDarkModeBtn');
        const icon = document.getElementById('publicDarkModeIcon');
        if (!button || !icon) return;

        function setPublicDarkMode(enabled) {
            document.documentElement.classList.toggle('dark-mode', enabled);
            button.setAttribute('aria-pressed', String(enabled));
            button.setAttribute('aria-label', enabled ? 'Enable light mode' : 'Enable dark mode');
            button.title = enabled ? 'Enable light mode' : 'Enable dark mode';
            icon.innerHTML = enabled
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364-.707-.707M6.343 6.343l-.707-.707m12.728 0-.707.707M6.343 17.657l-.707.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />';
        }

        setPublicDarkMode(localStorage.getItem('projectTrackerDarkMode') === 'true');
        button.addEventListener('click', function () {
            const enabled = !document.documentElement.classList.contains('dark-mode');
            setPublicDarkMode(enabled);
            localStorage.setItem('projectTrackerDarkMode', String(enabled));
            window.dispatchEvent(new CustomEvent('theme:changed'));
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePublicDarkModeToggle);
    } else {
        initializePublicDarkModeToggle();
    }
</script>