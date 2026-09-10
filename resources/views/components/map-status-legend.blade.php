<div class="map-status-legend" aria-label="Project status legend">
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
        background: rgba(15, 23, 42, .94);
        box-shadow: 0 12px 24px rgba(15, 23, 42, .28);
        color: #e2e8f0;
        font: 500 12px/1.25 Inter, system-ui, sans-serif;
        pointer-events: none;
    }
    .map-status-legend-title {
        margin-bottom: 10px;
        color: #cbd5e1;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .map-status-legend-item {
        display: flex;
        align-items: center;
        gap: 9px;
        min-height: 21px;
        white-space: nowrap;
    }
    .map-status-legend-item span {
        width: 10px;
        height: 10px;
        flex: 0 0 10px;
        border-radius: 50%;
        box-shadow: 0 0 0 1px rgba(255,255,255,.14);
    }
    @media (max-width: 640px) {
        .map-status-legend { right: 10px; bottom: 10px; width: 156px; padding: 11px; }
    }
</style>
