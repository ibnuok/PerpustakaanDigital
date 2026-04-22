@extends('layouts.portal')

@section('title', 'Edit Transaksi')
@section('page_heading', 'Edit Transaksi')
@section('page_description', 'Perbarui transaksi yang masih menunggu agar data peminjaman tetap akurat sebelum disetujui.')

@section('page_actions')
    <a href="{{ route('admin.peminjaman.show', $peminjaman) }}" class="btn-secondary">Lihat Detail</a>
@endsection

@section('content')
    <form action="{{ route('admin.peminjaman.update', $peminjaman) }}" method="POST" class="form-shell">
        @csrf
        @method('PUT')

        <div class="form-panel">
            <div class="form-grid">
                <div>
                    <label class="field-label" for="user_id">Anggota</label>
                    <select id="user_id" name="user_id" class="field-select" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $peminjaman->user_id) == $user->id)>{{ $user->name }} - {{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label" for="buku_id">Buku</label>
                    <select id="buku_id" name="buku_id" class="field-select" required>
                        @foreach ($bukus as $buku)
                            <option value="{{ $buku->id }}" @selected(old('buku_id', $peminjaman->buku_id) == $buku->id)>{{ $buku->judul }} - stok {{ $buku->stok }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label" for="jumlah">Jumlah</label>
                    <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah', $peminjaman->jumlah) }}" min="1" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="tanggal_pinjam">Tanggal Pinjam</label>
                    <input id="tanggal_pinjam" type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->toDateString()) }}" class="field-input" required>
                </div>
                <div class="md:col-span-2">
                    <label class="field-label" for="tanggal_kembali">Tanggal Kembali</label>
                    <input id="tanggal_kembali" type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali->toDateString()) }}" class="field-input" required>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-primary">Update Transaksi</button>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Status Saat Ini</span>
            <div class="stack-list mt-4">
                <div class="stack-item">
                    <strong class="block">{{ $peminjaman->user->name }}</strong>
                    <span class="detail-subvalue">{{ $peminjaman->buku->judul }}</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Status</strong>
                    <span class="detail-subvalue">{{ ucfirst($peminjaman->status) }}</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
