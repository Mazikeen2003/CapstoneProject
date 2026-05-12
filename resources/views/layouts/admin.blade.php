<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }} | Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white">
        <div class="h-screen">
            <div class="flex h-full">
                @include('components.sidebar')
                <div class="flex-1 flex flex-col overflow-hidden">
                    @include('components.navbar')
                    <main class="flex-1 overflow-y-auto px-6 py-6">
                        <div class="mx-auto max-w-7xl">
                            @yield('content')
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>