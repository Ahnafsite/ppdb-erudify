<!DOCTYPE html>
<html lang="id">
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
        <link
            rel="shortcut icon"
            type="image/x-icon"
            href="{{ asset('images/logo_erudify.ico') }}"
        >
        <!-- link stylesheet -->
        @livewireStyles
        @filamentStyles
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/icofont.min.css') }}" >
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}" >
    </head>
    <body class="relative font-inter font-normal text-base leading-[1.8] bg-bodyBg dark:bg-bodyBg-dark">
        {{-- preloader here --}}
        @include('livewire.components.header')
        <main>
            {{ $slot }}
        </main>
        @include('livewire.components.footer')
        @livewireScripts
        @filamentScripts
        @livewire('notifications')
        @vite('resources/js/app.js')
        @include('livewire.components.scripts')
    </body>

</html>
