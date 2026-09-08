<style>
.public-theme-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    padding: 0;
    border: 1px solid var(--public-toggle-line, rgba(0, 0, 0, 0.1));
    border-radius: 10px;
    background: var(--public-toggle-surface, #f8f7f5);
    color: var(--public-toggle-muted, #6b7280);
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.public-theme-toggle:hover {
    background: var(--public-toggle-bg, #ffffff);
    border-color: #f59e0b;
    color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1), 0 4px 10px -2px rgba(245, 158, 11, 0.15);
    transform: translateY(-1px);
}
.public-theme-toggle.active {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-color: transparent;
    color: #ffffff;
    box-shadow: 0 4px 12px -2px rgba(245, 158, 11, 0.35);
}
html.dark-mode .public-theme-toggle {
    --public-toggle-bg: #141321;
    --public-toggle-surface: #1c1b2e;
    --public-toggle-muted: #94a3b8;
    --public-toggle-line: rgba(255, 255, 255, 0.1);
}
.public-theme-toggle svg { width: 20px; height: 20px; }
</style>

<button id="publicDarkModeBtn" type="button" class="public-theme-toggle" title="Enable dark mode" aria-label="Enable dark mode" aria-pressed="false">
    <svg id="publicDarkModeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
            button.classList.toggle('active', enabled);
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