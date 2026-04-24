@extends('layouts.portal')

@section('title', 'Riwayat Pembayaran')
@section('page_heading', 'Riwayat Pembayaran Denda')
@section('page_description', 'Lihat daftar pembayaran denda yang sudah dilakukan')

@section('page_actions')
    <a href="{{ route('user.denda.index') }}" class="btn-secondary">Kembali ke Denda</a>
@endsection

@section('content')
    <div class="table-wrap">
        @if($pengembalians->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Denda</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengembalians as $item)
                        @if ($item->pengembalian && $item->pengembalian->denda > 0)
                            <tr>
                                <td>
                                    <b>{{ $item->buku->judul }}</b><br>
                                    <small>{{ $item->buku->penulis }}</small>
                                </td>

                                <td>
                                    <b class="text-red-600">
                                        Rp {{ number_format($item->pengembalian->denda, 0, ',', '.') }}
                                    </b>
                                </td>

                                <td>
                                    @if ($item->pengembalian->tanggal_pembayaran)
                                        {{ $item->pengembalian->tanggal_pembayaran->format('d M Y H:i') }}
                                    @else
                                        <small class="text-gray-500">-</small>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->pengembalian->status_pembayaran === 'belum_dibayar')
                                        <span class="badge bg-danger">Belum Dibayar</span>
                                    @elseif ($item->pengembalian->status_pembayaran === 'pending_approval')
                                        <span class="badge bg-warning">Pending Approval</span>
                                    @else
                                        <span class="badge bg-success">Sudah Dibayar</span>
                                    @endif
                                </td>

                                <td class="text-right">
                                    @if ($item->pengembalian->status_pembayaran === 'sudah_dibayar')
                                        <a href="{{ route('user.denda.show', $item->pengembalian) }}" class="btn-secondary">
                                            Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6">
                {{ $pengembalians->links() }}
            </div>
        @else
            <div class="alert alert-info" style="padding: 20px; text-align: center;">
                <p style="font-size: 1.1em;">Belum ada riwayat pembayaran</p>
            </div>
        @endif
    </div>
@endsection
