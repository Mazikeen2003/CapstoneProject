<div class="map-status-legend" aria-label="Project status legend">
    <button type="button" class="map-status-legend-toggle" aria-expanded="true" aria-controls="mapStatusLegendContent">
        <span class="map-status-legend-toggle-text">Project Status</span>
        <span class="map-status-legend-chevron" aria-hidden="true">▾</span>
    </button>

    <div class="map-status-legend-content" id="mapStatusLegendContent">
        <div class="map-status-legend-title">Project Status</div>
        <div class="map-status-legend-item"><span style="background:#2563eb"></span>Proposed</div>
        <div class="map-status-legend-item"><span style="background:#f59e0b"></span>For bidding</div>
        <div class="map-status-legend-item"><span style="background:#06b6d4"></span>Bidding ongoing</div>
        <div class="map-status-legend-item"><span style="background:#8b5cf6"></span>Award of contract</div>
        <div class="map-status-legend-item"><span style="background:#0f766e"></span>Implementation</div>
        <div class="map-status-legend-item"><span style="background:#16a34a"></span>Completed</div>
        <div class="map-status-legend-item"><span style="background:#dc2626"></span>On Hold</div>
        <div class="map-status-legend-item"><span style="background:#64748b"></span>Cancelled</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const legends = document.querySelectorAll('.map-status-legend');

        legends.forEach(function (legend) {
            const toggle = legend.querySelector('.map-status-legend-toggle');
            const content = legend.querySelector('.map-status-legend-content');
            const icon = toggle ? toggle.querySelector('.map-status-legend-chevron') : null;

            if (!toggle || !content || !icon) return;

            function syncState(forceClosed) {
                const shouldCollapse = forceClosed ?? legend.classList.contains('is-collapsed');
                legend.classList.toggle('is-collapsed', shouldCollapse);
                toggle.setAttribute('aria-expanded', String(!shouldCollapse));
                icon.textContent = shouldCollapse ? '▾' : '▴';
            }

            toggle.addEventListener('click', function () {
                const collapsed = legend.classList.contains('is-collapsed');
                syncState(!collapsed);
            });

            const mediaQuery = window.matchMedia('(max-width: 640px)');
            function applyMobileState() {
                syncState(mediaQuery.matches);
            }

            applyMobileState();
            if (mediaQuery.addEventListener) {
                mediaQuery.addEventListener('change', applyMobileState);
            } else {
                mediaQuery.addListener(applyMobileState);
            }
        });
    });
</script>

<style>
    .map-status-legend {
        position: absolute;
        right: 16px;
        bottom: 16px;
        z-index: 500;
        width: 168px;
        padding: 14px 14px 12px;
        border: 1px solid rgba(148, 163, 184, .35);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 12px 24px rgba(15, 23, 42, .12);
        color: #1f2937;
        font: 500 12px/1.25 Inter, system-ui, sans-serif;
        pointer-events: auto;
    }
    .map-status-legend-toggle {
        display: none;
        width: auto;
        max-width: 100%;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        border: none;
        background: transparent;
        color: #1f2937;
        font: inherit;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding: 0;
        cursor: pointer;
        white-space: nowrap;
    }
    .map-status-legend-toggle-text {
        white-space: nowrap;
    }
    .map-status-legend-chevron {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        line-height: 1;
        font-weight: 700;
        flex-shrink: 0;
    }
    .map-status-legend-title {
        margin-bottom: 10px;
        color: #374151;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .map-status-legend-content {
        display: block;
    }
    .map-status-legend-item {
        display: flex;
        align-items: center;
        gap: 9px;
        min-height: 21px;
        white-space: nowrap;
        color: #374151;
    }
    .map-status-legend-item span {
        width: 10px;
        height: 10px;
        flex: 0 0 10px;
        border-radius: 50%;
        box-shadow: 0 0 0 1px rgba(255,255,255,.14);
    }
    html.dark-mode .map-status-legend,
    .dark .map-status-legend {
        background: rgba(15, 23, 42, .94);
        box-shadow: 0 12px 24px rgba(15, 23, 42, .28);
        color: #e2e8f0;
    }
    html.dark-mode .map-status-legend-toggle,
    .dark .map-status-legend-toggle {
        color: #e2e8f0;
    }
    html.dark-mode .map-status-legend-title,
    .dark .map-status-legend-title {
        color: #cbd5e1;
    }
    html.dark-mode .map-status-legend-item,
    .dark .map-status-legend-item {
        color: #e2e8f0;
    }
    @media (max-width: 640px) {
        .map-status-legend {
            right: 10px;
            bottom: 10px;
            width: auto;
            min-width: 150px;
            max-width: calc(100vw - 20px);
            padding: 10px 11px;
        }
        .map-status-legend-toggle {
            display: flex;
            width: fit-content;
            min-width: 0;
        }
        .map-status-legend.is-collapsed .map-status-legend-content {
            display: none;
        }
        .map-status-legend .map-status-legend-title {
            display: none;
        }
    }
</style>
