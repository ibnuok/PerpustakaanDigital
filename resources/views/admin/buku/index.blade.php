@extends('layouts.portal')

@section('title', 'Data Buku')
@section('page_heading', '📚 Data Buku')
@section('page_description', 'Kelola koleksi buku perpustakaan, filter berdasarkan kategori dan kondisi.')

@section('page_actions')
    <a href="{{ route('admin.buku.create') }}" class="action-btn action-btn-primary">+ Tambah Buku</a>
@endsection

@section('content')
    <form method="GET" class="filter-panel">
        <div class="filter-grid">
            <input type="text" name="search" value="{{ request('search') }}" class="field-input" placeholder="🔍 Cari buku...">
            <select name="kategori_id" class="field-select">
                <option value="">Semua kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
            <select name="kondisi" class="field-select">
                <option value="">Semua kondisi</option>
                <option value="baik" @selected(request('kondisi') === 'baik')>Baik</option>
                <option value="rusak_ringan" @selected(request('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                <option value="rusak_berat" @selected(request('kondisi') === 'rusak_berat')>Rusak Berat</option>
            </select>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button class="action-btn action-btn-primary" style="flex: 1;">🔍 Filter</button>
                <a href="{{ route('admin.buku.index') }}" class="action-btn action-btn-secondary" style="flex: 1; text-align: center; text-decoration: none;">Reset</a>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div style="overflow-x: auto;">
                <table class="table responsive-table">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Kategori</th>
                            <th>ISBN</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukus as $buku)
                            <tr>
                                <td data-label="Buku">
                                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                                       <img src="{{ $buku->image && file_exists(public_path('images/'.$buku->image)) 
    ? asset('images/'.$buku->image) 
    : 'https://via.placeholder.com/50x75?text=Book' }}" 
    alt="Cover {{ $buku->judul }}" 
    style="width: 50px; height: 75px; object-fit: cover; border-radius: 6px; background: var(--bg-light);">
                                            <p style="margin: 0; font-weight: 600; color: var(--text);">{{ $buku->judul }}</p>
                                            <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: var(--text-light);">{{ $buku->penulis ?? 'Unknown' }}</p>
                                            <p style="margin: 0.25rem 0 0; font-size: 0.8rem; color: var(--text-light);">{{ $buku->penerbit ?? '-' }} ({{ $buku->tahun_terbit ?? '-' }})</p>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Kategori">{{ $buku->kategori?->nama_kategori ?? '-' }}</td>
                                <td data-label="ISBN">{{ $buku->isbn ?: '-' }}</td>   
                                <td data-label="Stok"><strong>{{ $buku->stok }}</strong></td>
                                <td data-label="Kondisi"><span class="badge badge-{{ $buku->kondisi === 'baik' ? 'approved' : 'pending' }}">{{ str_replace('_', ' ', ucfirst($buku->kondisi)) }}</span></td>
                                <td data-label="Aksi" style="text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                        <a href="{{ route('admin.buku.edit', $buku) }}" style="padding: 0.5rem 1rem; background: var(--primary); color: white; border-radius: 6px; border: none; cursor: pointer; text-decoration: none; font-weight: 600; font-size: 0.85rem;">Edit</a>
                                        <form action="{{ route('admin.buku.destroy', $buku) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button style="padding: 0.5rem 1rem; background: var(--danger); color: white; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 0.85rem;">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-light);">Belum ada data buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem;">{{ $bukus->links() }}</div>
@endsection
