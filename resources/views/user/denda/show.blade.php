@extends('layouts.portal')

@section('title', 'Detail Denda')
@section('page_heading', 'Detail Denda')
@section('page_description', 'Rincian pembayaran denda telat dan kerusakan untuk transaksi ini.')

@section('page_actions')
    <a href="{{ route('user.denda.history') }}" class="btn-secondary">Kembali ke Riwayat</a>
@endsection

@section('content')
<div class="form-shell">
    <section class="form-panel">
        <span class="badge-soft">Ringkasan Tagihan</span>
        <div class="detail-grid" style="margin-top:16px;">
            <div class="stack-item"><strong>Buku</strong><div>{{ $pengembalian->peminjaman->buku->judul }}</div></div>
            <div class="stack-item"><strong>Status</strong><div>{{ $pengembalian->status_label }}</div></div>
            <div class="stack-item"><strong>Jenis Denda</strong><div>{{ $pengembalian->jenis_denda_label }}</div></div>
            <div class="stack-item"><strong>Denda Terlambat</strong><div>{{ ($pengembalian->denda_telat ?? 0) > 0 ? $pengembalian->hari_terlambat . ' hari • Rp ' . number_format($pengembalian->denda_telat ?? 0, 0, ',', '.') : 'Tidak terkena denda terlambat' }}</div></div>
            <div class="stack-item"><strong>Denda Kerusakan</strong><div>{{ ($pengembalian->denda_kerusakan ?? 0) > 0 ? 'Rp ' . number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') : 'Tidak terkena denda kerusakan' }}</div></div>
            <div class="stack-item"><strong>Total</strong><div style="color:var(--danger); font-size:24px;">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</div></div>
            <div class="stack-item"><strong>Metode</strong><div>{{ $pengembalian->metode_pembayaran_label }}</div></div>
        </div>

        @if ($pengembalian->deskripsi_kerusakan)
            <div class="stack-item" style="margin-top:16px;">
                <strong>Deskripsi Kerusakan</strong>
                <div>{{ $pengembalian->deskripsi_kerusakan }}</div>
            </div>
        @endif

        @if ($pengembalian->catatan_penolakan)
            <div class="stack-item" style="margin-top:16px; color:var(--danger);">
                <strong>Catatan Admin</strong>
                <div>{{ $pengembalian->catatan_penolakan }}</div>
            </div>
        @endif
    </section>

    <aside class="guide-card">
        <span class="badge-soft">Timeline Pembayaran</span>
        <div class="stack-list" style="margin-top:16px;">
            <div class="stack-item"><strong>Tanggal Kembali</strong><div>{{ $pengembalian->tanggal_pengembalian?->format('d M Y H:i') ?? '-' }}</div></div>
            <div class="stack-item"><strong>Tanggal Pengajuan Bayar</strong><div>{{ $pengembalian->tanggal_pembayaran?->format('d M Y H:i') ?? '-' }}</div></div>
            <div class="stack-item"><strong>Bukti Transfer</strong><div>{{ $pengembalian->bukti_pembayaran ? 'Sudah diupload' : 'Tidak ada' }}</div></div>
        </div>
    </aside>
</div>
@endsection
