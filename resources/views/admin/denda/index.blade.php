@extends('layouts.portal')

@section('title', 'Verifikasi Denda')
@section('page_heading', 'Verifikasi Pembayaran Denda')
@section('page_description', 'Kelola pembayaran denda telat dan kerusakan, lalu verifikasi pengajuan tunai maupun transfer dari peminjam.')

@section('content')
<div class="dashboard-grid" style="margin-bottom:20px;">
    <section class="stat-card">
        <div class="stat-label">Total Denda</div>
        <div class="stat-value">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Belum Lunas</div>
        <div class="stat-value" style="color:var(--danger);">{{ $belumDibayar }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Menunggu Verifikasi</div>
        <div class="stat-value" style="color:var(--warning);">{{ $pendingApproval }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Lunas</div>
        <div class="stat-value" style="color:var(--success);">{{ $sudahDibayar }}</div>
    </section>
</div>

<div class="action-row" style="margin-bottom:20px;">
    <a href="{{ route('admin.denda.index') }}" class="btn-secondary {{ !request('status') ? 'active' : '' }}">Pending</a>
    <a href="{{ route('admin.denda.index', ['status' => 'belum_dibayar']) }}" class="btn-secondary">Belum Lunas</a>
    <a href="{{ route('admin.denda.index', ['status' => 'sudah_dibayar']) }}" class="btn-secondary">Lunas</a>
</div>

<div class="table-wrap">
    <table class="responsive-table">
        <thead>
            <tr>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Rincian Denda</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalians as $pengembalian)
                <tr>
                    <td data-label="Peminjam">
                        <strong>{{ $pengembalian->peminjaman->user->name }}</strong><br>
                        <small>{{ $pengembalian->peminjaman->user->email }}</small>
                    </td>
                    <td data-label="Buku">
                        <strong>{{ $pengembalian->peminjaman->buku->judul }}</strong><br>
                        <small>{{ $pengembalian->peminjaman->buku->penulis ?? 'Penulis tidak tersedia' }}</small>
                    </td>
                    <td data-label="Rincian Denda">
                        <div><strong>{{ $pengembalian->jenis_denda_label }}</strong></div>
                        @if (($pengembalian->denda_telat ?? 0) > 0)
                            <div>Terlambat {{ $pengembalian->hari_terlambat }} hari: Rp {{ number_format($pengembalian->denda_telat ?? 0, 0, ',', '.') }}</div>
                        @endif
                        @if (($pengembalian->denda_kerusakan ?? 0) > 0)
                            <div>Kerusakan buku: Rp {{ number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') }}</div>
                        @endif
                        <strong style="display:block; margin-top:6px; color:var(--danger);">Total Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</strong>
                    </td>
                    <td data-label="Metode">{{ $pengembalian->metode_pembayaran_label }}</td>
                    <td data-label="Status">
                        @if ($pengembalian->status_pembayaran === 'belum_dibayar')
                            <span class="status-badge status-belum">Belum Lunas</span>
                        @elseif ($pengembalian->status_pembayaran === 'pending_approval')
                            <span class="status-badge status-pending">Menunggu Verifikasi</span>
                        @else
                            <span class="status-badge status-lunas">Lunas</span>
                        @endif
                    </td>
                    <td data-label="Aksi">
                        <div class="action-row" style="justify-content:flex-start; margin-top:0;">
                            <a href="{{ route('admin.denda.show', ['denda' => $pengembalian->id]) }}" class="btn-primary">Detail</a>

                            @if ($pengembalian->status_pembayaran === 'pending_approval')
                                <form action="{{ route('admin.denda.approve', ['pengembalian' => $pengembalian->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-secondary">Approve</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">Tidak ada data denda pada filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px;">{{ $pengembalians->links() }}</div>
@endsection
