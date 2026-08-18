<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found | {{ config('app.name', 'Project Tracker System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F7F9FB] font-sans text-slate-900">
    <main class="flex min-h-screen items-center justify-center px-5 py-10">
        <section class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#c9a84c]/20 text-[#c9a84c]">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6m0 0l6-6m-6 6l-6-6m6 6l6 6" />
                </svg>
            </div>

            <p class="mt-5 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Page Not Found</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">404</h1>
            <p class="mx-auto mt-4 max-w-md text-sm leading-6 text-slate-600">
                The page you're looking for doesn't exist or may have been moved. Please check the URL and try again.
            </p>

            <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-[#162347] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f1e3d]">
                        Back to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-[#162347] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0f1e3d]">
                        Back to Login
                    </a>
                @endauth
                <a href="javascript:history.back()" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-6 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Go Back
                </a>
            </div>
        </section>
    </main>
</body>
</html>
