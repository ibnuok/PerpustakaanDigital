@extends('layouts.portal')

@section('title', 'Edit Pengajuan')
@section('page_heading', 'Edit Pengajuan Peminjaman')
@section('page_description', 'Perbarui jumlah atau tanggal peminjaman Anda selama pengajuan masih menunggu persetujuan admin.')

@section('page_actions')
    <a href="{{ route('user.peminjaman.index') }}" class="btn-secondary">Kembali ke Transaksi</a>
@endsection

@section('content')
    <form action="{{ route('user.peminjaman.update', $peminjaman) }}" method="POST" class="form-shell">
        @csrf
        @method('PUT')

        <div class="form-panel">
            <div class="detail-row">
                <span class="detail-label">Buku Dipilih</span>
                <span class="detail-value">{{ $buku->judul }}</span>
                <span class="detail-subvalue">{{ $buku->penulis }} • stok {{ $buku->stok }}</span>
            </div>

            <div class="form-grid">
                <div>
                    <label class="field-label" for="jumlah">Jumlah</label>
                    <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah', $peminjaman->jumlah) }}" min="1" class="field-input" required>
                </div>

                <div>
                    <label class="field-label" for="tanggal_pinjam">Tanggal Pinjam</label>
                    <input id="tanggal_pinjam" type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->toDateString()) }}" class="field-input" required>
                </div>

                <div>
                    <label class="field-label" for="jam_pinjam">Jam Pinjam (HH:MM)</label>
                    <input id="jam_pinjam" type="time" name="jam_pinjam" value="{{ old('jam_pinjam', $peminjaman->tanggal_pinjam->format('H:i')) }}" class="field-input" required>
                </div>

                <div>
                    <label class="field-label" for="tanggal_kembali">Tanggal Kembali</label>
                    <input id="tanggal_kembali" type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali->toDateString()) }}" class="field-input" required>
                </div>

                <div>
                    <label class="field-label" for="jam_kembali">Jam Kembali (HH:MM)</label>
                    <input id="jam_kembali" type="time" name="jam_kembali" value="{{ old('jam_kembali', $peminjaman->tanggal_kembali->format('H:i')) }}" class="field-input" required>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-primary">Update Pengajuan</button>
                <a href="{{ route('user.peminjaman.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Status Pengajuan</span>
            <div class="stack-list mt-4">
                <div class="stack-item">
                    <strong class="block">Status Saat Ini</strong>
                    <span class="detail-subvalue">{{ ucfirst($peminjaman->status) }}</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Buku</strong>
                    <span class="detail-subvalue">{{ $buku->judul }}</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
