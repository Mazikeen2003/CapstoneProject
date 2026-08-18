<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Expired | {{ config('app.name', 'Project Tracker System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F7F9FB] font-sans text-slate-900">
    <main class="flex min-h-screen items-center justify-center px-5 py-10">
        <section class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5-7.5a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <p class="mt-5 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Session Expired</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">Your Session Expired</h1>
            <p class="mx-auto mt-4 max-w-md text-sm leading-6 text-slate-600">
                Your session has expired for security reasons. Please refresh the page and try again, or log back in.
            </p>

            <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <button onclick="location.reload()" class="inline-flex items-center justify-center rounded-full bg-[#162347] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f1e3d]">
                    Refresh Page
                </button>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-6 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Back to Login
                </a>
            </div>
        </section>
    </main>
</body>
</html>
