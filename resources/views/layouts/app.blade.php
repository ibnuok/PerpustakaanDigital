<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Perpustakaan Digital') }}</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    @include('partials.vite')

    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            margin: 0;
        }

        /* LAYOUT */
        .layout {
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #4f46e5, #6366f1);
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 10px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            margin-bottom: 8px;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
        }

        .sidebar a.active {
            background: white;
            color: var(--primary);
            font-weight: 500;
        }

        /* CONTENT */
        .content {
            flex: 1;
        }

        /* TOPBAR */
        .topbar {
            background: white;
            padding: 15px 25px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search {
            background: #f1f5f9;
            border: none;
            padding: 10px;
            border-radius: 10px;
            width: 250px;
        }

        /* CARD */
        .card-modern {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: 0.2s;
        }

        .card-modern:hover {
            transform: translateY(-3px);
        }

        .stat {
            font-size: 26px;
            font-weight: 600;
        }

        /* BUTTON */
        .btn-modern {
            background: var(--primary);
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
        }
    </style>
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>📚 Perpustakaan</h2>

        <!-- ADMIN -->
        @auth
            @if(auth()->user()->role == 'admin')
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/buku">Data Buku</a>
                <a href="/kategori">Kategori</a>
                <a href="/anggota">Anggota</a>
                <a href="/transaksi">Transaksi</a>
            @else
                <!-- USER -->
                <a href="/user/dashboard">Dashboard</a>
                <a href="/katalog">Katalog</a>
                <a href="/transaksi-saya">Transaksi Saya</a>
            @endif
        @endauth
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- TOPBAR -->
        <div class="topbar">
            <input type="text" class="search" placeholder="Cari...">

            <div>
                {{ Auth::user()->name ?? 'Guest' }}
            </div>
        </div>

        <!-- MAIN -->
        <div style="padding: 25px;">
            {{ $slot }}
        </div>

    </div>

</div>

</body>
</html>