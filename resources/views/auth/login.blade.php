<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/app-fallback.css') }}">
    @include('partials.vite')
</head>
<body class="page-shell">
    <div class="auth-shell">
        <div class="auth-side">
            <span class="hero-chip" style="color: var(--accent-deep); background: rgba(38,102,255,0.08);">Masuk ke Sistem</span>
            <h1 class="mt-4 text-5xl font-bold tracking-tight">Masuk ke perpustakaan digital dengan akun yang sudah Anda daftarkan.</h1>
            <p class="mt-4 max-w-3xl leading-8" style="color: var(--muted);">
                Login dibuat sederhana. Anda hanya perlu email dan password, lalu sistem akan langsung menyesuaikan halaman admin atau user secara otomatis.
            </p>
            <div class="feature-grid mt-6">
                <article class="info-card">
                    <h3 class="text-lg font-bold">Akun Admin</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">admin@example.com / admin123</p>
                </article>
                <article class="info-card">
                    <h3 class="text-lg font-bold">Akun User</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">anggota@example.com / user12345</p>
                </article>
                <article class="info-card">
                    <h3 class="text-lg font-bold">Akses Cepat</h3>
                    <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Setelah login Anda akan langsung diarahkan ke dashboard yang sesuai.</p>
                </article>
            </div>
        </div>

        <div class="surface auth-card">
            <a href="/" class="text-sm font-semibold" style="color: var(--muted);">Kembali ke beranda</a>
            <h2 class="mt-4 text-3xl font-bold">Masuk ke akun</h2>
            <p class="mt-2 text-sm leading-7" style="color: var(--muted);">Masukkan email dan password untuk melanjutkan.</p>

            @if (session('status'))
                <div class="mt-6 soft-panel p-4" style="background: var(--success-bg); color: var(--success);">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="field-input @error('email') border-rose-400 @enderror" placeholder="nama@email.com">
                    @error('email')<p class="mt-2 text-sm" style="color: var(--danger);">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="field-label">Password</label>
                    <input id="password" type="password" name="password" required class="field-input @error('password') border-rose-400 @enderror" placeholder="Masukkan password">
                    @error('password')<p class="mt-2 text-sm" style="color: var(--danger);">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me" class="text-sm" style="color: var(--muted);">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-primary w-full">Masuk ke Dashboard</button>

                @if (Route::has('password.request'))
                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold" style="color: var(--accent-deep);">Lupa password?</a>
                    </div>
                @endif
            </form>

            <div class="mt-6 text-center text-sm" style="color: var(--muted);">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold" style="color: var(--accent-deep);">Daftar sekarang</a>
            </div>
        </div>
    </div>
</body>
</html>
