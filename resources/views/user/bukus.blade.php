@extends('layouts.portal')

@section('title', 'Katalog Buku')
@section('page_heading', 'Katalog Buku')
@section('page_description', 'Cari dan filter buku yang tersedia sebelum mengajukan peminjaman.')

@section('content')
    <form method="GET" class="grid gap-4 rounded-3xl border border-stone-200 bg-stone-50 p-5 md:grid-cols-4">
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
            <a href="{{ route('user.bukus') }}" class="btn-secondary flex-1">Reset</a>
        </div>
    </form>

    <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($bukus as $buku)
            <div class="soft-panel katalog-card">
                <div class="katalog-cover-wrap">
                    <img src="{{ $buku->cover_url }}" alt="Cover {{ $buku->judul }}" class="katalog-cover">
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-stone-500">{{ $buku->kategori?->nama_kategori ?? 'Tanpa kategori' }}</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $buku->judul }}</h2>
                        </div>
                        <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-600">
                            stok {{ $buku->stok }}
                        </span>
                    </div>
                    <p class="mt-3 text-sm leading-7 text-stone-600">{{ $buku->penulis }} - {{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</p>
                    <p class="mt-2 text-sm text-stone-500">Kondisi: {{ str_replace('_', ' ', ucfirst($buku->kondisi)) }}</p>
                    <div class="mt-5 flex gap-3">
                        <a href="{{ route('user.peminjaman.create', ['buku_id' => $buku->id]) }}" class="btn-primary flex-1">Pinjam Buku</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="soft-panel p-8 text-center text-stone-500 md:col-span-2 xl:col-span-3">
                Tidak ada buku yang sesuai dengan filter.
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $bukus->links() }}</div>
@endsection
