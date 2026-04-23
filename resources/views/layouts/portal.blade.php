            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <title>@yield('title', 'Perpustakaan Digital Sekolah')</title>
                <link rel="stylesheet" href="{{ asset('css/app-fallback.css') }}">
                <style>

                    

                    :root {
                        --primary: #6366f1;
                        --primary-dark: #4f46e5;
                        --secondary: #8b5cf6;
                        --danger: #ef4444;
                        --success: #10b981;
                        --warning: #f59e0b;
                        --text: #1f2937;
                        --text-light: #6b7280;
                        --bg-light: #f9fafb;
                        --border-light: #e5e7eb;
                    }

                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }

                    body {
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
                        background-color: var(--bg-light);
                        color: var(--text);
                    }

                    .dashboard-wrapper {
                        display: flex;
                        min-height: 100vh;
                    }

                    .sidebar {
                width: 280px;
                background: linear-gradient(180deg, #2f49c6, #3b5bdb, #5c7cfa);
                color: white;
                padding: 2rem 0;
                position: fixed;
                height: 100vh;
                overflow-y: auto;
                box-shadow: 0 10px 30px rgba(59, 91, 219, 0.2);
            }

                    .sidebar::-webkit-scrollbar {
                        width: 6px;
                    }

                    .sidebar::-webkit-scrollbar-track {
                        background: rgba(255, 255, 255, 0.1);
                    }

                    .sidebar::-webkit-scrollbar-thumb {
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 3px;
                    }

                    .sidebar-header {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 0 1.5rem 2rem;
                        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                        margin-bottom: 2rem;
                    }

                    .sidebar-logo {
                        width: 40px;
                        height: 40px;
                        background: rgba(255, 255, 255, 0.2);
                        border-radius: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: bold;
                        font-size: 1.2rem;
                    }

                    .sidebar-title {
                        flex: 1;
                    }

                    .sidebar-title h3 {
                        font-size: 1rem;
                        font-weight: 600;
                        margin: 0;
                    }

                    .sidebar-title p {
                        font-size: 0.75rem;
                        opacity: 0.8;
                        margin: 0;
                    }

                    .sidebar-menu {
                        list-style: none;
                    }

                    .sidebar-menu li {
                        margin: 0;
                    }

                    .sidebar-menu a {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 0.75rem 1.5rem;
                        color: rgba(255, 255, 255, 0.8);
                        text-decoration: none;
                        transition: all 0.3s ease;
                        margin: 0 1rem;
                        border-radius: 8px;
                        font-size: 0.95rem;
                    }

                .sidebar-menu a.active {
                background: white;
                color: #2f49c6;
                font-weight: 600;
            }

            .sidebar-menu a:hover {
                background: rgba(255,255,255,0.15);
            }

                    .main-content {
                        flex: 1;
                        margin-left: 280px;
                        display: flex;
                        flex-direction: column;
                    }

                    .topbar {
                        background: white;
                        border-bottom: 1px solid var(--border-light);
                        padding: 1.5rem 2rem;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        gap: 2rem;
                    }

                    .topbar-left {
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                        flex: 1;
                    }

                    .topbar-search {
                        flex: 1;
                        max-width: 400px;
                    }

                    .topbar-search input {
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border: 1px solid var(--border-light);
                        border-radius: 8px;
                        font-size: 0.9rem;
                        transition: all 0.3s ease;
                    }

                    .topbar-search input:focus {
                        outline: none;
                        border-color: var(--primary);
                        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                    }

                    .topbar-right {
                        display: flex;
                        align-items: center;
                        gap: 1.5rem;
                    }

                    .user-menu {
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                    }

                    .user-avatar {
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, var(--primary), var(--secondary));
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-weight: bold;
                        cursor: pointer;
                    }

                    .content {
                        flex: 1;
                        padding: 2rem;
                        overflow-y: auto;
                    }

                    .page-header {
                        margin-bottom: 2rem;
                    }

                    .page-header h1 {
                        font-size: 2rem;
                        font-weight: 700;
                        margin-bottom: 0.5rem;
                        color: var(--text);
                    }

                    .page-header p {
                        color: var(--text-light);
                        margin-bottom: 1rem;
                    }

                    .page-actions {
                        display: flex;
                        gap: 1rem;
                        margin-top: 1.5rem;
                    }

                    .action-btn {
                        padding: 0.75rem 1.5rem;
                        border-radius: 8px;
                        border: none;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        font-size: 0.95rem;
                    }

                    .action-btn-primary {
                        background: var(--primary);
                        color: white;
                    }

                    .action-btn-primary:hover {
                        background: var(--primary-dark);
                    }

                    .action-btn-secondary {
                        background: white;
                        color: var(--primary);
                        border: 2px solid var(--primary);
                    }

                    .action-btn-secondary:hover {
                        background: var(--bg-light);
                    }

                    /* Input and Select Styles */
                    .field-input,
                    .field-select {
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border: 1px solid var(--border-light);
                        border-radius: 8px;
                        font-size: 0.95rem;
                        color: var(--text);
                        transition: all 0.3s ease;
                        font-family: inherit;
                    }

                    .field-input::placeholder {
                        color: var(--text-light);
                    }

                    .field-input:focus,
                    .field-select:focus {
                        outline: none;
                        border-color: var(--primary);
                        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                    }

                    .field-label {
                        display: block;
                        margin-bottom: 0.5rem;
                        font-weight: 600;
                        color: var(--text);
                        font-size: 0.95rem;
                    }

                    .btn-primary,
                    .btn-secondary,
                    .btn-danger {
                        padding: 0.75rem 1.5rem;
                        border-radius: 8px;
                        border: none;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        font-size: 0.95rem;
                    }

                    .btn-primary {
                        background: var(--primary);
                        color: white;
                    }

                    .btn-primary:hover {
                        background: var(--primary-dark);
                        transform: translateY(-2px);
                    }

                    .btn-secondary {
                        background: white;
                        color: var(--primary);
                        border: 2px solid var(--primary);
                    }

                    .btn-secondary:hover {
                        background: var(--bg-light);
                    }

                    .btn-danger {
                        background: var(--danger);
                        color: white;
                    }

                    .btn-danger:hover {
                        background: #dc2626;
                        transform: translateY(-2px);
                    }

                    /* Form Styles */
                    .form-shell {
                        display: flex;
                        flex-direction: column;
                        gap: 2rem;
                    }

                    .form-panel {
                        background: white;
                        border-radius: 12px;
                        border: 1px solid var(--border-light);
                        padding: 2rem;
                    }

                    .form-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                        gap: 1.5rem;
                    }

                    .form-group {
                        display: flex;
                        flex-direction: column;
                    }

                    .page-intro {
                        margin-bottom: 2rem;
                    }

                    .page-intro .badge-soft {
                        display: inline-block;
                        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
                        padding: 0.5rem 1rem;
                        border-radius: 6px;
                        color: var(--primary);
                        font-weight: 600;
                        font-size: 0.85rem;
                    }

                    .page-intro p {
                        margin-top: 1rem;
                    }

                    /* Success/Error Messages */
                    .success-message,
                    .error-message,
                    .warning-message {
                        padding: 1rem 1.5rem;
                        border-radius: 8px;
                        margin-bottom: 1.5rem;
                        border-left: 4px solid;
                    }

                    .success-message {
                        background: #f0fdf4;
                        border-color: var(--success);
                        color: #166534;
                    }

                    .error-message {
                        background: #fef2f2;
                        border-color: var(--danger);
                        color: #991b1b;
                    }

                    .warning-message {
                        background: #fffbeb;
                        border-color: var(--warning);
                        color: #92400e;
                    }

                    ul.errors {
                        list-style: none;
                        padding-left: 0;
                    }

                    ul.errors li {
                        margin-bottom: 0.5rem;
                    }

                    ul.errors li:last-child {
                        margin-bottom: 0;
                    }

                    .dashboard-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                        gap: 1.5rem;
                        margin-bottom: 2rem;
                    }

                    .stat-card {
                        background: white;
                        border-radius: 12px;
                        padding: 1.5rem;
                        border: 1px solid var(--border-light);
                        transition: all 0.3s ease;
                    }

                    .stat-card:hover {
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                        transform: translateY(-2px);
                    }

                    .stat-label {
                        font-size: 0.85rem;
                        color: var(--text-light);
                        margin-bottom: 0.5rem;
                    }

                    .stat-value {
                        font-size: 2rem;
                        font-weight: 700;
                        color: var(--text);
                    }

                    .stat-change {
                        font-size: 0.8rem;
                        margin-top: 0.5rem;
                        color: var(--success);
                    }

                    .card {
                        background: white;
                        border-radius: 12px;
                        border: 1px solid var(--border-light);
                        overflow: hidden;
                        transition: all 0.3s ease;
                    }

                    .card:hover {
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                    }

                    .card-header {
                        padding: 1.5rem;
                        border-bottom: 1px solid var(--border-light);
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }

                    .card-header h2 {
                        font-size: 1.1rem;
                        font-weight: 600;
                        margin: 0;
                    }

                    .card-body {
                        padding: 1.5rem;
                    }

                    .table {
                        width: 100%;
                        border-collapse: collapse;
                        font-size: 0.95rem;
                    }

                    .table thead {
                        background: var(--bg-light);
                    }

                    .table th {
                        padding: 1rem 1.5rem;
                        text-align: left;
                        font-weight: 600;
                        color: var(--text);
                        border-bottom: 1px solid var(--border-light);
                    }

                    .table td {
                        padding: 1rem 1.5rem;
                        border-bottom: 1px solid var(--border-light);
                    }

                    .table tbody tr:hover {
                        background: var(--bg-light);
                    }

                    .badge {
                        display: inline-block;
                        padding: 0.4rem 0.8rem;
                        border-radius: 6px;
                        font-size: 0.8rem;
                        font-weight: 600;
                    }

                    .badge-pending {
                        background: #fef3c7;
                        color: #92400e;
                    }

                    .badge-approved {
                        background: #dbeafe;
                        color: #1e40af;
                    }

                    .badge-returned {
                        background: #d1fae5;
                        color: #065f46;
                    }

                    .alert {
                        padding: 1rem 1.5rem;
                        border-radius: 8px;
                        margin-bottom: 1.5rem;
                        border-left: 4px solid;
                    }

                    .alert-success {
                        background: #f0fdf4;
                        border-color: var(--success);
                        color: #166534;
                    }

                    .alert-error {
                        background: #fef2f2;
                        border-color: var(--danger);
                        color: #991b1b;
                    }

                    .alert-warning {
                        background: #fffbeb;
                        border-color: var(--warning);
                        color: #92400e;
                    }

                    @media (max-width: 768px) {
                        .sidebar {
                            width: 100%;
                            height: auto;
                            position: relative;
                            padding: 1rem 0;
                        }

                        .main-content {
                            margin-left: 0;
                        }

                        .dashboard-wrapper {
                            flex-direction: column;
                        }

                        .sidebar-menu a {
                            display: inline-block;
                            margin: 0 0.5rem;
                        }

                        .topbar {
                            flex-direction: column;
                        }

                        .topbar-search {
                            max-width: 100%;
                        }

                        .dashboard-grid {
                            grid-template-columns: 1fr;
                        }
                    }

                    /* Filter Panel Styles */
                    .filter-panel {
                        background: white;
                        border-radius: 12px;
                        padding: 1.5rem;
                        border: 1px solid var(--border-light);
                        margin-bottom: 1.5rem;
                    }

                    .filter-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                        gap: 1rem;
                    }

                    /* Mini Cover Image */
                    .mini-cover {
                        width: 50px;
                        height: 75px;
                        object-fit: cover;
                        border-radius: 6px;
                        background: var(--bg-light);
                    }

                    /* Pagination Styles */
                    .pagination {
                        display: flex;
                        gap: 0.5rem;
                        margin-top: 2rem;
                        justify-content: center;
                    }

                    .pagination a, .pagination span {
                        padding: 0.5rem 0.75rem;
                        border-radius: 6px;
                        border: 1px solid var(--border-light);
                        background: white;
                        text-decoration: none;
                        color: var(--text);
                        transition: all 0.3s ease;
                    }

                    .pagination a:hover {
                        background: var(--primary);
                        color: white;
                        border-color: var(--primary);
                    }

                    .pagination .active span {
                        background: var(--primary);
                        color: white;
                        border-color: var(--primary);
                    }

                    .pagination .disabled span {
                        opacity: 0.5;
                        cursor: not-allowed;
                    }
                </style>
                @include('partials.vite')
            </head>
            <body>
            @php
                $user = auth()->user();
                $isAdmin = $user?->role === 'admin';
                $navItems = $isAdmin
                    ? [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => '📊', 'active' => 'admin.dashboard'],
                        ['label' => 'Data Buku', 'route' => 'admin.buku.index', 'icon' => '📚', 'active' => 'admin.buku.*'],
                        ['label' => 'Kategori', 'route' => 'admin.kategori.index', 'icon' => '🏷️', 'active' => 'admin.kategori.*'],
                        ['label' => 'Anggota', 'route' => 'admin.users.index', 'icon' => '👥', 'active' => 'admin.users.*'],
                        ['label' => 'Transaksi', 'route' => 'admin.peminjaman.index', 'icon' => '📋', 'active' => 'admin.peminjaman.*'],
                    ]
                    : [
                        ['label' => 'Dashboard', 'route' => 'user.dashboard', 'icon' => '📊', 'active' => 'user.dashboard'],
                        ['label' => 'Katalog', 'route' => 'user.bukus', 'icon' => '🔍', 'active' => 'user.bukus'],
                        ['label' => 'Transaksi Saya', 'route' => 'user.peminjaman.index', 'icon' => '📋', 'active' => 'user.peminjaman.*'],
                    ];
            @endphp

            <div class="dashboard-wrapper">
                <!-- Sidebar -->
                <aside class="sidebar">
                    <div class="sidebar-header">
                        <div class="sidebar-logo">PD</div>
                        <div class="sidebar-title">
                            <h3>Perpustakaan</h3>
                            <p>Digital Sekolah</p>
                        </div>
                    </div>
                    <ul class="sidebar-menu">
                        @foreach ($navItems as $item)
                            <li>
                                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['active']) ? 'active' : '' }}">
                                    <span>{{ $item['icon'] }}</span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </aside>

                <!-- Main Content -->
                <div class="main-content">
                    <!-- Topbar -->
                    <div class="topbar">
                        <div class="topbar-left">
                            <div class="topbar-search">
                                <input type="text" placeholder="Cari sesuatu...">
                            </div>
                        </div>
                        <div class="topbar-right">
                            <div class="user-menu">
                                <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                                <div>
                                    <div style="font-weight: 600;">{{ $user->name }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-light);">{{ $isAdmin ? 'Admin' : 'Member' }}</div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: var(--text-light); cursor: pointer; font-weight: 600;">Keluar</button>
                            </form>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="content">
                        <div class="page-header">
                            <h1>@yield('page_heading', 'Dashboard')</h1>
                            <p>@yield('page_description', 'Selamat datang di sistem perpustakaan digital sekolah.')</p>
                            @hasSection('page_actions')
                                <div class="page-actions">
                                    @yield('page_actions')
                                </div>
                            @endif
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-error">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-warning">
                                <ul style="margin: 0; padding-left: 1rem;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
            </body>
            </html>
