<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital</title>
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
            --danger: #b53d2e;
            --shadow: 0 18px 40px rgba(31, 41, 51, 0.08);
        }
        * { box-sizing: border-box; }
        img { max-width: 100%; height: auto; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Georgia, 'Times New Roman', serif;
            background: var(--bg);
            color: var(--text);
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .shell {
            width: min(1080px, 100%);
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 22px;
        }
        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
            padding: 28px;
        }
        .badge {
            display: inline-flex;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--brand-soft);
            color: var(--brand);
            font-size: 12px;
            font-weight: 700;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }
        .book-card {
            border: 1px solid var(--line);
            border-radius: 18px;
            overflow: hidden;
            background: #faf8f4;
        }
        .book-card img { width: 100%; height: 210px; object-fit: cover; display:block; }
        .book-card .content { padding: 14px; }
        .field-label { display:block; margin-bottom:8px; font-weight:700; }
        .field-input {
            width:100%; border:1px solid #c9beaf; border-radius:16px; padding:12px 14px; background:#fff;
        }
        .btn {
            width:100%; border:none; border-radius:999px; padding:13px 16px; background:var(--brand); color:#fff; font-weight:700; cursor:pointer;
        }
        .link { color: var(--brand); font-weight: 700; text-decoration: none; }
        @media (max-width: 900px) {
            .shell { grid-template-columns: 1fr; }
            .grid { grid-template-columns: 1fr; }
            body { padding: 16px; }
            .panel { padding: 22px; }
        }

        @media (max-width: 560px) {
            body { padding: 12px; }
            .panel { padding: 18px; border-radius: 22px; }
        }
    </style>
</head>
<body>
    <div class="shell">
        <section class="panel">
            <span class="badge">Perpustakaan Digital</span>
            <h1 style="margin:16px 0 8px; font-size:clamp(30px, 7vw, 42px); line-height:1.1;">Masuk ke sistem perpustakaan sekolah.</h1>
            <p style="margin:0; color:var(--muted); line-height:1.7;">Admin dan peminjam memakai satu pintu login. Pendaftaran publik hanya tersedia untuk akun anggota atau `user`, bukan untuk admin.</p>
            <div class="grid">
                @php
                    $bukus = \App\Models\Buku::where('stok', '>', 0)->latest()->limit(4)->get();
                @endphp
                @forelse ($bukus as $buku)
                    <article class="book-card">
                        @if ($buku->image)
                            <img src="{{ asset('images/' . $buku->image) }}" alt="{{ $buku->judul }}">
                        @else
                            <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}">
                        @endif
                        <div class="content">
                            <strong>{{ $buku->judul }}</strong>
                            <div style="margin-top:6px; color:var(--muted);">{{ $buku->penulis ?? 'Penulis tidak tersedia' }}</div>
                        </div>
                    </article>
                @empty
                    <div class="book-card"><div class="content">Belum ada koleksi tersedia.</div></div>
                @endforelse
            </div>
        </section>

        <section class="panel">
            <a href="/" class="link">Kembali ke beranda</a>
            <h2 style="margin:18px 0 6px; font-size:clamp(28px, 6vw, 34px);">Login</h2>
            <p style="margin:0 0 22px; color:var(--muted);">Masukkan email dan password untuk melanjutkan.</p>

            @if (session('status'))
                <div style="margin-bottom:16px; padding:14px; border-radius:16px; background:#e5f0ea; color:#2f6a4f;">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" style="display:grid; gap:16px;">
                @csrf
                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="field-input">
                    @error('email')<div style="margin-top:6px; color:var(--danger);">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="password" class="field-label">Password</label>
                    <input id="password" type="password" name="password" required class="field-input">
                    @error('password')<div style="margin-top:6px; color:var(--danger);">{{ $message }}</div>@enderror
                </div>
                <label style="display:flex; align-items:center; gap:8px; color:var(--muted);">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
                <button type="submit" class="btn">Masuk ke Dashboard</button>
            </form>

            @if (Route::has('password.request'))
                <div style="margin-top:16px;"><a href="{{ route('password.request') }}" class="link">Lupa password?</a></div>
            @endif

            <div style="margin-top:16px; color:var(--muted);">
                Belum punya akun anggota? <a href="{{ route('register') }}" class="link">Daftar di sini</a>
            </div>
        </section>
    </div>
</body>
</html>
