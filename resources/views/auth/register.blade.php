<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Perpustakaan Digital Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/app-fallback.css') }}">
    @include('partials.vite')
</head>
<body class="page-shell">
    <div class="auth-shell">
        <div class="surface auth-card">
            <a href="/" class="text-sm font-semibold" style="color: var(--muted);">Kembali ke beranda</a>
            <h1 class="mt-4 text-3xl font-bold">Buat akun baru</h1>
            <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Pilih role saat daftar, lalu saat login nanti sistem langsung menyesuaikan akses Anda.</p>

            @if (session('status'))
                <div class="mt-6 soft-panel p-4" style="background: var(--success-bg); color: var(--success);">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="name" class="field-label">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="field-input @error('name') border-rose-400 @enderror" placeholder="Masukkan nama lengkap">
                    @error('name')<p class="mt-2 text-sm" style="color: var(--danger);">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="field-input @error('email') border-rose-400 @enderror" placeholder="nama@email.com">
                    @error('email')<p class="mt-2 text-sm" style="color: var(--danger);">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="role" class="field-label">Daftar Sebagai</label>
                    <select id="role" name="role" class="field-select @error('role') border-rose-400 @enderror" required>
                        <option value="">Pilih role akun</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="user" @selected(old('role') === 'user')>User / Anggota</option>
                    </select>
                    @error('role')<p class="mt-2 text-sm" style="color: var(--danger);">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="field-label">Password</label>
                    <input id="password" type="password" name="password" required class="field-input @error('password') border-rose-400 @enderror" placeholder="Minimal 8 karakter">
                    @error('password')<p class="mt-2 text-sm" style="color: var(--danger);">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="field-label">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="field-input" placeholder="Ulangi password">
                </div>

                <button type="submit" class="btn-primary w-full">Daftar Sekarang</button>
            </form>

            <div class="mt-6 text-center text-sm" style="color: var(--muted);">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold" style="color: var(--accent-deep);">Masuk di sini</a>
            </div>
        </div>

        <div class="auth-side">
            <span class="hero-chip" style="color: var(--accent-deep); background: rgba(38,102,255,0.08);">Panduan Pendaftaran</span>
            <h2 class="mt-4 text-5xl font-bold tracking-tight">Satu sistem untuk admin dan anggota perpustakaan.</h2>
            <p class="mt-4 max-w-3xl leading-8" style="color: var(--muted);">
                Desain dibuat terang, modern, dan terstruktur seperti referensi Anda agar nyaman dipakai dari halaman awal sampai semua halaman internal.
            </p>
            <div class="feature-grid mt-6">
                <article class="info-card">
                    <h3 class="text-lg font-bold">Admin</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Mengelola buku, kategori, anggota, dan transaksi.</p>
                </article>
                <article class="info-card">
                    <h3 class="text-lg font-bold">User</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Mencari buku, meminjam, dan mengembalikan dari dashboard.</p>
                </article>
                <article class="info-card">
                    <h3 class="text-lg font-bold">Role Otomatis</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Role dipilih sekali saat daftar, lalu dipakai terus saat login.</p>
                </article>
            </div>
        </div>
    </div>
</body>
</html>
