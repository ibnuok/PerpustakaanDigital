<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/app-fallback.css') }}">
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
                <a href="#cara-pakai" class="nav-pill">Cara Pakai</a>
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

        <section id="cara-pakai" class="feature-grid mt-6">
            <article class="info-card">
                <div class="text-sm font-semibold" style="color: var(--accent);">Langkah 1</div>
                <h3 class="mt-3 text-xl font-bold">Daftar Sesuai Kebutuhan</h3>
                <p class="mt-3 text-sm leading-7" style="color: var(--muted);">Pilih role saat pendaftaran. Setelah itu sistem akan menyimpan akses sesuai akun Anda.</p>
            </article>
            <article class="info-card">
                <div class="text-sm font-semibold" style="color: var(--accent);">Langkah 2</div>
                <h3 class="mt-3 text-xl font-bold">Login Tanpa Pilih Role Lagi</h3>
                <p class="mt-3 text-sm leading-7" style="color: var(--muted);">Cukup masukkan email dan password, lalu sistem otomatis mengarahkan Anda ke halaman yang sesuai.</p>
            </article>
            <article class="info-card">
                <div class="text-sm font-semibold" style="color: var(--accent);">Langkah 3</div>
                <h3 class="mt-3 text-xl font-bold">Kelola dan Pantau Transaksi</h3>
                <p class="mt-3 text-sm leading-7" style="color: var(--muted);">Admin dapat mengelola data, sedangkan anggota bisa mencari buku, meminjam, dan mengembalikan.</p>
            </article>
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
