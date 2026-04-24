@extends('layouts.portal')

@section('title', 'Manajemen Denda')
@section('page_heading', 'Manajemen Pembayaran Denda')
@section('page_description', 'Verifikasi dan setujui pembayaran denda dari member')

@section('content')
    <style>
        .stats-grid-elegant {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card-elegant {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #667eea;
        }

        .stat-card-elegant.danger {
            border-left-color: #ef4444;
        }

        .stat-card-elegant.warning {
            border-left-color: #f59e0b;
        }

        .stat-card-elegant.success {
            border-left-color: #10b981;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: bold;
            color: #1f2937;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        .filter-btn:hover {
            border-color: #667eea;
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
            transition: all 0.2s ease;
        }

        .elegant-table tbody tr:hover {
            background: #f9fafb;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .elegant-table tbody td {
            padding: 16px;
        }

        .member-info {
            display: flex;
            flex-direction: column;
        }

        .member-name {
            font-weight: 600;
            color: #1f2937;
        }

        .member-email {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .book-info {
            display: flex;
            flex-direction: column;
        }

        .book-title {
            font-weight: 600;
            color: #1f2937;
        }

        .book-author {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .denda-amount {
            font-weight: 700;
            color: #dc2626;
            font-size: 1.1rem;
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

        .btn-detail {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-bukti {
            background: #f59e0b;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.875rem;
            margin-left: 4px;
            transition: all 0.3s ease;
        }

        .btn-bukti:hover {
            background: #d97706;
        }

        .empty-state {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            border: 2px solid #3b82f6;
        }

        .empty-text {
            color: #1e40af;
            font-size: 1.1rem;
            font-weight: 600;
        }
    </style>

    {{-- STATISTIK --}}
    <div class="stats-grid-elegant">
        <div class="stat-card-elegant danger">
            <div class="stat-label">💰 Total Denda</div>
            <div class="stat-value">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card-elegant">
            <div class="stat-label">⏳ Belum Dibayar</div>
            <div class="stat-value">{{ $belumDibayar }}</div>
        </div>
        <div class="stat-card-elegant warning">
            <div class="stat-label">🔄 Pending Approval</div>
            <div class="stat-value">{{ $pendingApproval }}</div>
        </div>
        <div class="stat-card-elegant success">
            <div class="stat-label">✓ Sudah Dibayar</div>
            <div class="stat-value">{{ $sudahDibayar }}</div>
        </div>
    </div>

    {{-- FILTER STATUS --}}
    <div class="filter-buttons">
        <a href="{{ route('admin.denda.index') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">
            🔄 Pending Approval ({{ $pendingApproval }})
        </a>
        <a href="{{ route('admin.denda.index', ['status' => 'belum_dibayar']) }}" class="filter-btn {{ request('status') === 'belum_dibayar' ? 'active' : '' }}">
            ⏳ Belum Dibayar ({{ $belumDibayar }})
        </a>
        <a href="{{ route('admin.denda.index', ['status' => 'sudah_dibayar']) }}" class="filter-btn {{ request('status') === 'sudah_dibayar' ? 'active' : '' }}">
            ✓ Sudah Dibayar ({{ $sudahDibayar }})
        </a>
    </div>

    {{-- TABLE DENDA --}}
    <div class="elegant-table">
        @if($pengembalians->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>👤 Member</th>
                        <th>📚 Buku</th>
                        <th>💰 Denda</th>
                        <th>📅 Tanggal Pembayaran</th>
                        <th>✓ Status</th>
                        <th style="text-align: right;">⚙️ Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengembalians as $item)
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-name">{{ $item->peminjaman->user->name }}</div>
                                    <div class="member-email">{{ $item->peminjaman->user->email }}</div>
                                </div>
                            </td>

                            <td>
                                <div class="book-info">
                                    <div class="book-title">{{ $item->peminjaman->buku->judul }}</div>
                                    <div class="book-author">{{ $item->peminjaman->buku->penulis }}</div>
                                </div>
                            </td>

                            <td>
                                <div class="denda-amount">
                                    Rp {{ number_format($item->denda, 0, ',', '.') }}
                                </div>
                                @if ($item->ada_kerusakan)
                                    <div style="font-size: 0.875rem; color: #6b7280; margin-top: 4px;">
                                        🔴 Ada kerusakan
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if ($item->tanggal_pembayaran)
                                    <div style="font-size: 0.95rem;">{{ $item->tanggal_pembayaran->format('d M Y') }}</div>
                                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $item->tanggal_pembayaran->format('H:i') }}</div>
                                @else
                                    <span style="color: #6b7280;">-</span>
                                @endif
                            </td>

                            <td>
                                @if ($item->status_pembayaran === 'belum_dibayar')
                                    <span class="status-badge status-belum">Belum Dibayar</span>
                                @elseif ($item->status_pembayaran === 'pending_approval')
                                    <span class="status-badge status-pending">Pending Approval</span>
                                @else
                                    <span class="status-badge status-lunas">✓ Sudah Dibayar</span>
                                @endif
                            </td>

                            <td style="text-align: right;">
                                <a href="{{ route('admin.denda.show', $item) }}" class="btn-detail">
                                    Detail
                                </a>
                                
                                @if ($item->status_pembayaran === 'pending_approval')
                                    <a href="{{ route('admin.denda.view-bukti', $item) }}" class="btn-bukti" target="_blank">
                                        📸 Lihat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 16px; text-align: center; border-top: 1px solid #e5e7eb;">
                {{ $pengembalians->links() }}
            </div>
        @else
            <div class="empty-state">
                <div style="font-size: 2rem; margin-bottom: 16px;">📭</div>
                <div class="empty-text">Tidak ada data untuk ditampilkan</div>
                <p style="color: #1e7e84; margin-top: 8px; font-size: 0.95rem;">
                    Silakan pilih filter status yang berbeda
                </p>
            </div>
        @endif
    </div>
@endsection
