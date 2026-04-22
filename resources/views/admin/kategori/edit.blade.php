@extends('layouts.portal')

@section('title', 'Edit Kategori')
@section('page_heading', 'Edit Kategori')
@section('page_description', 'Perbarui nama atau deskripsi kategori agar struktur katalog perpustakaan tetap rapi dan mudah dipahami.')

@section('page_actions')
    <a href="{{ route('admin.kategori.index') }}" class="btn-secondary">Kembali</a>
@endsection

@section('content')
    <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST" class="form-shell">
        @csrf
        @method('PUT')

        <div class="form-panel">
            <div>
                <label class="field-label" for="nama_kategori">Nama Kategori</label>
                <input id="nama_kategori" type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" class="field-input" required>
            </div>
            <div>
                <label class="field-label" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="6" class="field-textarea">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
            </div>
            <div class="action-row">
                <button type="submit" class="btn-primary">Update Kategori</button>
                <a href="{{ route('admin.kategori.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Preview Kategori</span>
            <div class="stack-list mt-4">
                <div class="stack-item">
                    <strong class="block">{{ $kategori->nama_kategori }}</strong>
                    <span class="detail-subvalue">{{ $kategori->deskripsi ?: 'Belum ada deskripsi untuk kategori ini.' }}</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
