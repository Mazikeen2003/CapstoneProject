@props([
    'barangays',
    'projects',
    'reportFilters' => [],
    'fixedBarangay' => null,
])

<style>
    .report-filter-panel {
        --report-surface: var(--dr-surface, var(--br-surface, var(--cr-surface, #ffffff)));
        --report-surface-hover: var(--dr-surface-hover, var(--br-surface-hover, var(--cr-surface-hover, #fafaf9)));
        --report-ink: var(--dr-ink, var(--br-ink, var(--cr-ink, #1e1b4b)));
        --report-muted: var(--dr-muted, var(--br-muted, var(--cr-muted, #6b7280)));
        --report-line: var(--dr-line, var(--br-line, var(--cr-line, rgba(0, 0, 0, 0.08))));
        --report-shadow: var(--dr-shadow-sm, var(--br-shadow-sm, var(--cr-shadow-sm, 0 1px 2px rgb(0 0 0 / 0.05))));
        margin-bottom: 24px;
        padding: 18px;
        border: 1px solid var(--report-line);
        border-radius: 12px;
        background: var(--report-surface);
        box-shadow: var(--report-shadow);
    }

    .report-filter-label {
        display: block;
        color: var(--report-muted);
        font-size: 0.6875rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .report-filter-value {
        margin: 10px 0 0;
        color: var(--report-ink);
        font-size: 0.9375rem;
        font-weight: 700;
    }

    .report-filter-select {
        display: block;
        width: 100%;
        margin-top: 8px;
        min-height: 42px;
        border: 1px solid var(--report-line);
        border-radius: 8px;
        background: var(--report-surface-hover);
        color: var(--report-ink);
        font-size: 0.875rem;
    }

    .report-filter-select:focus {
        border-color: #d97706;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
        outline: none;
    }

    .report-filter-button {
        min-height: 42px;
        border: 0;
        border-radius: 8px;
        background: var(--report-ink);
        color: var(--report-surface);
        font-size: 0.875rem;
        font-weight: 700;
        transition: opacity 0.2s ease;
    }

    .report-filter-button:hover { opacity: 0.86; }
    .report-filter-button:focus { outline: 3px solid rgba(217, 119, 6, 0.3); outline-offset: 2px; }
</style>

<form method="GET" class="report-filter-panel grid gap-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)_auto] sm:items-end">
    @if ($fixedBarangay)
        <div>
            <label class="report-filter-label">Barangay</label>
            <p class="report-filter-value">{{ $fixedBarangay->barangay_name }}</p>
        </div>
    @else
        <div>
            <label for="report-barangay" class="report-filter-label">Barangay</label>
            <select id="report-barangay" name="barangay_id" class="report-filter-select">
                <option value="">All barangays</option>
                @foreach ($barangays as $barangay)
                    <option value="{{ $barangay->barangay_id }}" @selected((string) ($reportFilters['barangay_id'] ?? '') === (string) $barangay->barangay_id)>{{ $barangay->barangay_name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div>
        <label for="report-project" class="report-filter-label">Project</label>
        <select id="report-project" name="project_id" class="report-filter-select">
            <option value="">All projects</option>
            @foreach ($projects as $project)
                <option value="{{ $project->project_id }}" data-barangay-id="{{ $project->barangay_id }}" @selected((string) ($reportFilters['project_id'] ?? '') === (string) $project->project_id)>{{ $project->project_name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="report-filter-button px-4">Apply filters</button>
</form>

@if (! $fixedBarangay)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const barangay = document.getElementById('report-barangay');
            const project = document.getElementById('report-project');
            if (!barangay || !project) return;

            const updateProjects = () => {
                [...project.options].forEach(option => {
                    if (!option.value) return;
                    option.hidden = Boolean(barangay.value) && option.dataset.barangayId !== barangay.value;
                });

                if (project.selectedOptions[0]?.hidden) project.value = '';
            };

            barangay.addEventListener('change', updateProjects);
            updateProjects();
        });
    </script>
@endif
