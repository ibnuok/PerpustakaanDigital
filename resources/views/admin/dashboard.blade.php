@extends('layouts.portal')

@section('title', 'Dashboard Admin')
@section('page_heading', 'Dashboard Admin')
@section('page_description', 'Ringkasan operasional perpustakaan, transaksi aktif, dan status keterlambatan hari ini.')

@section('page_actions')
    <a href="{{ route('admin.buku.create') }}" class="btn-secondary">Tambah Buku</a>
    <a href="{{ route('admin.peminjaman.create') }}" class="btn-primary">Tambah Transaksi</a>
@endsection

@section('content')
<div class="dashboard-grid">
    <section class="stat-card">
        <div class="stat-label">Total Stok</div>
        <div class="stat-value">{{ $totalBuku }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Judul Buku</div>
        <div class="stat-value">{{ $totalJudul }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Anggota</div>
        <div class="stat-value">{{ $totalAnggota }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Transaksi Aktif</div>
        <div class="stat-value">{{ $peminjamanAktif }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Terlambat</div>
        <div class="stat-value" style="color: var(--danger);">{{ $peminjamanTerlambat }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Admin</div>
        <div class="stat-value">{{ $totalAdmin }}</div>
    </section>
</div>

<div class="grid-2" style="margin-top: 20px; align-items:start;">
    <section class="table-wrap">
        <div style="padding: 22px 22px 0; display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap;">
            <div>
                <h3 style="margin:0;">Transaksi Terbaru</h3>
                <p style="margin:8px 0 0; color:var(--muted);">Pantau pengajuan baru, buku yang masih dipinjam, dan pengembalian terbaru.</p>
            </div>
            <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Lihat Semua</a>
        </div>
        <table class="responsive-table">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Status</th>
                    <th>Jatuh Tempo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjamanTerbaru as $item)
                    <tr>
                        <td data-label="Peminjam">
                            <strong>{{ $item->user->name }}</strong><br>
                            <small>{{ $item->user->email }}</small>
                        </td>
                        <td data-label="Buku">
                            <strong>{{ $item->buku->judul }}</strong><br>
                            <small>{{ $item->buku->penulis ?? 'Penulis tidak tersedia' }}</small>
                        </td>
                        <td data-label="Status">
                            @if ($item->status === 'pending')
                                <span class="status-badge status-pending">Menunggu</span>
                            @elseif ($item->status === 'dipinjam')
                                <span class="status-badge badge-approved">Dipinjam</span>
                            @else
                                <span class="status-badge status-lunas">Dikembalikan</span>
                            @endif
                        </td>
                        <td data-label="Jatuh Tempo">
                            <strong style="color: {{ $item->isLate() ? 'var(--danger)' : 'var(--text)' }};">
                                {{ $item->status === 'returned' ? ($item->pengembalian?->tanggal_pengembalian?->format('d M Y H:i') ?? '-') : $item->tanggal_kembali->format('d M Y H:i') }}
                            </strong><br>
                            <small>{{ $item->sisa_waktu_label }}</small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-state">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="stack-list">
        <section class="card">
            <span class="badge-soft">Status Peminjaman</span>
            <div class="stack-list" style="margin-top: 14px;">
                <div class="stack-item"><strong>Menunggu</strong><div>{{ $statistikPerStatus['pending'] ?? 0 }} transaksi</div></div>
                <div class="stack-item"><strong>Dipinjam</strong><div>{{ $statistikPerStatus['dipinjam'] ?? 0 }} transaksi</div></div>
                <div class="stack-item"><strong>Dikembalikan</strong><div>{{ $statistikPerStatus['returned'] ?? 0 }} transaksi</div></div>
            </div>
        </section>

        <section class="card">
            <span class="badge-soft">Buku Paling Sering Dipinjam</span>
            <div class="stack-list" style="margin-top: 14px;">
                @forelse ($bukuTerlaris as $buku)
                    <div class="stack-item">
                        <strong>{{ $buku->judul }}</strong>
                        <div>{{ $buku->penulis ?? 'Penulis tidak tersedia' }}</div>
                        <small>{{ $buku->peminjamans_count }} kali dipinjam</small>
                    </div>
                @empty
                    <div class="stack-item">Belum ada data populer.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
