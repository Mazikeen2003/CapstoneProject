@props([
    'eyebrow',
    'title',
    'description',
    'route',
    'icon' => 'document',
])

<article class="dept-report-card group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-amber-300 hover:shadow-xl">
    <div class="absolute bottom-0 right-0 h-24 w-24 rounded-tl-full bg-slate-50 transition duration-300 group-hover:bg-amber-50"></div>
    <div class="relative flex items-start gap-4">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-900 text-amber-300 shadow-lg shadow-slate-900/15 ring-4 ring-slate-100">
            @if($icon === 'budget')
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-3.314 0-6 1.343-6 3s2.686 3 6 3 6-1.343 6-3-2.686-3-6-3Zm-6 3v4c0 1.657 2.686 3 6 3s6-1.343 6-3v-4M8 5h8m-4 0v3" /></svg>
            @else
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l4 4v14H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm7 0v5h5M8 13h6m-6 4h6" /></svg>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-amber-600">{{ $eyebrow }}</span>
            <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $title }}</h2>
        </div>
    </div>
    <div class="relative mt-5">
        <p class="min-h-[3rem] text-sm leading-6 text-slate-500">{{ $description }}</p>
    </div>
    <div class="relative mt-6 border-t border-slate-100 pt-4">
        <a href="{{ $route }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-900 transition hover:text-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
        Download PDF
        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" /></svg>
        </a>
        <span class="float-right text-xs font-medium text-slate-400">PDF export</span>
    </div>
</article>