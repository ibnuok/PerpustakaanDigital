@extends('layouts.portal')

@section('title', 'Tambah Kategori')
@section('page_heading', 'Tambah Kategori Baru')
@section('page_description', 'Buat kategori baru untuk membantu pengelompokan buku dan mempermudah pencarian di katalog.')

@section('page_actions')
    <a href="{{ route('admin.kategori.index') }}" class="btn-secondary">Kembali ke Kategori</a>
@endsection

@section('content')
    <form action="{{ route('admin.kategori.store') }}" method="POST" class="form-shell">
        @csrf

        <div class="form-panel">
            <div>
                <label class="field-label" for="nama_kategori">Nama Kategori</label>
                <input id="nama_kategori" type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" class="field-input" required>
            </div>
            <div>
                <label class="field-label" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="6" class="field-textarea">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="action-row">
                <button type="submit" class="btn-primary">Simpan Kategori</button>
                <a href="{{ route('admin.kategori.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Tips Kategori</span>
            <div class="stack-list mt-4">
                <div class="stack-item">
                    <strong class="block">Gunakan nama yang jelas</strong>
                    <span class="detail-subvalue">Contoh: Fiksi, Sejarah, Sains, Teknologi, atau Referensi.</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Deskripsi singkat saja</strong>
                    <span class="detail-subvalue">Tulis keterangan agar admin lain paham buku apa saja yang masuk di kategori ini.</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
