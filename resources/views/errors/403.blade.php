<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Restricted | {{ config('app.name', 'Project Tracker System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F7F9FB] font-sans text-slate-900">
    <main class="flex min-h-screen items-center justify-center px-5 py-10">
        <section class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 4.5h.008v.008H12v-.008Z" />
                </svg>
            </div>

            <p class="mt-5 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Access Restricted</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">Access unavailable</h1>
            <p class="mx-auto mt-4 max-w-md text-sm leading-6 text-slate-600">
                {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
            </p>

            <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-[#162347] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f1e3d]">
                    Back to Dashboard
                </a>
                <a href="javascript:history.back()" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Go Back
                </a>
            </div>
        </section>
    </main>
</body>
</html>
