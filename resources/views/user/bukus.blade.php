@extends('layouts.portal')

@section('title', 'Katalog Buku')
@section('page_heading', '🔍 Katalog Buku')
@section('page_description', 'Cari dan filter buku yang tersedia sebelum mengajukan peminjaman.')

@section('content')
    <form method="GET" class="filter-panel">
        <div class="filter-grid">
            <input type="text" name="search" value="{{ request('search') }}" class="field-input" placeholder="🔍 Cari judul, penulis...">
            <select name="kategori_id" class="field-select">
                <option value="">Semua kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                @endforeach@extends('layouts.portal')

@section('title', 'Katalog Buku')
@section('page_heading', '🔍 Katalog Buku')
@section('page_description', 'Cari dan filter buku yang tersedia sebelum mengajukan peminjaman.')

@section('content')

<style>
    /* ── Filter Panel ── */
    .filter-panel {
        background: var(--card-bg, #fff);
        border-radius: 14px;
        border: 1px solid var(--border, #e5e7eb);
        padding: 1.25rem 1.5rem;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 0.75rem;
        align-items: center;
    }
    @media (max-width: 768px) {
        .filter-grid { grid-template-columns: 1fr; }
    }
    .field-input,
    .field-select {
        width: 100%;
        padding: 0.6rem 0.9rem;
        border: 1.5px solid var(--border, #e5e7eb);
        border-radius: 10px;
        font-size: 0.875rem;
        background: var(--input-bg, #f9fafb);
        color: var(--text, #111827);
        outline: none;
        transition: border-color 0.15s;
        font-family: inherit;
    }
    .field-input:focus,
    .field-select:focus {
        border-color: #7C55E8;
        background: #fff;
    }
    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* ── Book Grid ── */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-top: 1.75rem;
    }

    /* ── Book Card ── */
    .book-card {
        background: var(--card-bg, #fff);
        border: 1.5px solid var(--border, #e5e7eb);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .book-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(91, 61, 202, 0.10);
    }

    .book-cover {
        width: 100%;
        aspect-ratio: 2 / 3;
        overflow: hidden;
        background: #EDE9FC;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .book-cover-placeholder {
        font-size: 3rem;
        opacity: 0.4;
    }

    .book-body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 0.25rem;
    }
    .book-kategori {
        font-size: 0.7rem;
        font-weight: 700;
        color: #7C55E8;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .book-judul {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text, #111827);
        line-height: 1.4;
        margin: 0.2rem 0 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .book-meta {
        font-size: 0.78rem;
        color: var(--text-light, #6b7280);
        margin-top: 0.35rem;
        line-height: 1.4;
    }
    .book-kondisi {
        font-size: 0.75rem;
        color: var(--text-light, #6b7280);
    }

    .book-footer {
        padding: 0.75rem 1rem;
        border-top: 1px solid var(--border, #e5e7eb);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .stok-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
    }
    .stok-ada   { background: #d1fae5; color: #065f46; }
    .stok-habis { background: #fee2e2; color: #991b1b; }

    .btn-pinjam {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        font-family: inherit;
        text-decoration: none;
        transition: background 0.15s;
        white-space: nowrap;
        display: inline-block;
        text-align: center;
    }
    .btn-pinjam-on  { background: #7C55E8; color: #fff; }
    .btn-pinjam-on:hover  { background: #5B3DCA; }
    .btn-pinjam-off {
        background: #f3f4f6; color: #9ca3af;
        cursor: not-allowed; pointer-events: none;
    }

    /* ── Empty State ── */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-light, #9ca3af);
        background: var(--card-bg, #fff);
        border-radius: 14px;
        border: 1.5px dashed var(--border, #e5e7eb);
    }
    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
    .empty-state p { font-size: 0.9rem; margin: 0; }

    /* ── Pagination spacing ── */
    .pagination-wrap { margin-top: 1.75rem; }
</style>

{{-- ── Filter ── --}}
<form method="GET" action="{{ route('user.bukus') }}" class="filter-panel">
    <div class="filter-grid">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            class="field-input"
            placeholder="🔍 Cari judul, penulis..."
        >
        <select name="kategori_id" class="field-select">
            <option value="">Semua Kategori</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>
                    {{ $kategori->nama_kategori }}
                </option>
            @endforeach
        </select>
        <select name="kondisi" class="field-select">
            <option value="">Semua Kondisi</option>
            <option value="baik"         @selected(request('kondisi') === 'baik')>Baik</option>
            <option value="rusak_ringan" @selected(request('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
            <option value="rusak_berat"  @selected(request('kondisi') === 'rusak_berat')>Rusak Berat</option>
        </select>
        <div class="filter-actions">
            <button type="submit" class="action-btn action-btn-primary">Filter</button>
            <a href="{{ route('user.bukus') }}" class="action-btn action-btn-secondary">Reset</a>
        </div>
    </div>
</form>

{{-- ── Grid Buku ── --}}
<div class="books-grid">
    @forelse ($bukus as $buku)
        <div class="book-card">

            {{-- Cover --}}
            <div class="book-cover">
                @if ($buku->image)
                    <img
                        src="{{ asset('images/' . $buku->image) }}"
                        alt="Cover {{ $buku->judul }}"
                    >
                @else
                    <div class="book-cover-placeholder">📚</div>
                @endif
            </div>

            {{-- Info --}}
            <div class="book-body">
                <p class="book-kategori">{{ $buku->kategori?->nama_kategori ?? 'Tanpa Kategori' }}</p>
                <h2 class="book-judul">{{ $buku->judul }}</h2>
                <p class="book-meta">
                    {{ $buku->penulis ?? 'Unknown' }}
                    @if($buku->penerbit) · {{ $buku->penerbit }} @endif
                    @if($buku->tahun_terbit) ({{ $buku->tahun_terbit }}) @endif
                </p>
                <p class="book-kondisi">Kondisi: {{ str_replace('_', ' ', ucfirst($buku->kondisi)) }}</p>
            </div>

            {{-- Footer --}}
            <div class="book-footer">
                <span class="stok-badge {{ $buku->stok > 0 ? 'stok-ada' : 'stok-habis' }}">
                    {{ $buku->stok > 0 ? 'Stok ' . $buku->stok : 'Habis' }}
                </span>
                @if ($buku->stok > 0)
                    <a
                        href="{{ route('user.peminjaman.create', ['buku_id' => $buku->id]) }}"
                        class="btn-pinjam btn-pinjam-on"
                    >📚 Pinjam</a>
                @else
                    <span class="btn-pinjam btn-pinjam-off">Tidak Tersedia</span>
                @endif
            </div>

        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <p>Tidak ada buku yang sesuai dengan filter.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="pagination-wrap">
    {{ $bukus->links() }}
</div>

@endsection
            </select>
            <select name="kondisi" class="field-select">
                <option value="">Semua kondisi</option>
                <option value="baik" @selected(request('kondisi') === 'baik')>Baik</option>
                <option value="rusak_ringan" @selected(request('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                <option value="rusak_berat" @selected(request('kondisi') === 'rusak_berat')>Rusak Berat</option>
            </select>
            <div style="display: flex; gap: 0.5rem;">
                <button class="action-btn action-btn-primary" style="flex: 1;">🔍 Filter</button>
                <a href="{{ route('user.bukus') }}" class="action-btn action-btn-secondary" style="flex: 1; text-align: center; text-decoration: none;">Reset</a>
            </div>
        </div>
    </form> 

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        @forelse ($bukus as $buku)
            <div class="card">
                <div style="width: 100%; aspect-ratio: 9/12; overflow: hidden; border-radius: 12px 12px 0 0;">
                   
                <img src="{{ $buku->image ? asset('images/'.$buku->image) : 'https://via.placeholder.com/280x420?text=Book' }}" 
     alt="Cover {{ $buku->judul }}"
     style="width: 100%; height: 100%; object-fit: cover;"> 
                <div class="card-body">
                 
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <p style="margin: 0; font-size: 0.8rem; color: var(--text-light); font-weight: 600;">{{ $buku->kategori?->nama_kategori ?? 'Tanpa kategori' }}</p>
                            <h2 style="margin: 0.5rem 0 0; font-size: 1.1rem; font-weight: 700; color: var(--text);">{{ $buku->judul }}</h2>
                        </div>
                        <span class="badge badge-approved" style="white-space: nowrap;">stok {{ $buku->stok }}</span>
                    </div>
                    <p style="margin: 1rem 0 0; font-size: 0.9rem; color: var(--text-light); line-height: 1.5;">{{ $buku->penulis ?? 'Unknown' }} - {{ $buku->penerbit ?? '-' }} ({{ $buku->tahun_terbit ?? '-' }})</p>
                    <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: var(--text-light);">Kondisi: {{ str_replace('_', ' ', ucfirst($buku->kondisi)) }}</p>
                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        <a href="{{ route('user.peminjaman.create', ['buku_id' => $buku->id]) }}" class="action-btn action-btn-primary" style="flex: 1; text-align: center; text-decoration: none;">📚 Pinjam</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card" style="grid-column: 1 / -1;">
                <div class="card-body" style="text-align: center; padding: 3rem;">
                    <p style="color: var(--text-light);">Tidak ada buku yang sesuai dengan filter.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 2rem;">{{ $bukus->links() }}</div>
@endsection
