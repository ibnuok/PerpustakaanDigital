@extends('layouts.portal')

@section('title', 'Transaksi')
@section('page_heading', 'Transaksi Perpustakaan')
@section('page_description', 'Pantau seluruh peminjaman dan pengembalian buku.')

@section('page_actions')
    <a href="{{ route('admin.peminjaman.create') }}" class="btn-primary">Tambah Transaksi</a>
@endsection

@section('content')
    {{-- STATISTIK --}}
    <div class="stats-grid">
        <div class="stat-card">
            <p>Total</p>
            <h2>{{ $totalPeminjaman }}</h2>
        </div>
        <div class="stat-card">
            <p>Dipinjam</p>
            <h2>{{ $approved }}</h2>
        </div>
        <div class="stat-card">
            <p>Dikembalikan</p>
            <h2>{{ $returned }}</h2>
        </div>
        <div class="stat-card">
            <p class="text-red-500">Terlambat</p>
            <h2 class="text-red-500">{{ $terlambat }}</h2>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-wrap mt-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Periode</th>
                    <th>Jumlah</th>
                    <th>Sisa Waktu</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjamans as $item)
                    <tr>
                        <td>
                            <b>{{ $item->user->name }}</b><br>
                            <small>{{ $item->user->email }}</small>
                        </td>

                        <td>
                            <b>{{ $item->buku->judul }}</b><br>
                            <small>{{ $item->buku->penulis }}</small>
                        </td>

                        {{-- 🔥 PERIODE + JAM --}}
                        <td>
                            <<td class="whitespace-nowrap">
                                <div class="text-sm font-medium">
                                    {{ $item->tanggal_pinjam?->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->tanggal_pinjam?->format('H:i') }} - 
                                    {{ $item->tanggal_kembali?->format('H:i') }}
                                </div>
                            </td>

                        {{-- 🔥 SISA WAKTU --}}
                        <td>
                            @php
                                $now = now();
                                $diff = $now->diffInSeconds($item->tanggal_kembali, false);

                                if ($diff > 0) {
                                    $h = floor($diff / 3600);
                                    $m = floor(($diff % 3600) / 60);
                                    $s = $diff % 60;
                                    $sisa = sprintf('%02d:%02d:%02d', $h, $m, $s);
                                } else {
                                    $sisa = 'TERLAMBAT';
                                }
                            @endphp

                            <b class="{{ $diff < 0 ? 'text-red-500' : '' }}">
                                {{ $sisa }}
                            </b>

                            {{-- DENDA --}}
                            @if ($item->pengembalian && $item->pengembalian->denda > 0)
                                <br>
                                <span class="text-red-500">
                                    Denda: Rp {{ number_format($item->pengembalian->denda) }}
                                </span>
                            @endif
                        </td>

                        {{-- 🔥 STATUS FIX --}}
                        <td>
                            @if ($item->status == 'pending')
                                <span class="badge bg-yellow-100 text-yellow-700">Menunggu</span>
                            @elseif ($item->status == 'dipinjam')
                                <span class="badge badge-approved">Dipinjam</span>
                            @else
                                <span class="badge badge-returned">Dikembalikan</span>
                            @endif
                        </td>

                        {{-- 🔥 AKSI FIX --}}
                        <td>
                            <div class="flex gap-2 justify-end">

                                {{-- 🔥 APPROVE BUTTON --}}
                                    @if ($item->status == 'pending')
                                        <form action="{{ route('admin.peminjaman.approve', $item) }}" method="POST">
                                            @csrf
                                            <button style="padding:8px 14px; background:#22c55e; color:white; border-radius:8px;">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                {{-- DETAIL --}}
                                <a href="{{ route('admin.peminjaman.show', $item) }}" class="btn-secondary">
                                    Detail
                                </a>

                                {{-- KEMBALIKAN --}}
                                @if ($item->status == 'dipinjam')
                                    <form action="{{ route('admin.peminjaman.return', $item) }}" method="POST">
                                        @csrf
                                        <button class="btn-primary">Kembalikan</button>
                                    </form>
                                @endif

                                {{-- HAPUS --}}
                                <form action="{{ route('admin.peminjaman.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger">Hapus</button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" align="center">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $peminjamans->links() }}
    </div>
@endsection