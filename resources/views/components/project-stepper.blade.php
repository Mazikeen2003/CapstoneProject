@php
    $steps = [
        'Proposed',
        'For bidding',
        'Bidding ongoing',
        'Award of contract',
        'Implementation',
    ];

    $stageByStatus = [
        'Proposed' => 0,
        'For bidding' => 1,
        'Bidding ongoing' => 2,
        'Award of contract' => 3,
        'Implementation' => 4,
        'Planning' => 0,
        'Procurement' => 1,
        'Bidding - Success' => 3,
        'On Going' => 4,
        'Completed' => 4,
    ];

    $activeStep = $stageByStatus[$project->current_status] ?? null;
    $isExceptionStatus = ! array_key_exists($project->current_status, $stageByStatus);
@endphp

<div class="bg-white rounded-lg p-6" style="border: 1px solid #B2BEB5;">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-black">Project Lifecycle</h2>
            <p class="text-sm text-gray-500">Current stage: {{ $project->current_status }}</p>
        </div>
        @if ($isExceptionStatus)
            <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                {{ $project->current_status }}
            </span>
        @endif
    </div>

    <div class="mt-6 grid grid-cols-5 gap-1 sm:gap-3">
        @foreach ($steps as $index => $step)
            @php
                $isComplete = $activeStep !== null && $index < $activeStep;
                $isCurrent = $activeStep !== null && $index === $activeStep;
                $circleClass = $isComplete
                    ? 'bg-emerald-600 text-white border-emerald-600'
                    : ($isCurrent ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-400 border-gray-300');
                $labelClass = ($isComplete || $isCurrent) ? 'text-gray-800' : 'text-gray-400';
            @endphp

            <div class="relative text-center">
                @if ($index < count($steps) - 1)
                    <div class="absolute left-1/2 top-4 h-0.5 w-full bg-gray-200" aria-hidden="true">
                        @if ($activeStep !== null && $index < $activeStep)
                            <div class="h-full bg-emerald-600"></div>
                        @endif
                    </div>
                @endif
                <div class="relative z-10 mx-auto flex h-8 w-8 items-center justify-center rounded-full border-2 text-sm font-bold {{ $circleClass }}">
                    @if ($isComplete)&#10003;@else{{ $index + 1 }}@endif
                </div>
                <p class="mt-2 text-[10px] font-semibold leading-tight sm:text-xs {{ $labelClass }}">{{ $step }}</p>
            </div>
        @endforeach
    </div>
</div>