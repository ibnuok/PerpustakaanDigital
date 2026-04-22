@extends('layouts.portal')

@section('title', 'Transaksi')
@section('page_heading', 'Transaksi Perpustakaan')
@section('page_description', 'Pantau seluruh pengajuan, pinjaman, dan pengembalian buku dengan pencarian serta filter lengkap.')

@section('page_actions')
    <a href="{{ route('admin.peminjaman.create') }}" class="btn-primary">Tambah Transaksi</a>
@endsection

@section('content')
    <div class="stats-grid">
        <div class="stat-card"><p class="text-sm text-stone-500">Total Transaksi</p><p class="mt-3 text-3xl font-bold text-slate-900">{{ $totalPeminjaman }}</p></div>
        <div class="stat-card"><p class="text-sm text-stone-500">Menunggu</p><p class="mt-3 text-3xl font-bold text-slate-900">{{ $pending }}</p></div>
        <div class="stat-card"><p class="text-sm text-stone-500">Dipinjam</p><p class="mt-3 text-3xl font-bold text-slate-900">{{ $approved }}</p></div>
        <div class="stat-card"><p class="text-sm text-stone-500">Dikembalikan</p><p class="mt-3 text-3xl font-bold text-slate-900">{{ $returned }}</p></div>
    </div>

    <form method="GET" class="filter-panel mt-6">
        <div class="filter-grid" style="grid-template-columns: repeat(6, minmax(0, 1fr));">
            <input type="text" name="search" value="{{ request('search') }}" class="field-input" placeholder="Cari peminjam / buku">
            <select name="user_id" class="field-select">
                <option value="">Semua akun</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
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
                <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary flex-1">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-wrap mt-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse ($peminjamans as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $item->user->name }}</p>
                                <p class="mt-1 text-stone-500">{{ $item->user->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $item->buku->judul }}</p>
                                <p class="mt-1 text-stone-500">{{ $item->buku->penulis }}</p>
                            </td>
                            <td class="px-6 py-4 text-stone-600">
                                {{ $item->tanggal_pinjam->format('d M Y') }}<br>
                                <span class="text-stone-400">s/d {{ $item->tanggal_kembali->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->jumlah }}</td>
                            <td class="px-6 py-4">{!! $item->status_badge !!}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.peminjaman.show', $item) }}" class="btn-secondary">Detail</a>
                                    @if ($item->isPending())
                                        <a href="{{ route('admin.peminjaman.edit', $item) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('admin.peminjaman.approve', $item) }}" method="POST">
                                            @csrf
                                            <button class="btn-primary">Setujui</button>
                                        </form>
                                    @endif
                                    @if ($item->isApproved())
                                        <form action="{{ route('admin.peminjaman.return', $item) }}" method="POST">
                                            @csrf
                                            <button class="btn-secondary">Kembalikan</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.peminjaman.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-stone-500">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $peminjamans->links() }}</div>
@endsection
