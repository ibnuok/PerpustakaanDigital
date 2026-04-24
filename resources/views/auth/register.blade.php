<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - Perpustakaan Digital</title>
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
            grid-template-columns: .95fr 1.05fr;
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
        .field-label { display:block; margin-bottom:8px; font-weight:700; }
        .field-input {
            width:100%; border:1px solid #c9beaf; border-radius:16px; padding:12px 14px; background:#fff;
        }
        .btn {
            width:100%; border:none; border-radius:999px; padding:13px 16px; background:var(--brand); color:#fff; font-weight:700; cursor:pointer;
        }
        .list { display:grid; gap:12px; margin-top:18px; }
        .list-item { padding:14px 16px; border-radius:18px; border:1px solid #ece3d7; background:var(--accent-soft); }
        .link { color: var(--brand); font-weight:700; text-decoration:none; }
        @media (max-width: 900px) {
            .shell { grid-template-columns: 1fr; }
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
            <span class="badge">Pendaftaran Anggota</span>
            <h1 style="margin:16px 0 8px; font-size:clamp(30px, 7vw, 42px); line-height:1.1;">Daftar sebagai peminjam perpustakaan.</h1>
            <p style="margin:0; color:var(--muted); line-height:1.7;">Form ini hanya membuat akun `user` atau anggota. Akun `admin` tetap dibuat dari panel admin agar akses sistem tetap aman.</p>

            <div class="list">
                <div class="list-item"><strong>Akun anggota</strong><div style="margin-top:6px; color:var(--muted);">Bisa login, melihat katalog, meminjam buku, dan membayar denda.</div></div>
                <div class="list-item"><strong>Akun admin</strong><div style="margin-top:6px; color:var(--muted);">Tidak bisa dibuat dari halaman register publik.</div></div>
                <div class="list-item"><strong>Keamanan akses</strong><div style="margin-top:6px; color:var(--muted);">Role admin hanya dikelola dari sistem internal.</div></div>
            </div>
        </section>

        <section class="panel">
            <a href="/" class="link">Kembali ke beranda</a>
            <h2 style="margin:18px 0 6px; font-size:clamp(28px, 6vw, 34px);">Buat Akun Anggota</h2>
            <p style="margin:0 0 22px; color:var(--muted);">Isi data berikut untuk membuat akun peminjam.</p>

            <form method="POST" action="{{ route('register') }}" style="display:grid; gap:16px;">
                @csrf
                <div>
                    <label for="name" class="field-label">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="field-input">
                    @error('name')<div style="margin-top:6px; color:var(--danger);">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="field-input">
                    @error('email')<div style="margin-top:6px; color:var(--danger);">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="password" class="field-label">Password</label>
                    <input id="password" type="password" name="password" required class="field-input">
                    @error('password')<div style="margin-top:6px; color:var(--danger);">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="field-label">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="field-input">
                </div>
                <button type="submit" class="btn">Daftar Sebagai Anggota</button>
            </form>

            <div style="margin-top:16px; color:var(--muted);">
                Sudah punya akun? <a href="{{ route('login') }}" class="link">Masuk di sini</a>
            </div>
        </section>
    </div>
</body>
</html>
