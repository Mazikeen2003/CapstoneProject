@props(['title', 'value', 'label' => null, 'status' => null, 'statusColor' => 'emerald', 'iconColor' => '#3b82f6'])

<div class="rounded-3xl p-6 shadow-sm" style="background-color: #ffffff; border: 1px solid #E5E7EB; color: #000000;">
    <div class="flex flex-row items-center justify-between gap-4 w-full flex-nowrap">
        <!-- Icon on the left -->
        <div class="rounded-2xl p-3 shrink-0 flex items-center justify-center" style="background-color: {{ $iconColor }}; color: white;">
            @if(trim($slot) !== '')
                {{ $slot }}
            @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            @endif
        </div>
        
        <!-- Content in the middle -->
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium" style="color: #6B7280; letter-spacing: 0.5px;">{{ $title }}</p>
            <p class="mt-3 text-4xl font-bold text-black" style="letter-spacing: -0.5px;">{{ $value }}</p>
            @if($label)
                <p class="text-sm mt-3 font-medium" style="color: #9CA3AF; letter-spacing: 0.3px;">{{ $label }}</p>
            @endif
        </div>
        
        <!-- Status on the right (optional) -->
        @if($status)
            <span class="text-xs uppercase tracking-[0.25em] px-3 py-1 rounded-full text-white font-semibold shrink-0 whitespace-nowrap" style="background-color: {{ $iconColor }};">{{ $status }}</span>
        @endif
    </div>
</div>
