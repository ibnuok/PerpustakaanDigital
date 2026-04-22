@extends('layouts.portal')

@section('title', 'Detail Buku')
@section('page_heading', 'Detail Buku')
@section('page_description', 'Lihat informasi lengkap koleksi buku sebelum Anda memperbarui data atau mengelola transaksi peminjamannya.')

@section('page_actions')
    <a href="{{ route('admin.buku.edit', $buku) }}" class="btn-primary">Edit Buku</a>
    <a href="{{ route('admin.buku.index') }}" class="btn-secondary">Kembali</a>
@endsection

@section('content')
    <div class="form-shell">
        <div class="guide-card text-center">
            <span class="badge-soft">Cover Buku</span>
            <div class="mt-5">
                <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="cover-hero">
            </div>
            <div class="stack-list mt-5 text-left">
                <div class="stack-item">
                    <strong class="block">Kategori</strong>
                    <span class="detail-subvalue">{{ $buku->kategori?->nama_kategori ?? '-' }}</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Kondisi Buku</strong>
                    <span class="detail-subvalue">{{ ucwords(str_replace('_', ' ', $buku->kondisi)) }}</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Stok Tersedia</strong>
                    <span class="detail-subvalue">{{ $buku->stok }} buku</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <span class="badge-soft">Informasi Koleksi</span>
            <div class="detail-grid mt-5">
                <div class="detail-row">
                    <span class="detail-label">Judul</span>
                    <span class="detail-value">{{ $buku->judul }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Penulis</span>
                    <span class="detail-value">{{ $buku->penulis }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Penerbit</span>
                    <span class="detail-value">{{ $buku->penerbit }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tahun Terbit</span>
                    <span class="detail-value">{{ $buku->tahun_terbit }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ISBN</span>
                    <span class="detail-value">{{ $buku->isbn ?: '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jumlah Transaksi</span>
                    <span class="detail-value">{{ $buku->peminjamans()->count() }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
