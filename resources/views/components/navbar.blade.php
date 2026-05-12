@php
    $userName = session('mock_user.email') ?? 'Guest';
@endphp

<header style="background-color: #F7F9FB; border-color: #E0E7F1;" class="border-b">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex flex-1 items-center gap-4">
            <div class="relative w-full max-w-md">
                <label for="search" class="sr-only">Search</label>
                <input id="search" type="search" placeholder="Search system resources..." class="w-full rounded-full border py-3 pl-4 pr-12 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" style="background-color: #ffffff; border-color: #D1D5DB; color: #0F172A;" />
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="#6B7280">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zm-6 8a6 6 0 1110.89 3.476l4.817 4.817-1.414 1.414-4.817-4.817A6 6 0 012 12z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button class="rounded-2xl p-3 transition" style="background-color: #F0F4F8; color: #0F172A;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <div class="hidden rounded-full px-4 py-2 text-sm font-semibold sm:block" style="color: #0F172A;">
                {{ $userName }}
            </div>
        </div>
    </div>
</header>
