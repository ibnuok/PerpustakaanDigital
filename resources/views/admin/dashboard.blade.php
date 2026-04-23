@extends('layouts.portal')

@section('title', 'Dashboard Admin')
@section('page_heading', 'Good Morning Admin')
@section('page_description', 'Ringkasan aktivitas perpustakaan, koleksi buku, anggota, dan transaksi terbaru dalam tampilan modern.')

@section('page_actions')
    <a href="{{ route('admin.buku.create') }}" class="action-btn action-btn-secondary">+ Tambah Buku</a>
    <a href="{{ route('admin.peminjaman.create') }}" class="action-btn action-btn-primary">+ Buat Transaksi</a>
@endsection

@section('content')
    <!-- Statistics Grid -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Total Stok Buku</div>
                    <div class="stat-value">{{ $totalBuku }}</div>
                </div>
                <div style="font-size: 2rem;">📚</div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Total Judul</div>
                    <div class="stat-value">{{ $totalJudul }}</div>
                </div>
                <div style="font-size: 2rem;">📖</div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Total Anggota</div>
                    <div class="stat-value">{{ $totalAnggota }}</div>
                </div>
                <div style="font-size: 2rem;">👥</div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Transaksi Aktif</div>
                    <div class="stat-value">{{ $peminjamanAktif }}</div>
                </div>
                <div style="font-size: 2rem;">🔄</div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Terlambat</div>
                    <div class="stat-value" style="color: var(--danger);">{{ $peminjamanTerlambat }}</div>
                </div>
                <div style="font-size: 2rem;">⏰</div>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="stat-label">Admin</div>
                    <div class="stat-value">{{ $totalAdmin }}</div>
                </div>
                <div style="font-size: 2rem;">🛡️</div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 2rem;">
        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">
                <h2>Transaksi Terbaru</h2>
                <a href="{{ route('admin.peminjaman.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Lihat Semua →</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Status</th>
                            <th>Sisa Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamanTerbaru as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->buku->judul }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($item->status) }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td style="color: {{ $item->isLate() ? 'var(--danger)' : 'var(--text-light)' }};">{{ $item->sisa_waktu_label }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-light);">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Side Panel -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Statistics Status -->
            <div class="card">
                <div class="card-header">
                    <h2>Statistik Status</h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-light);">Menunggu</span>
                            <strong style="font-size: 1.2rem;">{{ $statistikPerStatus['pending'] ?? 0 }}</strong>
                        </div>
                        <div style="border-bottom: 1px solid var(--border-light);"></div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-light);">Dipinjam</span>
                            <strong style="font-size: 1.2rem;">{{ $statistikPerStatus['approved'] ?? 0 }}</strong>
                        </div>
                        <div style="border-bottom: 1px solid var(--border-light);"></div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-light);">Dikembalikan</span>
                            <strong style="font-size: 1.2rem;">{{ $statistikPerStatus['returned'] ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Books -->
            <div class="card">
                <div class="card-header">
                    <h2>Buku Terpopuler</h2>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @forelse ($bukuTerlaris as $buku)
                            <div>
                                <p style="margin: 0 0 0.25rem; font-weight: 600; font-size: 0.95rem;">{{ $buku->judul }}</p>
                                <p style="margin: 0; font-size: 0.85rem; color: var(--text-light);">{{ $buku->penulis ?? 'Unknown' }} - {{ $buku->peminjamans_count }} transaksi</p>
                            </div>
                        @empty
                            <p style="color: var(--text-light);">Belum ada data buku terlaris.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
