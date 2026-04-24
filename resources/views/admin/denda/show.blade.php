@extends('layouts.portal')

@section('title', 'Detail Verifikasi Denda')
@section('page_heading', 'Detail Verifikasi Denda')
@section('page_description', 'Pastikan pembayaran yang diajukan peminjam valid sebelum mengubah status menjadi lunas.')

@section('page_actions')
    <a href="{{ route('admin.denda.index') }}" class="btn-secondary">Kembali ke Daftar</a>
@endsection

@section('content')
@php
    $peminjaman = $pengembalian->peminjaman;
    $user = $peminjaman?->user;
    $buku = $peminjaman?->buku;
@endphp

<div class="grid-2" style="align-items:start;">
    <div class="stack-list">
        <section class="card">
            <span class="badge-soft">Peminjam</span>
            <div class="detail-grid" style="margin-top:16px;">
                <div class="stack-item"><strong>Nama</strong><div>{{ $user?->name ?? 'Data peminjam tidak tersedia' }}</div></div>
                <div class="stack-item"><strong>Email</strong><div>{{ $user?->email ?? '-' }}</div></div>
            </div>
        </section>

        <section class="card">
            <span class="badge-soft">Buku dan Denda</span>
            <div class="detail-grid" style="margin-top:16px;">
                <div class="stack-item"><strong>Buku</strong><div>{{ $buku?->judul ?? 'Data buku tidak tersedia' }}</div></div>
                <div class="stack-item"><strong>Jenis Denda</strong><div>{{ $pengembalian->jenis_denda_label }}</div></div>
                <div class="stack-item"><strong>Metode Bayar</strong><div>{{ $pengembalian->metode_pembayaran_label }}</div></div>
                <div class="stack-item"><strong>Denda Terlambat</strong><div>{{ ($pengembalian->denda_telat ?? 0) > 0 ? $pengembalian->hari_terlambat . ' hari • Rp ' . number_format($pengembalian->denda_telat ?? 0, 0, ',', '.') : 'Tidak terkena denda terlambat' }}</div></div>
                <div class="stack-item"><strong>Denda Kerusakan</strong><div>{{ ($pengembalian->denda_kerusakan ?? 0) > 0 ? 'Rp ' . number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') : 'Tidak terkena denda kerusakan' }}</div></div>
            </div>
            <div class="stack-item" style="margin-top:16px;">
                <strong>Total Denda</strong>
                <div style="font-size:30px; margin-top:8px; color:var(--danger);">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</div>
                @if ($pengembalian->deskripsi_kerusakan)
                    <small>Catatan kerusakan: {{ $pengembalian->deskripsi_kerusakan }}</small>
                @endif
            </div>
        </section>
    </div>

    <div class="stack-list">
        @if ($pengembalian->metode_pembayaran === 'transfer' && $pengembalian->bukti_pembayaran)
            <section class="card">
                <span class="badge-soft">Bukti Transfer</span>
                <div style="margin-top:16px;">
                    <img src="{{ route('admin.denda.view-bukti', $pengembalian) }}" alt="Bukti Pembayaran" style="width:100%; border-radius:18px; border:1px solid var(--line);">
                </div>
                <div class="action-row">
                    <a href="{{ route('admin.denda.download-bukti', $pengembalian) }}" class="btn-secondary">Download Bukti</a>
                </div>
            </section>
        @endif

        <section class="card">
            <span class="badge-soft">Status Pembayaran</span>
            <div class="stack-item" style="margin-top:16px;">
                <strong>Status Saat Ini</strong>
                <div style="margin-top:8px;">{{ $pengembalian->status_label }}</div>
                <small>Diajukan pada {{ $pengembalian->tanggal_pembayaran?->format('d M Y H:i') ?? '-' }}</small>
            </div>

            @if ($pengembalian->status_pembayaran === 'pending_approval')
                <div class="stack-item" style="margin-top:16px; background: var(--warning-soft); color: var(--warning); border-color: #ecd9b0;">
                    Pembayaran sudah diajukan oleh peminjam dan siap diverifikasi admin dari halaman ini.
                </div>
            @endif

            @if ($pengembalian->catatan_penolakan)
                <div class="stack-item" style="margin-top:16px; color:var(--danger);">
                    <strong>Penolakan Terakhir</strong>
                    <div style="margin-top:8px;">{{ $pengembalian->catatan_penolakan }}</div>
                </div>
            @endif

            @if (! $peminjaman || ! $user || ! $buku)
                <div class="stack-item" style="margin-top:16px; color:var(--danger);">
                    Data relasi denda ini tidak lengkap, jadi verifikasi admin dinonaktifkan sampai data peminjaman diperbaiki.
                </div>
            @elseif ($pengembalian->status_pembayaran === 'pending_approval')
                <div class="action-row">
                    <form action="{{ route('admin.denda.approve', ['pengembalian' => $pengembalian->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary">Setujui Pembayaran</button>
                    </form>
                </div>

                <form action="{{ route('admin.denda.reject', ['pengembalian' => $pengembalian->id]) }}" method="POST" style="margin-top:16px;">
                    @csrf
                    <label class="field-label">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" rows="4" class="field-textarea" placeholder="Jelaskan alasan penolakan pembayaran ini..." required></textarea>
                    <div class="action-row" style="justify-content:flex-end;">
                        <button type="submit" class="btn-danger">Tolak Pembayaran</button>
                    </div>
                </form>
            @elseif ($pengembalian->status_pembayaran === 'sudah_dibayar')
                <div class="stack-item" style="margin-top:16px; color:var(--success);">
                    Pembayaran sudah diverifikasi dan status tagihan sekarang lunas.
                </div>
            @else
                <div class="stack-item" style="margin-top:16px; color:var(--muted);">
                    Belum ada pengajuan pembayaran dari peminjam.
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
