@extends('layouts.portal')

@section('title', 'Dashboard Admin')
@section('page_heading', 'Dashboard Admin')
@section('page_description', 'Ringkasan aktivitas perpustakaan, koleksi buku, anggota, dan transaksi terbaru dalam tampilan modern.')

@section('page_actions')
    <a href="{{ route('admin.buku.create') }}" class="btn-secondary">Tambah Buku</a>
    <a href="{{ route('admin.peminjaman.create') }}" class="btn-primary">Buat Transaksi</a>
@endsection

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <p class="text-sm text-stone-500">Total Stok Buku</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalBuku }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-stone-500">Total Judul</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalJudul }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-stone-500">Anggota</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalAnggota }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-stone-500">Admin</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalAdmin }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-stone-500">Transaksi Aktif</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $peminjamanAktif }}</p>
        </div>
    </div>

    <div class="section-grid mt-8">
        <div class="table-wrap">
            <div class="table-head">
                <h2 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse ($peminjamanTerbaru as $item)
                            <tr class="bg-white">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $item->user->name }}</td>
                                <td class="px-6 py-4">{{ $item->buku->judul }}</td>
                                <td class="px-6 py-4">{!! $item->status_badge !!}</td>
                                <td class="px-6 py-4 text-stone-500">{{ $item->tanggal_pinjam->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-stone-500">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="soft-panel p-6">
                <h2 class="text-lg font-bold text-slate-900">Statistik Status</h2>
                <div class="mt-4 space-y-4 text-sm">
                    <div class="info-card" style="padding: 1rem 1.2rem;">
                        <div class="flex items-center justify-between">
                            <span>Menunggu</span>
                            <strong>{{ $statistikPerStatus['pending'] }}</strong>
                        </div>
                    </div>
                    <div class="info-card" style="padding: 1rem 1.2rem;">
                        <div class="flex items-center justify-between">
                            <span>Dipinjam</span>
                            <strong>{{ $statistikPerStatus['approved'] }}</strong>
                        </div>
                    </div>
                    <div class="info-card" style="padding: 1rem 1.2rem;">
                        <div class="flex items-center justify-between">
                            <span>Dikembalikan</span>
                            <strong>{{ $statistikPerStatus['returned'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="soft-panel p-6">
                <h2 class="text-lg font-bold text-slate-900">Buku Terpopuler</h2>
                <div class="mt-4 space-y-4">
                    @forelse ($bukuTerlaris as $buku)
                        <div class="info-card">
                            <p class="font-semibold text-slate-900">{{ $buku->judul }}</p>
                            <p class="mt-1 text-sm text-stone-500">{{ $buku->penulis }} - {{ $buku->peminjamans_count }} transaksi</p>
                        </div>
                    @empty
                        <p class="text-sm text-stone-500">Belum ada data buku terlaris.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
