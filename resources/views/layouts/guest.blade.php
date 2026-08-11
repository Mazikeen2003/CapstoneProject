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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50">
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
