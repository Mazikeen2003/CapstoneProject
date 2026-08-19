<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cabuyao Project Tracker') }}</title>
        @include('layouts.favicon')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Public+Sans:wght@400;600;700&display=swap">

        <style>
            .guest-glass-nav {
                backdrop-filter: blur(16px);
                background-color: rgba(248, 249, 255, 0.8);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50">
        <header class="guest-glass-nav sticky top-0 z-50 w-full border-b border-slate-200/50">
            <nav class="relative mx-auto flex w-full items-center justify-between px-4 py-4 sm:px-8 lg:px-12">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="rounded-lg bg-slate-900 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M3 21h18v-2H3v2Zm2-4h2V9H5v8Zm6 0h2V5h-2v12Zm6 0h2V2h-2v15Z" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-bold tracking-tight text-slate-900 sm:text-xl" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                        <span class="text-[9px] uppercase tracking-widest text-slate-500 opacity-70 sm:text-[10px]" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                    </div>
                </div>

                <div class="absolute left-1/2 hidden -translate-x-1/2 items-center gap-6 text-xs uppercase tracking-widest md:flex" style="font-family:'Public Sans',sans-serif;">
                    <a href="{{ url('/') }}" class="py-2 font-semibold text-slate-500 transition-colors hover:text-emerald-700">Home</a>
                    <a href="{{ route('public.map') }}" class="py-2 font-semibold text-slate-500 transition-colors hover:text-emerald-700">Public Map</a>
                    <a href="{{ route('public.analytics') }}" class="py-2 font-semibold text-slate-500 transition-colors hover:text-emerald-700">Analytics</a>
                </div>

                <a href="{{ route('login') }}" class="shrink-0 rounded-md bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 sm:px-5">
                    Login
                </a>
            </nav>
            <div class="border-t border-slate-200 bg-white md:hidden">
                <div class="flex flex-wrap items-center justify-center gap-3 px-4 py-3 text-xs uppercase tracking-widest text-slate-600">
                    <a href="{{ url('/') }}" class="transition-colors hover:text-emerald-700">Home</a>
                    <a href="{{ route('public.map') }}" class="transition-colors hover:text-emerald-700">Public Map</a>
                    <a href="{{ route('public.analytics') }}" class="transition-colors hover:text-emerald-700">Analytics</a>
                </div>
            </div>
        </header>

        {{ $slot }}

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('form[method="POST"]').forEach(function (form) {
                    form.addEventListener('submit', function () {
                        var submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
                        if (!submitButton) {
                            return;
                        }

                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-70', 'cursor-not-allowed');

                        if (submitButton.tagName.toLowerCase() === 'button') {
                            if (!submitButton.querySelector('.loading-spinner')) {
                                var spinner = document.createElement('span');
                                spinner.className = 'loading-spinner inline-block h-4 w-4 animate-spin rounded-full border-2 border-white border-r-transparent mr-2';
                                submitButton.insertBefore(spinner, submitButton.firstChild);
                            }

                            var label = submitButton.querySelector('.loading-label');
                            if (!label) {
                                label = document.createElement('span');
                                label.className = 'loading-label';
                                submitButton.appendChild(label);
                            }
                            label.textContent = 'Loading...';
                        } else if (submitButton.tagName.toLowerCase() === 'input') {
                            submitButton.value = 'Loading...';
                        }
                    });
                });
            });
        </script>
    </body>
</html>
