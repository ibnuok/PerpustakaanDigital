@extends('layouts.portal')

@section('title', 'Dashboard Anggota')
@section('page_heading', 'Selamat Datang ' . explode(' ', auth()->user()->name)[0])
@section('page_description', 'Kelola peminjaman buku Anda dan jelajahi koleksi perpustakaan digital sekolah kami.')

@section('page_actions')
    <a href="{{ route('user.bukus') }}" class="action-btn action-btn-primary">+ Cari Buku</a>
@endsection

@section('content')
    <!-- Statistics Grid -->
    <div class="dashboard-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Pinjaman Aktif</div>
                    <div class="stat-value">{{ $peminjamanAktif->count() }}</div>
                </div>
                <div style="font-size: 2rem;">📚</div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Buku Tersedia</div>
                    <div class="stat-value">{{ $bukuTersedia }}</div>
                </div>
                <div style="font-size: 2rem;">📖</div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Selesai Dipinjam</div>
                    <div class="stat-value">{{ $peminjamanSelesai }}</div>
                </div>
                <div style="font-size: 2rem;">✅</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 2rem;">
        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h2>Riwayat Transaksi</h2>
                <a href="{{ route('user.peminjaman.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Lihat Semua →</a>
            </div>
            <div class="card-body" style="padding: 0;">
                @forelse ($peminjamanTerbaru as $item)
                    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="margin: 0; font-weight: 600; color: var(--text);">{{ $item->buku->judul }}</p>
                            <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: var(--text-light);">
                                {{ $item->tanggal_pinjam->format('d M Y') }} - {{ $item->tanggal_kembali->format('d M Y') }}
                            </p>
                            <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: {{ $item->isLate() ? 'var(--danger)' : 'var(--success)' }};">
                                {{ $item->sisa_waktu_label }}
                                @if ($item->denda > 0)
                                    | Denda Rp {{ number_format($item->denda, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                        <span class="badge badge-{{ strtolower($item->status) }}">{{ ucfirst($item->status) }}</span>
                    </div>
                @empty
                    <div style="padding: 2rem; text-align: center; color: var(--text-light);">
                        Belum ada transaksi. Mulai dari katalog buku untuk melakukan peminjaman.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Side Panel -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Quick Access -->
            <div class="card">
                <div class="card-header">
                    <h2>Menu Cepat</h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <a href="{{ route('user.bukus') }}" style="padding: 1rem; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1)); border-radius: 8px; border: 1px solid var(--primary); text-decoration: none; color: var(--primary); font-weight: 600; transition: all 0.3s;">
                            🔍 Cari Buku
                        </a>
                        <a href="{{ route('user.peminjaman.index') }}" style="padding: 1rem; background: var(--primary); border-radius: 8px; border: none; text-decoration: none; color: white; font-weight: 600; transition: all 0.3s; text-align: center;">
                            📋 Kelola Transaksi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="card">
                <div class="card-header">
                    <h2>💡 Tips</h2>
                </div>
                <div class="card-body">
                    <ul style="padding-left: 1.5rem; color: var(--text-light); font-size: 0.9rem; line-height: 1.8;">
                        <li>Gunakan fitur search untuk cari buku favorit</li>
                        <li>Kembalikan buku tepat waktu</li>
                        <li>Pantau denda jika terlambat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
