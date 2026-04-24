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
                    {{-- PEMINJAM --}}
                    <td>
                        <b>{{ $item->user->name }}</b><br>
                        <small>{{ $item->user->email }}</small>
                    </td>

                    {{-- BUKU --}}
                    <td>
                        <b>{{ $item->buku->judul }}</b><br>
                        <small>{{ $item->buku->penulis }}</small>
                    </td>

                    {{-- PERIODE --}}
                    <td class="whitespace-nowrap">
                        <div class="text-sm font-medium">
                            {{ $item->tanggal_pinjam?->format('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $item->tanggal_pinjam?->format('H:i') }} - 
                            {{ $item->tanggal_kembali?->format('H:i') }}
                        </div>
                    </td>

                    {{-- JUMLAH --}}
                    <td>{{ $item->jumlah }}</td>

                    {{-- SISA WAKTU --}}
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
                            <span class="text-red-500 text-sm">
                                Denda: Rp {{ number_format($item->pengembalian->denda) }}
                            </span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if ($item->status == 'pending')
                            <span class="badge bg-yellow-100 text-yellow-700">Menunggu</span>
                        @elseif ($item->status == 'dipinjam')
                            <span class="badge badge-approved">Dipinjam</span>
                        @else
                            <span class="badge badge-returned">Dikembalikan</span>
                        @endif
                    </td>

                    {{-- AKSI MODERN --}}
                    <td>
                        <div class="flex gap-2 justify-end flex-wrap">

                            {{-- APPROVE --}}
                            @if ($item->status == 'pending')
                                <form action="{{ route('admin.peminjaman.approve', $item) }}" method="POST">
                                    @csrf
                                    <button class="action-btn success" title="Approve">✔</button>
                                </form>
                            @endif

                            {{-- DETAIL --}}
                            <a href="{{ route('admin.peminjaman.show', $item) }}" 
                               class="action-btn info" title="Detail">🔍</a>

                            {{-- KEMBALIKAN --}}
                            @if ($item->status == 'dipinjam')
                                <form action="{{ route('admin.peminjaman.return', $item) }}" method="POST">
                                    @csrf
                                    <button class="action-btn primary" title="Kembalikan">⬅</button>
                                </form>
                            @endif

                            {{-- PERIKSA --}}
                            @if ($item->status == 'returned' && (!$item->pengembalian || !$item->pengembalian->ada_kerusakan))
                                <a href="{{ route('admin.peminjaman.check-damage', $item) }}" 
                                   class="action-btn warning" title="Periksa">⚠</a>
                            @endif

                            {{-- HAPUS --}}
                            <form action="{{ route('admin.peminjaman.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn danger" title="Hapus">✖</button>
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