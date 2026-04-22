@extends('layouts.portal')

@section('title', 'Transaksi Saya')
@section('page_heading', 'Transaksi Saya')
@section('page_description', 'Pantau status peminjaman, edit pengajuan yang masih menunggu, dan lakukan pengembalian buku.')

@section('page_actions')
    <a href="{{ route('user.bukus') }}" class="btn-primary">Pinjam Buku Baru</a>
@endsection

@section('content')
    <form method="GET" class="grid gap-4 rounded-3xl border border-stone-200 bg-stone-50 p-5 md:grid-cols-5">
        <input type="text" name="search" value="{{ request('search') }}" class="field-input" placeholder="Cari judul / ISBN / penulis">
        <select name="status" class="field-select">
            <option value="">Semua status</option>
            <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
            <option value="approved" @selected(request('status') === 'approved')>Dipinjam</option>
            <option value="returned" @selected(request('status') === 'returned')>Dikembalikan</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="field-input">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="field-input">
        <div class="flex gap-3">
            <button class="btn-primary flex-1">Filter</button>
            <a href="{{ route('user.peminjaman.index') }}" class="btn-secondary flex-1">Reset</a>
        </div>
    </form>

    <div class="mt-6 space-y-4">
        @forelse ($peminjamans as $item)
            <div class="soft-panel p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-sm text-stone-500">{{ $item->buku->kategori?->nama_kategori ?? '-' }}</p>
                        <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $item->buku->judul }}</h2>
                        <p class="mt-2 text-sm leading-7 text-stone-600">{{ $item->buku->penulis }}</p>
                        <p class="mt-3 text-sm text-stone-500">
                            {{ $item->tanggal_pinjam->format('d M Y') }} sampai {{ $item->tanggal_kembali->format('d M Y') }} •
                            {{ $item->jumlah }} buku
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        {!! $item->status_badge !!}
                        @if ($item->isPending())
                            <a href="{{ route('user.peminjaman.edit', $item) }}" class="btn-secondary !px-4 !py-2">Edit</a>
                            <form action="{{ route('user.peminjaman.destroy', $item) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-danger !px-4 !py-2">Hapus</button>
                            </form>
                        @endif
                        @if ($item->isApproved())
                            <form action="{{ route('user.peminjaman.return', $item) }}" method="POST" onsubmit="return confirm('Kembalikan buku ini?')">
                                @csrf
                                <button class="btn-primary !px-4 !py-2">Kembalikan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="soft-panel p-10 text-center text-stone-500">
                Belum ada transaksi. Mulai pinjam buku dari katalog perpustakaan.
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $peminjamans->links() }}</div>
@endsection
