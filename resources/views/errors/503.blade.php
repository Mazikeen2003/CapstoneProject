<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Under Maintenance | {{ config('app.name', 'Project Tracker System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F7F9FB] font-sans text-slate-900">
    <main class="flex min-h-screen items-center justify-center px-5 py-10">
        <section class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5.36 4.24l-.707-.707M5.05 6.464L5.757 5.757" />
                </svg>
            </div>

            <p class="mt-5 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Maintenance Mode</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">Under Maintenance</h1>
            <p class="mx-auto mt-4 max-w-md text-sm leading-6 text-slate-600">
                {{ config('app.name', 'ProjectTracker') }} is currently undergoing scheduled maintenance. We'll be back online shortly. Thank you for your patience!
            </p>

            <div class="mt-7 text-sm text-slate-500">
                <p>Please check back soon.</p>
            </div>
        </section>
    </main>
</body>
</html>
