@extends('layouts.portal')

@section('title', 'Data Buku')
@section('page_heading', 'Data Buku')
@section('page_description', 'Kelola koleksi buku perpustakaan, filter berdasarkan kategori dan kondisi, lalu cari dengan cepat.')

@section('page_actions')
    <a href="{{ route('admin.buku.create') }}" class="btn-primary">Tambah Buku</a>
@endsection

@section('content')
    <form method="GET" class="filter-panel">
        <div class="filter-grid">
            <input type="text" name="search" value="{{ request('search') }}" class="field-input" placeholder="Cari judul, penulis, penerbit, ISBN">
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
            <div class="flex gap-3">
                <button class="btn-primary flex-1">Filter</button>
                <a href="{{ route('admin.buku.index') }}" class="btn-secondary flex-1">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-wrap mt-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">ISBN</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4">Kondisi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse ($bukus as $buku)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $buku->cover_url }}" alt="Cover {{ $buku->judul }}" class="mini-cover">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $buku->judul }}</p>
                                        <p class="mt-1 text-stone-500">{{ $buku->penulis }} - {{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-stone-600">{{ $buku->kategori?->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-4 text-stone-600">{{ $buku->isbn ?: '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $buku->stok }}</td>
                            <td class="px-6 py-4 text-stone-600">{{ str_replace('_', ' ', ucfirst($buku->kondisi)) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.buku.show', $buku) }}" class="btn-secondary">Detail</a>
                                    <a href="{{ route('admin.buku.edit', $buku) }}" class="btn-secondary">Edit</a>
                                    <form action="{{ route('admin.buku.destroy', $buku) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-stone-500">Belum ada data buku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $bukus->links() }}</div>
@endsection
