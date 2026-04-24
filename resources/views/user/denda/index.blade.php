@extends('layouts.portal')

@section('title', 'Daftar Denda Saya')
@section('page_heading', 'Denda Perpustakaan')
@section('page_description', 'Kelola pembayaran denda keterlambatan dan kerusakan buku')

@section('page_actions')
    <a href="{{ route('user.denda.history') }}" class="btn-secondary">📋 Riwayat Pembayaran</a>
@endsection

@section('content')
    <style>
        .stats-box {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 24px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .stats-amount {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 8px 0;
        }

        .stats-label {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .elegant-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .elegant-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .elegant-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .elegant-table thead th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .elegant-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s ease;
        }

        .elegant-table tbody tr:hover {
            background: #f9fafb;
        }

        .elegant-table tbody td {
            padding: 16px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-belum {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-lunas {
            background: #d1fae5;
            color: #065f46;
        }

        .denda-amount {
            font-weight: 600;
            color: #dc2626;
            font-size: 1.1rem;
        }

        .btn-bayar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-bayar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-lunas {
            background: #d1d5db;
            color: #6b7280;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: not-allowed;
            font-size: 0.9rem;
        }

        .empty-state {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            border: 2px solid #10b981;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 16px;
        }

        .empty-state-text {
            color: #065f46;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .book-info {
            display: flex;
            flex-direction: column;
        }

        .book-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .book-author {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .denda-items {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 4px;
        }
    </style>

    @if($pengembalians->count() > 0)
        {{-- STATISTIK DENDA --}}
        <div class="stats-box">
            <div class="stats-label">Total Denda yang Belum Dibayar</div>
            <div class="stats-amount">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
        </div>

        {{-- DAFTAR DENDA --}}
        <div class="elegant-table" style="margin-top: 24px;">
            <table>
                <thead>
                    <tr>
                        <th>📚 Buku</th>
                        <th>💰 Denda</th>
                        <th>📌 Jenis</th>
                        <th>✓ Status</th>
                        <th style="text-align: right;">⚙️ Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengembalians as $item)
                        @if ($item->pengembalian && $item->pengembalian->denda > 0)
                            <tr>
                                <td>
                                    <div class="book-info">
                                        <div class="book-title">{{ $item->buku->judul }}</div>
                                        <div class="book-author">{{ $item->buku->penulis }}</div>
                                    </div>
                                </td>

                                <td>
                                    <div class="denda-amount">
                                        Rp {{ number_format($item->pengembalian->denda, 0, ',', '.') }}
                                    </div>
                                </td>

                                <td>
                                    <div style="font-size: 0.875rem;">
                                        @if ($item->pengembalian->ada_kerusakan)
                                            <span class="status-badge" style="background: #fee2e2; color: #991b1b; display: block; margin-bottom: 4px;">
                                                🔴 Kerusakan
                                            </span>
                                        @endif
                                        
                                        @if ($item->isTerlambat() || ($item->pengembalian->denda > 0 && !$item->pengembalian->ada_kerusakan))
                                            <span class="status-badge" style="background: #fef3c7; color: #92400e; display: block;">
                                                ⏰ Keterlambatan
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    @if ($item->pengembalian->status_pembayaran === 'belum_dibayar')
                                        <span class="status-badge status-belum">Belum Dibayar</span>
                                    @elseif ($item->pengembalian->status_pembayaran === 'pending_approval')
                                        <span class="status-badge status-pending">Pending Approval</span>
                                    @else
                                        <span class="status-badge status-lunas">✓ Sudah Dibayar</span>
                                    @endif
                                </td>

                                <td style="text-align: right;">
                                    @if ($item->pengembalian->status_pembayaran === 'belum_dibayar')
                                        <a href="{{ route('user.denda.payment', $item->pengembalian) }}" class="btn-bayar">
                                            Bayar
                                        </a>
                                    @elseif ($item->pengembalian->status_pembayaran === 'pending_approval')
                                        <button class="btn-lunas" style="cursor: wait;">
                                            Menunggu...
                                        </button>
                                    @else
                                        <button class="btn-lunas" style="background: #d1fae5; color: #065f46;">
                                            ✓ Lunas
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $pengembalians->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">✓</div>
            <div class="empty-state-text">Tidak ada denda. Selamat!</div>
            <p style="color: #047857; margin-top: 8px; font-size: 0.95rem;">
                Anda telah melunasi semua denda perpustakaan.
            </p>
        </div>
    @endif
@endsection
