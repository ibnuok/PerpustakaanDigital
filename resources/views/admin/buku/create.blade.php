@extends('layouts.portal')

@section('title', 'Tambah Buku')
@section('page_heading', 'Tambah Buku Baru')
@section('page_description', 'Masukkan data buku baru agar koleksi perpustakaan selalu lengkap, rapi, dan siap dipinjam.')

@section('page_actions')
    <a href="{{ route('admin.buku.index') }}" class="btn-secondary">Kembali ke Data Buku</a>
@endsection

@section('content')
    <div class="page-intro">
        <span class="badge-soft">Form Koleksi</span>
        <p class="mt-4 text-sm leading-7" style="color: var(--muted);">
            Isi informasi buku secara lengkap agar anggota lebih mudah menemukan judul, kategori, dan kondisi buku di katalog.
        </p>
    </div>

    <form action="{{ route('admin.buku.store') }}" method="POST" class="form-shell">
        @csrf

        <div class="form-panel">
            <div class="form-grid">
                <div>
                    <label class="field-label" for="judul">Judul Buku</label>
                    <input id="judul" type="text" name="judul" value="{{ old('judul') }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="penulis">Penulis</label>
                    <input id="penulis" type="text" name="penulis" value="{{ old('penulis') }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="penerbit">Penerbit</label>
                    <input id="penerbit" type="text" name="penerbit" value="{{ old('penerbit') }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="tahun_terbit">Tahun Terbit</label>
                    <input id="tahun_terbit" type="text" name="tahun_terbit" value="{{ old('tahun_terbit') }}" class="field-input" maxlength="4" required>
                </div>
                <div>
                    <label class="field-label" for="isbn">ISBN</label>
                    <input id="isbn" type="text" name="isbn" value="{{ old('isbn') }}" class="field-input">
                </div>
                <div>
                    <label class="field-label" for="stok">Stok</label>
                    <input id="stok" type="number" name="stok" value="{{ old('stok', 1) }}" min="1" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="field-select" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label" for="kondisi">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="field-select" required>
                        <option value="">Pilih kondisi</option>
                        <option value="baik" @selected(old('kondisi') === 'baik')>Baik</option>
                        <option value="rusak_ringan" @selected(old('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                        <option value="rusak_berat" @selected(old('kondisi') === 'rusak_berat')>Rusak Berat</option>
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-primary">Simpan Buku</button>
                <a href="{{ route('admin.buku.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Panduan Singkat</span>
            <div class="stack-list mt-4">
                <div class="stack-item">
                    <strong class="block">1. Lengkapi identitas buku</strong>
                    <span class="detail-subvalue">Masukkan judul, penulis, penerbit, dan tahun terbit agar data katalog tampil rapi.</span>
                </div>
                <div class="stack-item">
                    <strong class="block">2. Pilih kategori yang tepat</strong>
                    <span class="detail-subvalue">Kategori membantu anggota menemukan buku lebih cepat saat melakukan pencarian.</span>
                </div>
                <div class="stack-item">
                    <strong class="block">3. Atur stok dan kondisi</strong>
                    <span class="detail-subvalue">Stok dipakai saat transaksi, sedangkan kondisi memudahkan pemantauan kualitas koleksi.</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
