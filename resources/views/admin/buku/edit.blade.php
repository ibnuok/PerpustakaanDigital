@extends('layouts.portal')

@section('title', 'Edit Buku')
@section('page_heading', 'Edit Data Buku')
@section('page_description', 'Perbarui informasi koleksi agar katalog, stok, dan kondisi buku tetap sesuai dengan keadaan terbaru.')

@section('page_actions')
    <a href="{{ route('admin.buku.show', $buku) }}" class="btn-secondary">Lihat Detail</a>
@endsection

@section('content')
    <form action="{{ route('admin.buku.update', $buku) }}" method="POST" class="form-shell">
        @csrf
        @method('PUT')

        <div class="form-panel">
            <div class="form-grid">
                <div>
                    <label class="field-label" for="judul">Judul Buku</label>
                    <input id="judul" type="text" name="judul" value="{{ old('judul', $buku->judul) }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="penulis">Penulis</label>
                    <input id="penulis" type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="penerbit">Penerbit</label>
                    <input id="penerbit" type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="tahun_terbit">Tahun Terbit</label>
                    <input id="tahun_terbit" type="text" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" class="field-input" maxlength="4" required>
                </div>
                <div>
                    <label class="field-label" for="isbn">ISBN</label>
                    <input id="isbn" type="text" name="isbn" value="{{ old('isbn', $buku->isbn) }}" class="field-input">
                </div>
                <div>
                    <label class="field-label" for="stok">Stok</label>
                    <input id="stok" type="number" name="stok" value="{{ old('stok', $buku->stok) }}" min="1" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="field-select" required>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected(old('kategori_id', $buku->kategori_id) == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label" for="kondisi">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="field-select" required>
                        <option value="baik" @selected(old('kondisi', $buku->kondisi) === 'baik')>Baik</option>
                        <option value="rusak_ringan" @selected(old('kondisi', $buku->kondisi) === 'rusak_ringan')>Rusak Ringan</option>
                        <option value="rusak_berat" @selected(old('kondisi', $buku->kondisi) === 'rusak_berat')>Rusak Berat</option>
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-primary">Update Buku</button>
                <a href="{{ route('admin.buku.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Ringkasan Koleksi</span>
            <div class="text-center mt-4">
                <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="cover-hero">
            </div>
            <div class="stack-list mt-5">
                <div class="stack-item">
                    <strong class="block">{{ $buku->judul }}</strong>
                    <span class="detail-subvalue">{{ $buku->penulis }} • {{ $buku->penerbit }}</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Kategori Saat Ini</strong>
                    <span class="detail-subvalue">{{ $buku->kategori?->nama_kategori ?? '-' }}</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Status Koleksi</strong>
                    <span class="detail-subvalue">Stok {{ $buku->stok }} buku • kondisi {{ str_replace('_', ' ', $buku->kondisi) }}</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
