<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Perpustakaan Digital Sekolah') }}</title>

        <link rel="stylesheet" href="{{ asset('css/app-fallback.css') }}">
        @include('partials.vite')
    </head>
    <body class="page-shell">
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                    <div class="surface px-6 py-5">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
