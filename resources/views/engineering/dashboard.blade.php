@extends('layouts.department')

@section('content')
@php
    $budgetAllocated = (float) ($stats['budget_allocated'] ?? 0);
    $budgetDisplay = $budgetAllocated >= 1000000000
        ? '₱' . number_format($budgetAllocated / 1000000000, 1) . 'B'
        : ($budgetAllocated >= 1000000 ? '₱' . number_format($budgetAllocated / 1000000, 1) . 'M' : '₱' . number_format($budgetAllocated, 0));
@endphp
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" />
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .engineering-summary-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        flex-shrink: 0;
    }
    .engineering-summary-icon.blue { background: #dbeafe; color: #2563eb; }
    .engineering-summary-icon.amber { background: #fef3c7; color: #d97706; }
    .engineering-summary-icon.emerald { background: #d1fae5; color: #059669; }
    .engineering-summary-icon.rose { background: #ffe4e6; color: #e11d48; }
    html.dark-mode .engineering-summary-icon.blue,
    .dark .engineering-summary-icon.blue { background: rgba(59, 130, 246, 0.08); color: #60a5fa; }
    html.dark-mode .engineering-summary-icon.amber,
    .dark .engineering-summary-icon.amber { background: rgba(245, 158, 11, 0.08); color: #fbbf24; }
    html.dark-mode .engineering-summary-icon.emerald,
    .dark .engineering-summary-icon.emerald { background: rgba(16, 185, 129, 0.08); color: #34d399; }
    html.dark-mode .engineering-summary-icon.rose,
    .dark .engineering-summary-icon.rose { background: rgba(244, 63, 94, 0.08); color: #fb7185; }
</style>
<style>
    .engineering-dashboard .admin-dashboard-stat,
    .engineering-dashboard .admin-dashboard-activity {
        background: #ffffff !important;
    }

    .engineering-dashboard .engineering-recent-card {
        background: #f4f4f5 !important;
    }

    html.dark-mode .engineering-dashboard .admin-dashboard-stat,
    html.dark-mode .engineering-dashboard .admin-dashboard-activity,
    .dark .engineering-dashboard .admin-dashboard-stat,
    .dark .engineering-dashboard .admin-dashboard-activity {
        background: #141321 !important;
        border: 1px solid #020617 !important;
        box-shadow: inset 0 0 0 1px #1e293b, 0 1px 3px rgba(0, 0, 0, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    html.dark-mode .engineering-dashboard .engineering-recent-card,
    .dark .engineering-dashboard .engineering-recent-card {
        background: #0f0e1a !important;
    }

    .engineering-dashboard {
        max-width: 1400px;
        padding: 24px;
    }

    @media (min-width: 640px) {
        .engineering-dashboard { padding: 32px; }
    }

    @media (min-width: 1024px) {
        .engineering-dashboard { padding: 40px; }
    }

    .engineering-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        border-radius: 20px;
        padding: 36px 40px;
        background: linear-gradient(135deg, #082f49 0%, #075985 48%, #0e7490 100%);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    @media (min-width: 640px) {
        .engineering-hero { padding: 44px 48px; }
    }

    .engineering-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.5;
        pointer-events: none;
    }

    .engineering-hero-content { position: relative; z-index: 1; }

    .engineering-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .engineering-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 999px;
        padding: 6px 14px;
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.9);
        font-size: 0.75rem;
        font-weight: 600;
    }

    .engineering-quick-actions {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }

    .engineering-quick-actions::-webkit-scrollbar { display: none; }

    .engineering-quick-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 999px;
        padding: 10px 18px;
        background: #fff;
        color: #0f172a;
        font-size: 0.8125rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .engineering-quick-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
    }

    .engineering-main-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    @media (min-width: 1024px) {
        .engineering-main-grid { grid-template-columns: 1.2fr 0.8fr; }
    }

    .engineering-panel-card {
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
    }

    .engineering-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        padding: 20px 24px;
    }

    .engineering-panel-body { padding: 20px 24px; }

    .engineering-map-frame {
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 8px;
    }

    .engineering-map-frame #engineering-map {
        height: 420px;
        width: 100%;
    }

    html.dark-mode .engineering-quick-action,
    .dark .engineering-quick-action,
    html.dark-mode .engineering-panel-card,
    .dark .engineering-panel-card {
        border-color: #1e293b;
        background: #141321;
        color: #e2e8f0;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<div class="engineering-dashboard max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="engineering-hero">
        <div class="engineering-hero-content">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-amber-300">Engineering workspace</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Engineering Dashboard</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">Review project delivery, track field progress, and monitor citywide implementation health.</p>
            <div class="engineering-hero-meta">
                <span class="engineering-hero-badge"><span aria-hidden="true">●</span> Live project monitoring</span>
                <span class="engineering-hero-badge"><span aria-hidden="true">✓</span> Progress updates enabled</span>
            </div>
        </div>
    </div>

    <div class="engineering-quick-actions">
        <a href="{{ route('engineering.projects.index') }}" class="engineering-quick-action">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A2.5 2.5 0 015.5 5h4l2 2h7A2.5 2.5 0 0121 9.5v9a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 18.5v-11z"/></svg>
            View Projects
        </a>
        <a href="{{ route('engineering.map.index') }}" class="engineering-quick-action">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
            Open Full Map
        </a>
        <a href="{{ route('engineering.analytics.index') }}" class="engineering-quick-action">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19V9m7 10V5m7 14v-7"/></svg>
            View Analytics
        </a>
        <a href="{{ route('engineering.reports.index') }}" class="engineering-quick-action">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3h8l4 4v14H6a2 2 0 01-2-2V5a2 2 0 012-2zm8 0v5h5"/></svg>
            Generate Report
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="admin-dashboard-stat admin-dashboard-stat-blue rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-600">Total Projects</p><span class="engineering-summary-icon blue material-symbols-outlined">folder_open</span></div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['total_projects'] }}</p>
        </div>
        <div class="admin-dashboard-stat admin-dashboard-stat-amber rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-600">Ongoing Projects</p><span class="engineering-summary-icon amber material-symbols-outlined">pending_actions</span></div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['ongoing'] }}</p>
        </div>
        <div class="admin-dashboard-stat admin-dashboard-stat-emerald rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3"><p class="text-sm font-semibold text-slate-600">Completed Projects</p><span class="engineering-summary-icon emerald material-symbols-outlined">task_alt</span></div>
            <p class="mt-4 text-4xl font-bold text-slate-950">{{ $stats['completed'] }}</p>
        </div>

        <div class="ed-stat ed-animate" style="--stat-accent: #f59e0b; --stat-icon-bg: #fef3c7; --stat-icon-color: #d97706;">
            <div class="ed-stat-header">
                <div class="ed-stat-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.412 15.655L9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L6.75 3.75l8.586 8.986M12.75 3.75h5.695l-2.659 2.849m-2.048 2.194L17.25 12.75l-4.518 4.518"/></svg>
                </div>
            </div>
            <div class="ed-stat-label">Ongoing Projects</div>
            <div class="ed-stat-value">{{ $stats['ongoing'] ?? 0 }}</div>
            <div class="ed-stat-footer">Currently active</div>
        </div>

    <div class="engineering-main-grid">
    <div class="engineering-panel-card">
        <div class="engineering-panel-header">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Project Locations</h2>
                <p class="mt-1 text-xs text-slate-500">Geographic distribution across Cabuyao</p>
            </div>
            <a href="{{ route('engineering.map.index') }}" class="text-sm font-semibold text-cyan-700 hover:text-cyan-900">Full Map →</a>
        </div>
        <div class="engineering-panel-body">
            <div class="engineering-map-frame">
            <div id="engineering-map" class="relative z-0 h-[42vh] overflow-hidden rounded-3xl border border-slate-200 sm:h-[48vh] md:h-[56vh]"></div>
            </div>
        </div>
    </div>

    <div class="engineering-panel-card">
        <div class="engineering-panel-header">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Recent Projects</h2>
                <p class="mt-1 text-xs text-slate-500">Latest engineering review activity</p>
            </div>
            <a href="{{ route('engineering.projects.index') }}" class="text-sm font-semibold text-cyan-700 hover:text-cyan-900">View All →</a>
        </div>

        <div class="engineering-panel-body">
            @if ($recentProjects->isEmpty())
                <p class="mt-4 text-sm text-slate-500">No projects yet.</p>
            @else
                <div class="mt-4 grid gap-4">
                    @foreach ($recentProjects as $project)
                        <div class="engineering-recent-card rounded-2xl border border-slate-200 p-4 shadow-sm">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <p class="text-base font-semibold text-slate-900">{{ $project->project_name }}</p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                        <span class="rounded-full bg-cyan-50 px-3 py-1 font-semibold text-cyan-700">{{ $project->current_status }}</span>
                                        <span>{{ $project->barangay?->barangay_name ?? 'Citywide' }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('engineering.projects.show', $project->project_id) }}" class="inline-flex shrink-0 rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100">View</a>
                            </div>
                        @endforeach
                    </div>
                    @if ($recentProjects->hasPages())
                        <div class="ed-pagination">{{ $recentProjects->links('vendor.pagination.custom') }}</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ asset('data/cabuyao-map.geojson') }}')
            .then(response => response.json())
            .then(function(geojson) {
                const boundaryFeature = geojson.features?.find(f => f.properties?.kind === 'boundary');
                const geoJsonBoundary = boundaryFeature ? boundaryFeature : (geojson.features?.length ? geojson : null);

                if (!geoJsonBoundary) {
                    console.error('GeoJSON boundary is missing or malformed:', geojson);
                    return;
                }

                const cabuyaoBounds = L.geoJSON(geoJsonBoundary).getBounds();
                const map = L.map('engineering-map', {
                    maxBounds: cabuyaoBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'OpenStreetMap contributors',
                    maxZoom: 19,
                    minZoom: 11
                }).addTo(map);

                L.geoJSON(geoJsonBoundary, {
                    style: {
                        color: '#3b82f6',
                        weight: 2,
                        opacity: 0.6,
                        fillOpacity: 0.1
                    }
                }).addTo(map);

                map.fitBounds(cabuyaoBounds, { padding: [20, 20] });
                map.setMinZoom(map.getZoom());

                fetch('{{ route('api.projects.geojson') }}')
                    .then(r => r.json())
                    .then(function(data) {
                        L.geoJSON(data, {
                            pointToLayer: function(feature, latlng) {
                                const statusColor = {
                                    proposed: '#fbbf24',
                                    planning: '#fbbf24',
                                    forbidding: '#f59e0b',
                                    biddingongoing: '#3b82f6',
                                    ongoing: '#3b82f6',
                                    awardofcontract: '#8b5cf6',
                                    implementation: '#0ea5e9',
                                    completed: '#10b981',
                                    onhold: '#ef4444',
                                    cancelled: '#64748b'
                                };
                                const normalizedStatus = String(feature.properties.status || '')
                                    .trim()
                                    .toLowerCase()
                                    .replace(/[\s_-]+/g, '');

                                return L.circleMarker(latlng, {
                                    radius: 8,
                                    fillColor: statusColor[normalizedStatus] || '#64748b',
                                    color: '#000',
                                    weight: 2,
                                    opacity: 0.8,
                                    fillOpacity: 0.7
                                });
                            },
                            onEachFeature: function(feature, layer) {
                                const props = feature.properties;
                                layer.bindPopup(`
                                    <div class="text-sm">
                                        <h4 class="font-bold">${props.name}</h4>
                                        <p class="text-xs text-gray-600">${props.code}</p>
                                        <p class="text-xs"><strong>Status:</strong> ${props.status}</p>
                                        <p class="text-xs"><strong>Barangay:</strong> ${props.barangay}</p>
                                        <p class="text-xs"><strong>Budget:</strong> ₱${parseInt(props.budget).toLocaleString()}</p>
                                        <a href="${props.url}" class="text-blue-600 text-xs">View Details</a>
                                    </div>
                                `);
                            }
                        }).addTo(map);
                    })
                    .catch(err => console.error('Failed to load projects:', err));

                setTimeout(() => map.invalidateSize(), 100);
            })
            .catch(err => console.error('Failed to load map:', err));
    });
</script>
@endsection
