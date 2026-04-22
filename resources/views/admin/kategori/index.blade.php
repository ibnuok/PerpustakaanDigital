@extends('layouts.portal')

@section('title', 'Kategori Buku')
@section('page_heading', 'Kategori Buku')
@section('page_description', 'Kelola kategori buku untuk memudahkan pencarian dan pengelompokan koleksi.')

@section('page_actions')
    <a href="{{ route('admin.kategori.create') }}" class="btn-primary">Tambah Kategori</a>
@endsection

@section('content')
    <form method="GET" class="grid gap-4 rounded-3xl border border-stone-200 bg-stone-50 p-5 md:grid-cols-[1fr_auto_auto]">
        <input type="text" name="search" value="{{ request('search') }}" class="field-input" placeholder="Cari nama kategori">
        <button class="btn-primary">Cari</button>
        <a href="{{ route('admin.kategori.index') }}" class="btn-secondary">Reset</a>
    </form>

    <div class="mt-6 overflow-hidden rounded-3xl border border-stone-200">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse ($kategoris as $kategori)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $kategori->nama_kategori }}</td>
                            <td class="px-6 py-4 text-stone-600">{{ $kategori->deskripsi ?: '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn-secondary !px-4 !py-2">Edit</a>
                                    <form action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger !px-4 !py-2">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-stone-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $kategoris->links() }}</div>
@endsection
