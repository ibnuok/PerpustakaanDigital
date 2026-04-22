<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital Sekolah')</title>
    <link rel="stylesheet" href="{{ asset('css/app-fallback.css') }}">
    @include('partials.vite')
</head>
<body class="page-shell">
@php
    $user = auth()->user();
    $isAdmin = $user?->role === 'admin';
    $navItems = $isAdmin
        ? [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
            ['label' => 'Data Buku', 'route' => 'admin.buku.index', 'active' => 'admin.buku.*'],
            ['label' => 'Kategori', 'route' => 'admin.kategori.index', 'active' => 'admin.kategori.*'],
            ['label' => 'Anggota', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
            ['label' => 'Transaksi', 'route' => 'admin.peminjaman.index', 'active' => 'admin.peminjaman.*'],
        ]
        : [
            ['label' => 'Dashboard', 'route' => 'user.dashboard', 'active' => 'user.dashboard'],
            ['label' => 'Katalog', 'route' => 'user.bukus', 'active' => 'user.bukus'],
            ['label' => 'Transaksi Saya', 'route' => 'user.peminjaman.index', 'active' => 'user.peminjaman.*'],
        ];
@endphp

<div class="portal-wrapper">
    <header class="site-topbar">
        <div class="brand">
            <span class="brand-badge">PD</span>
            <div>
                <div class="text-xs font-semibold uppercase" style="color: var(--muted); letter-spacing: 0.22em;">Perpustakaan Sekolah</div>
                <div class="text-lg font-bold">Perpustakaan Digital</div>
            </div>
        </div>

        <nav class="nav-pills">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}" class="nav-pill {{ request()->routeIs($item['active']) ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="nav-pills">
            <a href="{{ route('profile.edit') }}" class="nav-pill">{{ $user->name }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-primary">Keluar</button>
            </form>
        </div>
    </header>

    <main class="portal-content">
        <section class="hero-panel">
            <span class="hero-chip">{{ $isAdmin ? 'Admin Panel' : 'Member Area' }}</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight">@yield('page_heading', 'Dashboard')</h1>
            <p class="mt-3 max-w-3xl leading-8" style="color: rgba(255,255,255,0.88);">
                @yield('page_description', 'Kelola perpustakaan digital sekolah dengan tampilan yang modern, bersih, dan nyaman dipakai.')
            </p>
            @hasSection('page_actions')
                <div class="page-actions mt-5">
                    @yield('page_actions')
                </div>
            @endif
        </section>

        @if (session('success'))
            <div class="mt-6 soft-panel p-5" style="border-color: #d7f5e1; background: var(--success-bg); color: var(--success);">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-6 soft-panel p-5" style="border-color: #ffd7e2; background: var(--danger-bg); color: var(--danger);">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 soft-panel p-5" style="border-color: #ffe8b5; background: var(--warning-bg); color: var(--warning);">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="surface page-block mt-6">
            @yield('content')
        </section>
    </main>

    <footer class="footer-bar">
        Perpustakaan Digital Sekolah - sistem peminjaman buku yang modern, rapi, dan mudah digunakan.
    </footer>
</div>
</body>
</html>
