<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }} | Public</title>
        @include('layouts.favicon')

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: #ffffff; color: #111827;">
        <div class="flex min-h-screen bg-white">
            @include('components.sidebar')
            <div class="flex-1 flex flex-col bg-white">
                @include('components.navbar')
                <main class="flex-1 p-6 bg-white">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
