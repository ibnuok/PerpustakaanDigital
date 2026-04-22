@php
    $viteAssets = $viteAssets ?? ['resources/css/app.css', 'resources/js/app.js'];
@endphp

@if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
    @vite($viteAssets)
@endif
