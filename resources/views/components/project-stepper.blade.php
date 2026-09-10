@php
    $steps = ['Proposed', 'For bidding', 'Bidding ongoing', 'Award of contract', 'Implementation', 'Completed'];
    $stageByStatus = [
        'Proposed' => 0,
        'Planning' => 0,
        'For bidding' => 1,
        'Procurement' => 1,
        'Bidding ongoing' => 2,
        'Bidding - Success' => 3,
        'Award of contract' => 3,
        'Implementation' => 4,
        'On Going' => 4,
        'Completed' => 5,
    ];
    $activeStep = $stageByStatus[$project->current_status] ?? null;
@endphp

<style>
    .dept-stepper-lifecycle {
        --de-surface: var(--ds-surface, #ffffff);
        --de-line: var(--ds-line, rgba(0, 0, 0, 0.06));
        --de-line-strong: var(--ds-line-strong, rgba(0, 0, 0, 0.12));
        --de-ink: var(--ds-ink, #1e1b4b);
        --de-ink-secondary: var(--ds-ink-secondary, #374151);
        --de-muted: var(--ds-muted, #9ca3af);
        --de-shadow-sm: var(--ds-shadow-sm, 0 1px 2px 0 rgb(0 0 0 / 0.05));
        background: var(--de-surface);
        border: 1px solid var(--de-line);
        border-radius: var(--ds-radius-sm, 12px);
        box-shadow: var(--de-shadow-sm);
        padding: 20px 20px 22px;
        margin-bottom: 24px;
    }
    .dept-stepper-header { margin-bottom: 20px; }
    .dept-stepper-title {
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--de-ink);
        margin: 0 0 4px;
    }
    .dept-stepper-subtitle {
        font-size: 0.75rem;
        color: var(--de-muted);
        margin: 0;
        font-weight: 500;
    }
    html.dark-mode .dept-stepper-lifecycle {
        --de-surface: #141321;
        --de-line: rgba(255, 255, 255, 0.06);
        --de-line-strong: rgba(255, 255, 255, 0.12);
        --de-ink: #f8f7f5;
        --de-ink-secondary: #cbd5e1;
        --de-muted: #94a3b8;
    }
    .dept-stepper-track {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 4px;
    }
    .dept-step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex: 1 1 0;
        position: relative;
        z-index: 2;
        min-width: 0;
    }
    .dept-step-node {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6875rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .dept-step-item.completed .dept-step-node { background: #10b981; color: #fff; }
    .dept-step-item.current .dept-step-node { background: #3b82f6; color: #fff; }
    .dept-step-item.pending .dept-step-node {
        background: transparent;
        color: var(--de-muted);
        border: 2px solid var(--de-line-strong);
    }
    .dept-step-name {
        font-size: 0.625rem;
        font-weight: 600;
        color: var(--de-ink-secondary);
        text-align: center;
        line-height: 1.2;
        white-space: normal;
        max-width: 72px;
    }
    .dept-step-item.completed .dept-step-name { color: #059669; font-weight: 700; }
    .dept-step-item.current .dept-step-name { color: #2563eb; font-weight: 700; }
    .dept-step-item.pending .dept-step-name { color: var(--de-muted); font-weight: 500; }
    .dept-step-connector {
        flex: 1;
        height: 2px;
        background: var(--de-line-strong);
        margin-top: 14px;
        min-width: 12px;
        position: relative;
        z-index: 1;
    }
    .dept-step-connector.completed { background: #10b981; }
    html.dark-mode .dept-step-item.completed .dept-step-node { background: #34d399; color: #064e3b; }
    html.dark-mode .dept-step-item.current .dept-step-node { background: #60a5fa; color: #0f172a; }
    html.dark-mode .dept-step-item.completed .dept-step-name { color: #34d399; }
    html.dark-mode .dept-step-item.current .dept-step-name { color: #60a5fa; }
    html.dark-mode .dept-step-connector.completed { background: #34d399; }
    html.dark-mode .dept-stepper-title { color: #f8f7f5; }
    html.dark-mode .dept-stepper-subtitle { color: #94a3b8; }
    @media (max-width: 640px) {
        .dept-stepper-track { overflow-x: auto; padding-bottom: 8px; }
        .dept-step-item { min-width: 70px; }
        .dept-step-name { max-width: 70px; }
    }
</style>

<div class="dept-stepper-lifecycle dept-animate">
    <div class="dept-stepper-header">
        <h3 class="dept-stepper-title">Project Lifecycle</h3>
        <p class="dept-stepper-subtitle">Current stage: {{ $project->current_status }}</p>
    </div>
    <div class="dept-stepper-track">
        @foreach ($steps as $index => $step)
            @php
                $isComplete = $activeStep !== null && $index < $activeStep;
                $isCurrent = $activeStep !== null && $index === $activeStep;
                $state = $isComplete ? 'completed' : ($isCurrent ? 'current' : 'pending');
            @endphp
            <div class="dept-step-item {{ $state }}">
                <div class="dept-step-node">
                    @if ($isComplete)
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <span class="dept-step-name">{{ $step }}</span>
            </div>
            @if ($index < count($steps) - 1)
                <div class="dept-step-connector {{ $activeStep !== null && $index < $activeStep ? 'completed' : '' }}"></div>
            @endif
        @endforeach
    </div>
</div>