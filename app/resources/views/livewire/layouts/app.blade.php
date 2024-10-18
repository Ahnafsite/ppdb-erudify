<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        <div class="mt-5">
            <div class="bg-white">
                @include('livewire.components.navigation')
                <div class="relative px-6 isolate pt-14 lg:px-8">
                    {{ $slot }}
                </div>
            </div>
        </div>


        @livewire('notifications')

        @filamentScripts
        @vite('resources/js/app.js')
        <script>
            // Ambil elemen-elemen yang dibutuhkan
            const mobileMenuButton = document.querySelector('button[aria-label="Open main menu"]');
            const mobileMenu = document.querySelector('div[role="dialog"]');
            const closeMenuButton = document.querySelector('button[aria-label="Close menu"]');

            // Fungsi untuk membuka menu
            mobileMenuButton.addEventListener('click', function() {
              mobileMenu.classList.remove('hidden'); // Tampilkan menu
            });

            // Fungsi untuk menutup menu
            closeMenuButton.addEventListener('click', function() {
              mobileMenu.classList.add('hidden'); // Sembunyikan menu
            });
        </script>
    </body>
</html>
