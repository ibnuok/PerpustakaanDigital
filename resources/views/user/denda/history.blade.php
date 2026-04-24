@extends('layouts.portal')

@section('title', 'Riwayat Pembayaran Denda')
@section('page_heading', 'Riwayat Pembayaran Denda')
@section('page_description', 'Semua pengajuan pembayaran denda Anda, baik yang masih diproses maupun yang sudah lunas.')

@section('page_actions')
    <a href="{{ route('user.denda.index') }}" class="btn-secondary">Kembali ke Denda</a>
@endsection

@section('content')
<div class="table-wrap">
    <table class="responsive-table">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Total Denda</th>
                <th>Tanggal Pengajuan</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalians as $item)
                @php($pengembalian = $item->pengembalian)
                @if ($pengembalian && $pengembalian->denda > 0)
                    <tr>
                        <td data-label="Buku">
                            <strong>{{ $item->buku->judul }}</strong><br>
                            <small>{{ $item->buku->penulis ?? 'Penulis tidak tersedia' }}</small>
                        </td>
                        <td data-label="Total Denda">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</td>
                        <td data-label="Tanggal Pengajuan">{{ $pengembalian->tanggal_pembayaran?->format('d M Y H:i') ?? '-' }}</td>
                        <td data-label="Metode">{{ $pengembalian->metode_pembayaran_label }}</td>
                        <td data-label="Status">{{ $pengembalian->status_label }}</td>
                        <td data-label="Aksi"><a href="{{ route('user.denda.show', $pengembalian) }}" class="btn-secondary">Detail</a></td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="empty-state">Belum ada riwayat pembayaran denda.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px;">{{ $pengembalians->links() }}</div>
@endsection
