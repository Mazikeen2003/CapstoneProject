<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Cabuyao Project Tracker') }} | Department</title>
        @include('layouts.favicon')

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: #F7F9FB; color: #0F172A;">
        <div class="flex flex-col xl:flex-row min-h-screen">
            @include('components.sidebar')
            <div class="flex-1 flex flex-col xl:overflow-hidden">
                @include('components.navbar')
                <main class="flex-1 p-6 overflow-y-auto">
                    @if (session('error'))
                        <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 rounded-md border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
                @yield('modals')
            </div>
        </div>
    </body>
</html>
