<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Restricted | {{ config('app.name', 'Project Tracker System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-900">
    <main class="flex min-h-screen items-center justify-center px-5 py-10">
        <section class="w-full max-w-md rounded-xl bg-white p-8 text-center shadow-sm">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 4.5h.008v.008H12v-.008Z" />
                </svg>
            </div>

            <h1 class="mt-5 text-2xl font-bold">Access unavailable</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                {{ $exception->getMessage() ?: 'You do not have permission to access this page. Please contact the original Administrator if you need access.' }}
            </p>

            <a href="{{ route('dashboard') }}" class="mt-7 inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                Back to Dashboard
            </a>
        </section>
    </main>
</body>
</html>
