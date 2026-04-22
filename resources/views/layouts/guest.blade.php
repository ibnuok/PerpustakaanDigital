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
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <div>
                <a href="/">
                    <div class="flex h-20 w-20 items-center justify-center rounded-[2rem] bg-slate-900 text-2xl font-bold text-white">PD</div>
                </a>
            </div>

            <div class="surface mt-6 w-full sm:max-w-md px-6 py-6">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
