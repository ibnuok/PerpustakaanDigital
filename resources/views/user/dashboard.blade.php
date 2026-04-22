@extends('layouts.portal')

@section('title', 'Dashboard User')
@section('page_heading', 'Dashboard User')
@section('page_description', 'Lihat ringkasan transaksi, buku tersedia, dan akses cepat untuk meminjam buku.')

@section('page_actions')
    <a href="{{ route('user.bukus') }}" class="btn-primary">Lihat Katalog Buku</a>
@endsection

@section('content')
    <div class="stats-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
        <div class="stat-card">
            <p class="text-sm text-stone-500">Pengajuan / Pinjaman Aktif</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $peminjamanAktif->count() }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-stone-500">Buku Tersedia</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $bukuTersedia }}</p>
        </div>
        <div class="stat-card">
            <p class="text-sm text-stone-500">Transaksi Selesai</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $peminjamanSelesai }}</p>
        </div>
    </div>

    <div class="section-grid mt-8">
        <div class="soft-panel p-6">
            <h2 class="text-lg font-bold text-slate-900">Menu Cepat</h2>
            <div class="mt-4 grid gap-4">
                <a href="{{ route('user.bukus') }}" class="info-card">
                    <p class="font-semibold text-slate-900">Cari Buku</p>
                    <p class="mt-2 text-sm leading-6 text-stone-500">Telusuri buku berdasarkan judul, penulis, kategori, dan kondisi.</p>
                </a>
                <a href="{{ route('user.peminjaman.index') }}" class="info-card">
                    <p class="font-semibold text-slate-900">Kelola Transaksi Saya</p>
                    <p class="mt-2 text-sm leading-6 text-stone-500">Pantau pengajuan, edit transaksi yang masih menunggu, atau kembalikan buku.</p>
                </a>
            </div>
        </div>

        <div class="table-wrap">
            <div class="table-head">
                <h2 class="text-lg font-bold text-slate-900">Riwayat Terbaru</h2>
            </div>
            <div class="divide-y divide-stone-200">
                @forelse ($peminjamanTerbaru as $item)
                    <div class="px-6 py-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $item->buku->judul }}</p>
                                <p class="mt-1 text-sm text-stone-500">
                                    {{ $item->tanggal_pinjam->format('d M Y') }} sampai {{ $item->tanggal_kembali->format('d M Y') }}
                                </p>
                            </div>
                            <div>{!! $item->status_badge !!}</div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-sm text-stone-500">
                        Belum ada transaksi. Mulai dari katalog buku untuk melakukan peminjaman.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
