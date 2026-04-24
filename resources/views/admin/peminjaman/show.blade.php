@extends('layouts.portal')

@section('title', 'Detail Transaksi')
@section('page_heading', 'Detail Transaksi')
@section('page_description', 'Tinjau informasi peminjaman, status persetujuan, dan detail pengembalian dalam satu halaman.')

@section('page_actions')
    @if ($peminjaman->isPending())
        <a href="{{ route('admin.peminjaman.edit', $peminjaman) }}" class="btn-primary">Edit</a>
    @endif
    <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Kembali</a>
@endsection

@section('content')
    <div class="detail-card">
        <span class="badge-soft">Informasi Transaksi</span>
        <div class="detail-grid mt-5">
            <div class="detail-row">    
                <span class="detail-label">Peminjam</span>
                <span class="detail-value">{{ $peminjaman->user->name }}</span>
                <span class="detail-subvalue">{{ $peminjaman->user->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Buku</span>
                <span class="detail-value">{{ $peminjaman->buku->judul }}</span>
                <span class="detail-subvalue">{{ $peminjaman->buku->penulis }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Jumlah</span>
                <span class="detail-value">{{ $peminjaman->jumlah }} buku</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">{{ ucfirst($peminjaman->status) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Pinjam</span>
                <span class="detail-value">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Kembali</span>
                <span class="detail-value">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Sisa Waktu</span>
                <span class="detail-value">{{ $peminjaman->sisa_waktu_label }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Pengembalian</span>
                <span class="detail-value">{{ $peminjaman->pengembalian?->tanggal_pengembalian?->format('d M Y') ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Denda</span>
                <span class="detail-value">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</span>
                <span class="detail-subvalue">Tarif otomatis Rp {{ number_format(\App\Models\Peminjaman::DENDA_PER_HARI, 0, ',', '.') }} per hari keterlambatan.</span>
            </div>
            <div class="detail-row md:col-span-2">
                <span class="detail-label">Disetujui Oleh</span>
                <span class="detail-value">{{ $peminjaman->approvedBy?->name ?? '-' }}</span>
                <span class="detail-subvalue">{{ $peminjaman->approved_at?->format('d M Y H:i') ?? 'Belum ada persetujuan' }}</span>
            </div>
        </div>
    </div>
@endsection
