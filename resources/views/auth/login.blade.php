<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital Sekolah</title>
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

        .book-gallery-login {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 30px;
        }

        .book-card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .book-card:nth-child(1) { animation-delay: 0.1s; }
        .book-card:nth-child(2) { animation-delay: 0.2s; }
        .book-card:nth-child(3) { animation-delay: 0.3s; }

        .book-cover {
            position: relative;
            width: 100%;
            aspect-ratio: 9/12;
            border-radius: 8px;
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
            top: 8px;
            right: 8px;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            padding: 2px 8px;
            border-radius: 16px;
            font-size: 0.65rem;
            font-weight: 600;
            z-index: 10;
        }

        .book-title {
            margin-top: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.3;
            color: var(--text);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .auth-side-new {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 16px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .books-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--text);
            margin-bottom: 8px;
        }

        .books-subtitle {
            font-size: 0.875rem;
            color: var(--muted);
            margin-bottom: 20px;
        }

        @media (max-width: 1024px) {
            .book-gallery-login {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .book-cover {
                aspect-ratio: 9/12;
            }

            .book-title {
                font-size: 0.8rem;
            }
        }
    </style>
    @include('partials.vite')
</head>
<body class="page-shell">
    <div class="auth-shell">
        <div class="auth-side auth-side-new">
            <span class="hero-chip" style="color: var(--accent-deep); background: rgba(38,102,255,0.08);">Koleksi Buku</span>
            <h2 class="mt-4 text-3xl font-bold tracking-tight">Jelajahi perpustakaan digital sekolah kami</h2>
            <p class="mt-3 text-sm leading-7" style="color: var(--muted);">
                Dengan akun Anda, akses ribuan koleksi buku berkualitas dan kelola peminjaman dengan mudah.
            </p>
            
            <div class="books-gallery">
                <h3 class="books-title">Buku Populer</h3>
                <p class="books-subtitle">Buku-buku pilihan dari berbagai kategori</p>
                <div class="book-gallery-login">
                    @php
                        $bukus = \App\Models\Buku::with('kategori')
                            ->where('stok', '>', 0)
                            ->orderByDesc('created_at')
                            ->limit(3)
                            ->get();
                    @endphp

                    @forelse($bukus as $buku)
                        <div class="book-card">
                            <div class="book-cover">
                                @if($buku->image)
                                   <img src="{{ $buku->image && file_exists(public_path('images/'.$buku->image)) 
    ? asset('images/'.$buku->image) 
    : 'https://via.placeholder.com/150x200?text=Book' }}"
    alt="{{ $buku->judul }}"
    loading="lazy">
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
                                <div class="book-badge">{{ $buku->stok }} ada</div>
                            </div>
                            <div class="book-title">{{ $buku->judul }}</div>
                        </div>
                    @empty
                    @endforelse
                </div>
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
