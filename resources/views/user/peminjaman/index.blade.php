@extends('layouts.portal')

@section('title', 'Ajukan Peminjaman')
@section('page_heading', 'Ajukan Peminjaman Buku')
@section('page_description', 'Lengkapi jumlah peminjaman.')

@section('page_actions')
    <a href="{{ route('user.bukus') }}" class="btn-secondary">Kembali ke Katalog</a>
@endsection

@section('content')

@if(isset($buku))

<form action="{{ route('user.peminjaman.store') }}" method="POST" class="form-shell">
    @csrf
    <input type="hidden" name="buku_id" value="{{ $buku->id }}">

    <div class="form-panel">
        <div class="detail-row">
            <span class="detail-label">Buku Dipilih</span>
            <span class="detail-value">{{ $buku->judul }}</span>
            <span class="detail-subvalue">
                {{ $buku->penulis }} • {{ $buku->penerbit }} • stok {{ $buku->stok }}
            </span>
        </div>

        <div class="form-grid">
            <div>
                <label class="field-label">Jumlah</label>
                <input type="number" name="jumlah" value="1" min="1" max="{{ $buku->stok }}" class="field-input" required>
            </div>

            <div>
                <label class="field-label">Durasi (hari)</label>
                <input type="number" name="durasi" value="1" min="1" class="field-input" required>
            </div>

            <!-- 🔥 AUTO WAKTU SEKARANG -->
            <div>
                <label class="field-label">Tanggal Pinjam</label>
                <input type="text" value="{{ now()->format('d M Y H:i') }}" class="field-input" readonly>
            </div>
        </div>

        <div class="action-row">
            <button type="submit" class="btn-primary">Kirim Pengajuan</button>
            <a href="{{ route('user.bukus') }}" class="btn-secondary">Batal</a>
        </div>
    </div>

    <aside class="guide-card text-center">
        <span class="badge-soft">Preview Buku</span>
        <div class="mt-5">
            <img src="{{ $buku->cover_url }}" class="cover-hero">
        </div>
    </aside>
</form>

@else

<div class="soft-panel p-10 text-center text-red-500">
    Data buku tidak ditemukan.
</div>

@endif

@endsection 