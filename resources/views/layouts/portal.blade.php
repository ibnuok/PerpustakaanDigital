<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital Sekolah')</title>
    @include('partials.vite')
    <style>
        :root {
            --bg: #f3f0ea;
            --panel: #fcfbf8;
            --panel-strong: #ffffff;
            --line: #ddd6cb;
            --line-strong: #c9beaf;
            --text: #1f2933;
            --muted: #6b7280;
            --brand: #1f3a5f;
            --brand-soft: #e8eef5;
            --accent: #7a5c3e;
            --accent-soft: #f3ede4;
            --danger: #b53d2e;
            --danger-soft: #f8e7e4;
            --success: #2f6a4f;
            --success-soft: #e5f0ea;
            --warning: #9a6a16;
            --warning-soft: #f8efd8;
            --shadow: 0 18px 40px rgba(31, 41, 51, 0.08);
            --radius-xl: 28px;
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: Georgia, 'Times New Roman', serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        a { color: inherit; }
        button, input, select, textarea { font: inherit; }
        img, svg { max-width: 100%; height: auto; }

        .dashboard-wrapper {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            padding: 28px 20px;
            background: #f7f3ec;
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .sidebar-brand {
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            background: var(--panel-strong);
            box-shadow: var(--shadow);
        }

        .sidebar-brand small {
            display: block;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .12em;
            font-size: 11px;
            margin-bottom: 6px;
        }

        .sidebar-brand strong {
            font-size: 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 15px;
            border-radius: 14px;
            text-decoration: none;
            color: #324152;
            border: 1px solid transparent;
            transition: .2s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--panel-strong);
            border-color: var(--line);
            box-shadow: 0 10px 24px rgba(31, 41, 51, 0.06);
            color: var(--brand);
        }

        .sidebar-foot {
            margin-top: auto;
            padding: 16px;
            border-radius: var(--radius-lg);
            background: #efe7da;
            border: 1px solid var(--line);
            color: #5a4a36;
            font-size: 14px;
            line-height: 1.6;
        }

        .main-content {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 22px 28px;
            background: rgba(243, 240, 234, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(201, 190, 175, 0.7);
        }

        .menu-toggle {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: var(--panel-strong);
            cursor: pointer;
        }

        .topbar-meta {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 999px;
            background: var(--panel-strong);
            border: 1px solid var(--line);
            min-width: 0;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--brand);
            color: #fff;
            font-weight: 700;
        }

        .logout-btn {
            border: none;
            background: transparent;
            color: var(--danger);
            cursor: pointer;
            font-weight: 700;
        }

        .page-frame {
            padding: 28px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .page-header h1, .page-header h2 {
            margin: 0;
            font-size: clamp(28px, 4vw, 40px);
            line-height: 1.1;
        }

        .page-header p {
            margin: 10px 0 0;
            color: var(--muted);
            max-width: 760px;
            line-height: 1.7;
        }

        .page-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .page-actions > * {
            flex-shrink: 0;
        }

        .surface,
        .card,
        .soft-panel,
        .table-wrap,
        .guide-card,
        .form-panel,
        .table-shell,
        .summary-card,
        .modal-card {
            background: var(--panel-strong);
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
        }

        .card,
        .soft-panel,
        .guide-card,
        .form-panel,
        .summary-card,
        .modal-card {
            padding: 22px;
        }

        .dashboard-grid,
        .stats-grid,
        .metric-grid,
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: var(--panel-strong);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .stat-label {
            color: var(--muted);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .stat-value {
            margin-top: 8px;
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 700;
            line-height: 1;
        }

        .btn-primary,
        .btn-secondary,
        .btn-danger,
        .action-btn,
        .action-btn-primary,
        .action-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            border-radius: 999px;
            padding: 11px 18px;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid transparent;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-primary,
        .action-btn-primary,
        .action-btn.primary,
        .action-btn.success,
        .btn-approve {
            background: var(--brand);
            color: #fff;
        }

        .btn-secondary,
        .action-btn-secondary,
        .action-btn.info,
        .action-btn.warning {
            background: var(--panel-strong);
            color: var(--brand);
            border-color: var(--line);
        }

        .btn-danger,
        .action-btn.danger,
        .btn-reject {
            background: var(--danger);
            color: #fff;
        }

        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-danger:hover,
        .action-btn:hover,
        .btn-approve:hover,
        .btn-reject:hover,
        .filter-btn:hover {
            transform: translateY(-1px);
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
        }

        .field-input,
        .field-select,
        .field-textarea,
        textarea,
        select,
        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            border: 1px solid var(--line-strong);
            background: #fff;
            color: var(--text);
            border-radius: 16px;
            padding: 12px 14px;
            outline: none;
        }

        .field-input:focus,
        .field-select:focus,
        .field-textarea:focus,
        textarea:focus,
        select:focus,
        input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(31, 58, 95, 0.08);
        }

        .form-shell {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, .65fr);
            gap: 20px;
        }

        .form-grid,
        .filter-grid,
        .info-grid,
        .detail-grid,
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .filter-panel {
            padding: 16px;
            background: rgba(255,255,255,.7);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            margin-bottom: 20px;
        }

        .action-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 20px;
        }

        .table-wrap {
            overflow: hidden;
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        .table-wrap table,
        .table,
        .elegant-table table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        .table-wrap th,
        .table-wrap td,
        .table th,
        .table td,
        .elegant-table th,
        .elegant-table td {
            padding: 16px 18px;
            text-align: left;
            border-bottom: 1px solid #efe8dd;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        .table-wrap thead,
        .table thead,
        .elegant-table thead {
            background: #f7f2ea;
        }

        .badge,
        .badge-soft,
        .status-badge,
        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .badge-soft,
        .hero-chip {
            background: var(--accent-soft);
            color: var(--accent);
            border-color: #e4d7c8;
        }

        .status-belum,
        .status-rejected,
        .bg-danger {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .status-pending,
        .bg-warning,
        .status-pending-approval {
            background: var(--warning-soft);
            color: var(--warning);
        }

        .status-lunas,
        .status-approved,
        .badge-returned,
        .bg-success {
            background: var(--success-soft);
            color: var(--success);
        }

        .badge-approved {
            background: #e3ecf7;
            color: var(--brand);
        }

        .empty-state {
            padding: 40px 24px;
            text-align: center;
            color: var(--muted);
        }

        .stack-list {
            display: grid;
            gap: 12px;
        }

        .stack-item,
        .info-card,
        .detail-row {
            padding: 14px 16px;
            border-radius: var(--radius-md);
            background: #f8f4ee;
            border: 1px solid #ece3d7;
        }

        .detail-label,
        .detail-subvalue,
        .info-item-label,
        small {
            color: var(--muted);
        }

        .cover-hero {
            max-width: 100%;
            border-radius: 18px;
            border: 1px solid var(--line);
        }

        .flash-stack {
            display: grid;
            gap: 10px;
            margin-bottom: 16px;
        }

        .flash {
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: var(--panel-strong);
        }

        .flash.success { background: var(--success-soft); color: var(--success); border-color: #cfe2d8; }
        .flash.error { background: var(--danger-soft); color: var(--danger); border-color: #eed2cc; }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(31, 41, 51, 0.32);
            z-index: 40;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(31, 41, 51, 0.42);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 60;
        }

        .modal-backdrop.show,
        .overlay.show {
            display: flex;
        }

        .modal-card {
            width: min(680px, 100%);
            max-height: calc(100vh - 40px);
            overflow: auto;
        }

        @media (max-width: 1100px) {
            .dashboard-wrapper {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 50;
                transform: translateX(-100%);
                transition: transform .2s ease;
                width: min(300px, 88vw);
            }

            .sidebar.show { transform: translateX(0); }
            .menu-toggle { display: inline-flex; align-items: center; justify-content: center; }
            .page-frame, .topbar { padding-left: 18px; padding-right: 18px; }
            .topbar {
                flex-wrap: wrap;
                align-items: flex-start;
            }
            .topbar-meta {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 820px) {
            .page-header,
            .form-shell,
            .main-grid,
            .grid-2,
            .detail-grid,
            .form-grid,
            .filter-grid,
            .info-grid {
                grid-template-columns: 1fr;
                display: grid;
            }

            .page-header {
                align-items: stretch;
            }

            .page-actions {
                justify-content: flex-start;
            }

            .page-actions > * {
                width: 100%;
            }

            .table-wrap,
            .elegant-table {
                overflow-x: auto;
            }

            .table-wrap table,
            .table,
            .elegant-table table {
                min-width: 640px;
            }

            .action-row > * {
                width: 100%;
            }

            .user-badge {
                flex: 1 1 auto;
            }

            .logout-btn {
                width: 100%;
                padding: 12px 14px;
                border: 1px solid var(--line);
                border-radius: 999px;
                background: var(--panel-strong);
            }

            .modal-backdrop {
                align-items: flex-end;
                padding: 12px;
            }

            .modal-card {
                width: 100%;
                max-height: min(88vh, 100%);
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }

            .responsive-table,
            .responsive-table thead,
            .responsive-table tbody,
            .responsive-table tr,
            .responsive-table th,
            .responsive-table td {
                display: block;
                width: 100%;
            }

            .responsive-table {
                min-width: 100% !important;
            }

            .responsive-table thead {
                display: none;
            }

            .responsive-table tbody {
                display: grid;
                gap: 14px;
                padding: 14px;
            }

            .responsive-table tr {
                border: 1px solid #efe8dd;
                border-radius: var(--radius-lg);
                padding: 16px;
                background: var(--panel-strong);
                box-shadow: 0 10px 22px rgba(31, 41, 51, 0.05);
            }

            .responsive-table td {
                min-width: 0;
                padding: 10px 0;
                border-bottom: 1px solid #f0ebe4;
            }

            .responsive-table td:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .responsive-table td::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 6px;
                color: var(--muted);
                font-size: 12px;
                font-weight: 700;
                letter-spacing: .04em;
                text-transform: uppercase;
            }

            .responsive-table td[data-label="Aksi"] .action-row,
            .responsive-table td[data-label="Actions"] .action-row {
                justify-content: flex-start !important;
            }

            .responsive-table td[data-label="Aksi"] .btn-primary,
            .responsive-table td[data-label="Aksi"] .btn-secondary,
            .responsive-table td[data-label="Aksi"] .btn-danger,
            .responsive-table td[data-label="Aksi"] .action-btn,
            .responsive-table td[data-label="Actions"] .btn-primary,
            .responsive-table td[data-label="Actions"] .btn-secondary,
            .responsive-table td[data-label="Actions"] .btn-danger,
            .responsive-table td[data-label="Actions"] .action-btn {
                width: 100%;
            }

            .responsive-table td[data-label="Aksi"] form,
            .responsive-table td[data-label="Actions"] form {
                width: 100%;
            }
        }

        @media (max-width: 560px) {
            .page-frame,
            .topbar {
                padding-left: 14px;
                padding-right: 14px;
            }

            .page-frame {
                padding-top: 20px;
                padding-bottom: 20px;
            }

            .card,
            .soft-panel,
            .guide-card,
            .form-panel,
            .summary-card,
            .modal-card,
            .stat-card {
                padding: 16px;
                border-radius: 20px;
            }

            .page-header h1,
            .page-header h2 {
                font-size: clamp(24px, 7vw, 32px);
            }
        }
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $isAdmin = $user?->role === 'admin';
    $navItems = $isAdmin
        ? [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
            ['label' => 'Buku', 'route' => 'admin.buku.index', 'active' => 'admin.buku.*'],
            ['label' => 'Kategori', 'route' => 'admin.kategori.index', 'active' => 'admin.kategori.*'],
            ['label' => 'Anggota', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
            ['label' => 'Peminjaman', 'route' => 'admin.peminjaman.index', 'active' => 'admin.peminjaman.*'],
            ['label' => 'Denda', 'route' => 'admin.denda.index', 'active' => 'admin.denda.*'],
        ]
        : [
            ['label' => 'Dashboard', 'route' => 'user.dashboard', 'active' => 'user.dashboard'],
            ['label' => 'Katalog', 'route' => 'user.bukus', 'active' => 'user.bukus'],
            ['label' => 'Peminjaman', 'route' => 'user.peminjaman.index', 'active' => 'user.peminjaman.*'],
            ['label' => 'Denda', 'route' => 'user.denda.index', 'active' => 'user.denda.*'],
        ];
@endphp

<div class="dashboard-wrapper">
    <div id="overlay" class="overlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <small>{{ $isAdmin ? 'Panel Admin' : 'Portal Peminjam' }}</small>
            <strong>Perpustakaan Digital</strong>
        </div>

        <ul class="sidebar-menu">
            @foreach ($navItems as $item)
                <li>
                    <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['active']) ? 'active' : '' }}">
                        <span>{{ $item['label'] }}</span>
                        <span>&rsaquo;</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="sidebar-foot">
            Gunakan panel ini untuk mengelola koleksi, transaksi, dan pembayaran denda tanpa mengubah alur backend yang sudah berjalan.
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <button class="menu-toggle" id="menuToggle" type="button">Menu</button>
                <div>
                    <div style="font-size:12px; text-transform:uppercase; letter-spacing:.14em; color:var(--muted);">Sistem Perpustakaan</div>
                    <div style="font-weight:700;">{{ $isAdmin ? 'Administrasi Koleksi dan Denda' : 'Dashboard Peminjaman dan Denda' }}</div>
                </div>
            </div>
            <div class="topbar-meta">
                <div class="user-badge">
                    <span class="user-avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
                    <div>
                        <div style="font-weight:700;">{{ $user->name ?? 'Pengguna' }}</div>
                        <div style="font-size:12px; color:var(--muted); text-transform:capitalize;">{{ $user->role ?? '-' }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        <div class="page-frame">
            <header class="page-header">
                <div>
                    <h1>@yield('page_heading')</h1>
                    <p>@yield('page_description')</p>
                </div>
                @hasSection('page_actions')
                    <div class="page-actions">@yield('page_actions')</div>
                @endif
            </header>

            @if (session('success') || session('error'))
                <div class="flash-stack">
                    @if (session('success'))
                        <div class="flash success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="flash error">{{ session('error') }}</div>
                    @endif
                </div>
            @endif

            @if ($errors->any())
                <div class="flash error" style="margin-bottom:16px;">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggle = document.getElementById('menuToggle');

        if (toggle) {
            toggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    });
</script>
</body>
</html>
