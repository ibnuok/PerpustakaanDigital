<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/app-fallback.css') }}">
    <style>
        /* Book Gallery Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bookHover {
            0% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-10px) scale(1.02);
            }
            100% {
                transform: translateY(0) scale(1);
            }
        }

        @keyframes bookSpotlight {
            0% {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
            50% {
                box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
            }
            100% {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .book-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }

        .book-card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .book-card:nth-child(1) { animation-delay: 0.1s; }
        .book-card:nth-child(2) { animation-delay: 0.2s; }
        .book-card:nth-child(3) { animation-delay: 0.3s; }
        .book-card:nth-child(4) { animation-delay: 0.4s; }
        .book-card:nth-child(5) { animation-delay: 0.5s; }
        .book-card:nth-child(6) { animation-delay: 0.6s; }

        .book-cover {
            position: relative;
            width: 100%;
            aspect-ratio: 9/12;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .book-card:hover .book-cover {
            animation: bookHover 0.6s ease-out;
            animation: bookSpotlight 2s ease-in-out infinite;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 10;
        }

        .book-info {
            margin-top: 16px;
        }

        .book-title {
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.4;
            color: var(--text);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-author {
            margin-top: 8px;
            font-size: 0.875rem;
            color: var(--muted);
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-meta {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
        }

        .book-year {
            color: var(--muted);
        }

        .book-stok {
            color: var(--accent);
            font-weight: 600;
        }

        .books-section {
            margin-top: 40px;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: var(--text);
            margin-bottom: 12px;
        }

        .section-subtitle {
            text-align: center;
            color: var(--muted);
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .book-gallery {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 16px;
            }

            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
    @include('partials.vite')
</head>
<body class="page-shell">
    <div class="site-shell">
        <header class="site-topbar">
            <div class="brand">
                <span class="brand-badge">PD</span>
                <div>
                    <div class="text-xs font-semibold uppercase" style="color: var(--muted); letter-spacing: 0.22em;">Perpustakaan Sekolah</div>
                    <div class="text-lg font-bold">Perpustakaan Digital</div>
                </div>
            </div>
            <nav class="nav-pills">
                <a href="#koleksi" class="nav-pill">Koleksi Buku</a>
                <a href="#layanan" class="nav-pill">Layanan</a>
                <a href="#manfaat" class="nav-pill">Manfaat</a>
            </nav>
            <div class="nav-pills">
                <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
            </div>
        </header>

        <section class="hero-panel mt-6">
            <span class="hero-chip">Website Perpustakaan Digital</span>
            <h1 class="mt-4 text-5xl font-bold tracking-tight">Kelola buku, anggota, dan peminjaman dalam tampilan yang modern dan nyaman dilihat.</h1>
            <p class="mt-4 max-w-3xl leading-8" style="color: rgba(255,255,255,0.88);">
                Sistem ini membantu admin dan anggota menjalankan proses perpustakaan sekolah dari pencarian buku sampai pengembalian dengan alur yang jelas.
            </p>
            <div class="page-actions mt-5">
                <a href="{{ route('register') }}" class="btn-secondary">Buat Akun</a>
                <a href="{{ route('login') }}" class="btn-primary">Masuk Sekarang</a>
            </div>
        </section>

        <section id="koleksi" class="books-section">
            <h2 class="section-title">Koleksi Buku Perpustakaan</h2>
            <p class="section-subtitle">Jelajahi koleksi buku terbaru kami dengan berbagai kategori pilihan</p>
            <div class="book-gallery">
                @php
                    $bukus = \App\Models\Buku::with('kategori')
                        ->where('stok', '>', 0)
                        ->orderByDesc('created_at')
                        ->limit(6)
                        ->get();
                @endphp

                @forelse($bukus as $buku)
                    <div class="book-card">
                        <div class="book-cover">
                            @if($buku->image)
                                <img src="{{ $buku->image }}" alt="{{ $buku->judul }}" loading="lazy">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 460">
                                    <defs>
                                        <linearGradient id="g{{ $loop->index }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#667eea"/>
                                            <stop offset="100%" stop-color="#764ba2"/>
                                        </linearGradient>
                                    </defs>
                                    <rect width="320" height="460" fill="url(#g{{ $loop->index }})"/>
                                    <text x="160" y="230" text-anchor="middle" font-size="24" font-weight="bold" fill="white">
                                        {{ $buku->judul }}
                                    </text>
                                </svg>
                            @endif
                            <div class="book-badge">{{ $buku->stok }} tersedia</div>
                        </div>
                        <div class="book-info">
                            <div class="book-title">{{ $buku->judul }}</div>
                            <div class="book-author">{{ $buku->penulis ?? 'Penulis tidak diketahui' }}</div>
                            <div class="book-meta">
                                <span class="book-year">{{ $buku->tahun_terbit ?? '-' }}</span>
                                <span class="book-stok">{{ $buku->kategori?->nama_kategori ?? 'Umum' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--muted);">
                        <p>Belum ada buku dalam koleksi.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <section id="layanan" class="grid-two mt-6">
            <div class="hero-panel" style="min-height: 320px;">
                <span class="hero-chip">Layanan Utama</span>
                <h2 class="mt-4 text-4xl font-bold tracking-tight">Katalog buku digital dengan cover, pencarian, dan filter yang lebih menarik.</h2>
                <p class="mt-4 max-w-2xl leading-8" style="color: rgba(255,255,255,0.88);">
                    Katalog didesain lebih visual agar anggota lebih mudah menemukan buku yang ingin dipinjam.
                </p>
            </div>
            <div class="soft-panel p-6">
                <div class="space-y-4">
                    <div class="info-card">
                        <h3 class="text-lg font-bold">Admin</h3>
                        <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Kelola buku, kategori, anggota, dan semua transaksi peminjaman.</p>
                    </div>
                    <div class="info-card">
                        <h3 class="text-lg font-bold">Anggota</h3>
                        <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Lihat katalog, pilih buku, ajukan pinjaman, dan pantau status transaksi.</p>
                    </div>
                    <div class="info-card">
                        <h3 class="text-lg font-bold">Riwayat</h3>
                        <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Semua aktivitas dicatat dalam sistem sehingga mudah dilacak kapan saja.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="manfaat" class="surface page-block mt-6">
            <div class="text-center">
                <div class="text-sm font-semibold uppercase" style="color: var(--accent); letter-spacing: 0.18em;">Manfaat Sistem</div>
                <h2 class="mt-3 text-3xl font-bold">Semua kebutuhan perpustakaan sekolah dalam satu tempat.</h2>
                <p class="mt-3 max-w-3xl mx-auto leading-8" style="color: var(--muted);">
                    Tampilan disusun lebih modern seperti referensi: terang, rapi, penuh ruang kosong yang nyaman, serta mudah dipakai di desktop maupun mobile.
                </p>
            </div>
            <div class="feature-grid mt-6">
                <article class="info-card">
                    <h3 class="text-lg font-bold">Dashboard Ringkas</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Statistik dan akses menu utama bisa dipahami dengan cepat.</p>
                </article>
                <article class="info-card">
                    <h3 class="text-lg font-bold">Katalog Visual</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Buku ditampilkan dengan cover yang menarik dan informasi lengkap.</p>
                </article>
                <article class="info-card">
                    <h3 class="text-lg font-bold">Form Lebih Nyaman</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Input, filter, dan tindakan dibuat lebih jelas dan konsisten.</p>
                </article>
                <article class="info-card">
                    <h3 class="text-lg font-bold">Visual Seragam</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Dari halaman awal sampai halaman admin/user, semuanya memakai gaya yang sama.</p>
                </article>
            </div>
        </section>

        <footer class="footer-bar">
            Perpustakaan Digital Sekolah - tampilan modern untuk admin dan anggota.
        </footer>
    </div>
</body>
</html>
                    <h3 class="text-lg font-bold">Form Lebih Nyaman</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Input, filter, dan tindakan dibuat lebih jelas dan konsisten.</p>
                </article>
                <article class="info-card">
                    <h3 class="text-lg font-bold">Visual Seragam</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Dari halaman awal sampai halaman admin/user, semuanya memakai gaya yang sama.</p>
                </article>
            </div>
        </section>

        <footer class="footer-bar">
            Perpustakaan Digital Sekolah - tampilan modern untuk admin dan anggota.
        </footer>
    </div>
</body>
</html>
