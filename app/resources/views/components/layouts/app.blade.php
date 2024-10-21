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
        <link
            rel="shortcut icon"
            type="image/x-icon"
            href="{{ asset('images/logo_erudify.ico') }}"
        >

        @livewireStyles
        @filamentStyles
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>

    <body class="antialiased">
        {{ $slot }}

        @livewireScripts
        @filamentScripts
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
