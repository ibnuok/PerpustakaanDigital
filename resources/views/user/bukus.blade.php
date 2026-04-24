@extends('layouts.portal')

@section('title', 'Katalog Buku')
@section('page_heading', '🔍 Katalog Buku')
@section('page_description', 'Cari dan filter buku yang tersedia sebelum mengajukan peminjaman.')

@section('content')

<style>
/* FILTER */
.filter-panel {
    background: white;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    padding: 1.25rem;
}

.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 0.75rem;
}

@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
}

/* GRID */
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.25rem;
    margin-top: 1.5rem;
}

/* CARD */
.book-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: 0.2s;
}

.book-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* COVER */
.book-cover {
    width: 100%;
    aspect-ratio: 2/3;
    overflow: hidden;
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* BODY */
.book-body {
    padding: 1rem;
    flex: 1;
}

.book-judul {
    font-weight: 700;
    font-size: 0.95rem;
    margin: 5px 0;
}

/* FOOTER */
.book-footer {
    padding: 0.8rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* BADGE */
.stok {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
}

.stok-ada {
    background: #d1fae5;
    color: #065f46;
}

.stok-habis {
    background: #fee2e2;
    color: #991b1b;
}

/* BUTTON */
.btn-pinjam {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
}

.btn-on {
    background: #6366f1;
    color: white;
}

.btn-on:hover {
    background: #4f46e5;
}

.btn-off {
    background: #e5e7eb;
    color: #9ca3af;
    pointer-events: none;
}

/* EMPTY */
.empty {
    grid-column: 1/-1;
    text-align: center;
    padding: 3rem;
    color: #9ca3af;
}
</style>

{{-- FILTER --}}
<form method="GET" action="{{ route('user.bukus') }}" class="filter-panel">
    <div class="filter-grid">
        <input type="text" name="search" value="{{ request('search') }}"
               class="field-input" placeholder="🔍 Cari buku...">

        <select name="kategori_id" class="field-select">
            <option value="">Semua kategori</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>
                    {{ $kategori->nama_kategori }}
                </option>
            @endforeach
        </select>

        <select name="kondisi" class="field-select">
            <option value="">Semua kondisi</option>
            <option value="baik" @selected(request('kondisi')=='baik')>Baik</option>
            <option value="rusak_ringan" @selected(request('kondisi')=='rusak_ringan')>Rusak Ringan</option>
            <option value="rusak_berat" @selected(request('kondisi')=='rusak_berat')>Rusak Berat</option>
        </select>

        <div style="display:flex; gap:5px; flex-wrap:wrap;">
            <button class="action-btn action-btn-primary">Filter</button>
            <a href="{{ route('user.bukus') }}" class="action-btn action-btn-secondary">Reset</a>
        </div>
    </div>
</form>

{{-- GRID --}}
<div class="books-grid">
@forelse ($bukus as $buku)
    <div class="book-card">

        {{-- COVER --}}
        <div class="book-cover">
            <img src="{{ $buku->image ? asset('images/'.$buku->image) : 'https://via.placeholder.com/300x400' }}">
        </div>

        {{-- BODY --}}
        <div class="book-body">
            <small>{{ $buku->kategori?->nama_kategori ?? '-' }}</small>
            <div class="book-judul">{{ $buku->judul }}</div>
            <small>{{ $buku->penulis ?? '-' }}</small><br>
            <small>Kondisi: {{ str_replace('_',' ',$buku->kondisi) }}</small>
        </div>

        {{-- FOOTER --}}
        <div class="book-footer">
            <span class="stok {{ $buku->stok > 0 ? 'stok-ada' : 'stok-habis' }}">
                {{ $buku->stok > 0 ? 'Stok '.$buku->stok : 'Habis' }}
            </span>

            @if ($buku->stok > 0)
                <a href="{{ route('user.peminjaman.create') }}?buku_id={{ $buku->id }}"
                   class="btn-pinjam btn-on">Pinjam</a>
            @else
                <span class="btn-pinjam btn-off">Tidak ada</span>
            @endif
        </div>

    </div>
@empty
    <div class="empty">
        📭 Tidak ada buku ditemukan
    </div>
@endforelse
</div>

<div style="margin-top:20px;">
    {{ $bukus->links() }}
</div>

@endsection
