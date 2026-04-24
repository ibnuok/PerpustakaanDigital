<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Digital Sekolah</title>
    @include('partials.vite')
    <style>
        :root {
            --bg: #f3f0ea;
            --panel: #ffffff;
            --line: #ddd6cb;
            --text: #1f2933;
            --muted: #6b7280;
            --brand: #1f3a5f;
            --brand-soft: #e8eef5;
            --accent-soft: #f3ede4;
            --shadow: 0 18px 40px rgba(31, 41, 51, 0.08);
        }
        * { box-sizing: border-box; }
        img { max-width: 100%; height: auto; }
        body { margin:0; font-family: Georgia, 'Times New Roman', serif; background:var(--bg); color:var(--text); }
        .site { width:min(1200px, 100%); margin:0 auto; padding:24px; }
        .topbar, .panel { background:var(--panel); border:1px solid var(--line); border-radius:28px; box-shadow:var(--shadow); }
        .topbar { display:flex; justify-content:space-between; align-items:center; gap:16px; padding:18px 22px; }
        .brand-badge, .chip { display:inline-flex; align-items:center; padding:6px 12px; border-radius:999px; font-size:12px; font-weight:700; }
        .brand-badge { background:var(--brand); color:#fff; width:42px; height:42px; justify-content:center; }
        .chip { background:var(--brand-soft); color:var(--brand); }
        .btn { display:inline-flex; align-items:center; justify-content:center; padding:12px 18px; border-radius:999px; text-decoration:none; font-weight:700; }
        .btn-primary { background:var(--brand); color:#fff; }
        .btn-secondary { background:#fff; color:var(--brand); border:1px solid var(--line); }
        .hero { display:grid; grid-template-columns:1.1fr .9fr; gap:22px; margin-top:22px; }
        .panel { padding:28px; }
        .grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; margin-top:22px; }
        .book { border:1px solid var(--line); border-radius:20px; overflow:hidden; background:#faf8f4; }
        .book img { width:100%; height:240px; object-fit:cover; display:block; }
        .book .content { padding:14px; }
        .list { display:grid; gap:12px; margin-top:18px; }
        .list-item { padding:14px 16px; border-radius:18px; border:1px solid #ece3d7; background:var(--accent-soft); }
        @media (max-width: 920px) {
            .hero, .grid, .topbar { grid-template-columns:1fr; display:grid; }
            .topbar { justify-content:stretch; }
            .site { padding: 16px; }
            .panel { padding: 22px; }
        }

        @media (max-width: 560px) {
            .site { padding: 12px; }
            .panel { padding: 18px; border-radius: 22px; }
        }
    </style>
</head>
<body>
    <div class="site">
        <header class="topbar">
            <div style="display:flex; gap:12px; align-items:center;">
                <span class="brand-badge">PD</span>
                <div>
                    <div style="font-size:12px; text-transform:uppercase; letter-spacing:.14em; color:var(--muted);">Perpustakaan Sekolah</div>
                    <strong style="font-size:22px;">Perpustakaan Digital</strong>
                </div>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftar Anggota</a>
            </div>
        </header>

        <section class="hero">
            <div class="panel">
                <span class="chip">Sistem Peminjaman Buku</span>
                <h1 style="font-size:clamp(32px, 8vw, 52px); line-height:1.04; margin:16px 0 10px;">Manajemen perpustakaan yang lebih rapi, tenang, dan jelas alurnya.</h1>
                <p style="margin:0; color:var(--muted); line-height:1.8; max-width:720px;">Admin dapat mengelola buku, kategori, anggota, transaksi, dan verifikasi pembayaran denda. Peminjam dapat melihat katalog, memantau pinjaman, serta membayar denda telat maupun kerusakan langsung dari dashboard.</p>
                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:20px;">
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar Sebagai Anggota</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Masuk ke Sistem</a>
                </div>
            </div>

            <div class="panel">
                <span class="chip">Alur Utama</span>
                <div class="list">
                    <div class="list-item"><strong>1. Pendaftaran Anggota</strong><div style="margin-top:6px; color:var(--muted);">Pengunjung bisa daftar sendiri sebagai user atau anggota.</div></div>
                    <div class="list-item"><strong>2. Pengembalian dan Denda</strong><div style="margin-top:6px; color:var(--muted);">Denda telat dihitung otomatis, denda kerusakan ditambahkan admin bila perlu.</div></div>
                    <div class="list-item"><strong>3. Akses Admin Tetap Aman</strong><div style="margin-top:6px; color:var(--muted);">Akun admin tidak bisa dibuat lewat register publik.</div></div>
                </div>
            </div>
        </section>

        <section class="panel" style="margin-top:22px;">
            <div style="display:flex; justify-content:space-between; gap:16px; align-items:end; flex-wrap:wrap;">
                <div>
                    <span class="chip">Koleksi Tersedia</span>
                    <h2 style="margin:14px 0 6px; font-size:clamp(28px, 6vw, 34px);">Beberapa buku yang siap dipinjam</h2>
                    <p style="margin:0; color:var(--muted);">Register publik sekarang aktif lagi, tetapi hanya untuk akun anggota.</p>
                </div>
            </div>
            <div class="grid">
                @php($bukus = \App\Models\Buku::where('stok', '>', 0)->latest()->limit(6)->get())
                @forelse ($bukus as $buku)
                    <article class="book">
                        @if ($buku->image)
                            <img src="{{ asset('images/' . $buku->image) }}" alt="{{ $buku->judul }}">
                        @else
                            <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}">
                        @endif
                        <div class="content">
                            <strong>{{ $buku->judul }}</strong>
                            <div style="margin-top:6px; color:var(--muted);">{{ $buku->penulis ?? 'Penulis tidak tersedia' }}</div>
                            <div style="margin-top:10px; color:var(--brand); font-weight:700;">Stok {{ $buku->stok }}</div>
                        </div>
                    </article>
                @empty
                    <div class="list-item" style="grid-column:1 / -1;">Belum ada koleksi tersedia.</div>
                @endforelse
            </div>
        </section>
    </div>
</body>
</html>
