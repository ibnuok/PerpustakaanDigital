@extends('layouts.portal')

@section('title', 'Transaksi')
@section('page_heading', 'Transaksi Perpustakaan')
@section('page_description', 'Pantau seluruh peminjaman dan pengembalian buku.')

@section('page_actions')
    <a href="{{ route('admin.peminjaman.create') }}" class="btn-primary">
        + Tambah Transaksi
    </a>
@endsection

@section('content')

{{-- STATISTIK --}}
<div class="stats-grid mb-6">
    <div class="stat-card text-center">
        <p>Total</p>
        <h2>{{ $totalPeminjaman }}</h2>
    </div>
    <div class="stat-card text-center">
        <p>Dipinjam</p>
        <h2>{{ $approved }}</h2>
    </div>
    <div class="stat-card text-center">
        <p>Dikembalikan</p>
        <h2>{{ $returned }}</h2>
    </div>
    <div class="stat-card text-center">
        <p class="text-red-500">Terlambat</p>
        <h2 class="text-red-500">{{ $terlambat }}</h2>
    </div>
</div>

{{-- TABLE / MOBILE CARD --}}
<div class="table-wrap">
    <table class="table responsive-table">
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

            <tr>

                <td data-label="Peminjam">
                    <b>{{ $item->user->name }}</b><br>
                    <small>{{ $item->user->email }}</small>
                </td>

                <td data-label="Buku">
                    <b>{{ $item->buku->judul }}</b><br>
                    <small>{{ $item->buku->penulis }}</small>
                </td>

                <td data-label="Periode">
                    <div>
                        {{ $item->tanggal_pinjam?->format('d M Y') }}
                    </div>
                    <small>
                        {{ $item->tanggal_pinjam?->format('H:i') }} - 
                        {{ $item->tanggal_kembali?->format('H:i') }}
                    </small>
                </td>

                <td data-label="Jumlah">
                    {{ $item->jumlah }}
                </td>

                <td data-label="Sisa Waktu">
                    <b class="{{ $diff < 0 ? 'text-red-500' : '' }}">
                        {{ $sisa }}
                    </b>

                    @if ($item->pengembalian && $item->pengembalian->denda > 0)
                        <div class="text-red-500 text-xs">
                            Denda: Rp {{ number_format($item->pengembalian->denda) }}
                        </div>
                    @endif
                </td>

                <td data-label="Status">
                    @if ($item->status == 'pending')
                        <span class="badge bg-yellow-100 text-yellow-700">Menunggu</span>
                    @elseif ($item->status == 'dipinjam')
                        <span class="badge badge-approved">Dipinjam</span>
                    @else
                        <span class="badge badge-returned">Dikembalikan</span>
                    @endif
                </td>

                <td data-label="Aksi">
                    <div class="flex gap-2 flex-wrap justify-end">

                        @if ($item->status == 'pending')
                            <form action="{{ route('admin.peminjaman.approve', $item) }}" method="POST">
                                @csrf
                                <button class="action-btn success">✔</button>
                            </form>
                        @endif

                        <a href="{{ route('admin.peminjaman.show', $item) }}" 
                           class="action-btn info">🔍</a>

                        @if ($item->status == 'dipinjam')
                            <form action="{{ route('admin.peminjaman.return', $item) }}" method="POST">
                                @csrf
                                <button class="action-btn primary">⬅</button>
                            </form>
                        @endif

                        @if ($item->status == 'returned' && (!$item->pengembalian || !$item->pengembalian->ada_kerusakan))
                            <a href="{{ route('admin.peminjaman.check-damage', $item) }}" 
                               class="action-btn warning">⚠</a>
                        @endif

                        <form action="{{ route('admin.peminjaman.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="action-btn danger">✖</button>
                        </form>

                    </div>
                </td>

            </tr>

        @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada data</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $peminjamans->links() }}
</div>

@endsection
