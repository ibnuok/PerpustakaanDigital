@extends('layouts.portal')

@section('title', 'Detail Pembayaran Denda')
@section('page_heading', 'Detail Pembayaran Denda')
@section('page_description', 'Verifikasi dan persetujui pembayaran denda dari member')

@section('content')
    <style>
        .elegant-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .elegant-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card-header-elegant {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            border-bottom: none;
        }

        .card-body-elegant {
            padding: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-item {
            padding: 12px 16px;
            background: #f9fafb;
            border-left: 4px solid #667eea;
            border-radius: 8px;
        }

        .info-item-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .info-item-value {
            font-size: 1.125rem;
            color: #1f2937;
            font-weight: 600;
        }

        .denda-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            padding: 16px;
            border-radius: 8px;
            margin: 16px 0;
        }

        .denda-amount {
            font-size: 2rem;
            font-weight: bold;
            color: #d97706;
        }

        .approval-section {
            background: #f3f4f6;
            padding: 24px;
            border-radius: 12px;
            margin-top: 24px;
        }

        .btn-approve {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 10px;
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .bukti-preview {
            width: 100%;
            border-radius: 8px;
            max-height: 400px;
            object-fit: cover;
            margin: 16px 0;
            border: 2px solid #e5e7eb;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="grid-2">
        {{-- MAIN INFO --}}
        <div>
            <div class="elegant-card">
                <div class="card-header-elegant">
                    <h3>📋 Informasi Member</h3>
                </div>
                <div class="card-body-elegant">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-item-label">Nama Member</div>
                            <div class="info-item-value">{{ $pengembalian->peminjaman->user->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Email</div>
                            <div class="info-item-value" style="font-size: 0.95rem;">{{ $pengembalian->peminjaman->user->email }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="elegant-card" style="margin-top: 20px;">
                <div class="card-header-elegant">
                    <h3>📚 Informasi Buku</h3>
                </div>
                <div class="card-body-elegant">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-item-label">Judul Buku</div>
                            <div class="info-item-value">{{ $pengembalian->peminjaman->buku->judul }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Penulis</div>
                            <div class="info-item-value">{{ $pengembalian->peminjaman->buku->penulis }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="elegant-card" style="margin-top: 20px;">
                <div class="card-header-elegant">
                    <h3>💰 Rincian Denda</h3>
                </div>
                <div class="card-body-elegant">
                    @if ($pengembalian->ada_kerusakan)
                        <div class="info-item" style="margin-bottom: 12px;">
                            <div class="info-item-label">Denda Kerusakan Buku</div>
                            <div class="info-item-value">Rp 50.000</div>
                        </div>
                    @endif

                    @php
                        $dendaKeterlambatan = $pengembalian->denda - ($pengembalian->ada_kerusakan ? 50000 : 0);
                    @endphp
                    @if ($dendaKeterlambatan > 0)
                        <div class="info-item" style="margin-bottom: 12px;">
                            <div class="info-item-label">Denda Keterlambatan</div>
                            <div class="info-item-value">Rp {{ number_format($dendaKeterlambatan, 0, ',', '.') }}</div>
                        </div>
                    @endif

                    <div class="denda-box">
                        <div class="info-item-label">Total Denda</div>
                        <div class="denda-amount">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</div>
                    </div>

                    @if ($pengembalian->deskripsi_kerusakan)
                        <div class="info-item" style="margin-top: 12px;">
                            <div class="info-item-label">Catatan Kerusakan</div>
                            <p style="margin-top: 8px; color: #374151;">{{ $pengembalian->deskripsi_kerusakan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- APPROVAL SECTION --}}
        <div>
            @if ($pengembalian->bukti_pembayaran)
                <div class="elegant-card">
                    <div class="card-header-elegant">
                        <h3>📸 Bukti Pembayaran</h3>
                    </div>
                    <div class="card-body-elegant">
                        <img src="{{ route('admin.denda.view-bukti', $pengembalian) }}" alt="Bukti Pembayaran" class="bukti-preview">
                        <a href="{{ route('admin.denda.download-bukti', $pengembalian) }}" class="btn-secondary" style="display: block; text-align: center; margin-top: 12px;">
                            📥 Download Bukti
                        </a>
                    </div>
                </div>
            @endif

            <div class="elegant-card" style="margin-top: 20px;">
                <div class="card-header-elegant">
                    <h3>✓ Status & Approval</h3>
                </div>
                <div class="card-body-elegant">
                    <div style="text-align: center; margin-bottom: 20px;">
                        @if ($pengembalian->status_pembayaran === 'pending_approval')
                            <span class="status-badge status-pending">⏳ Pending Approval</span>
                        @elseif ($pengembalian->status_pembayaran === 'sudah_dibayar')
                            <span class="status-badge status-approved">✓ Sudah Disetujui</span>
                        @else
                            <span class="status-badge status-rejected">✗ Belum Dibayar</span>
                        @endif
                    </div>

                    @if ($pengembalian->status_pembayaran === 'pending_approval')
                        <form action="{{ route('admin.denda.approve', $pengembalian) }}" method="POST" style="margin-bottom: 12px;">
                            @csrf
                            <button type="submit" class="btn-approve">
                                ✓ Setujui Pembayaran
                            </button>
                        </form>

                        <button type="button" class="btn-reject" onclick="toggleRejectForm()">
                            ✗ Tolak Pembayaran
                        </button>

                        <div id="reject-form" style="display: none; margin-top: 16px; padding-top: 16px; border-top: 2px solid #e5e7eb;">
                            <form action="{{ route('admin.denda.reject', $pengembalian) }}" method="POST">
                                @csrf

                                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                                    Alasan Penolakan:
                                </label>
                                <textarea 
                                    name="alasan_penolakan" 
                                    rows="4" 
                                    class="field-input"
                                    placeholder="Jelaskan alasan penolakan..."
                                    required
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit;"
                                ></textarea>

                                <div style="display: flex; gap: 10px; margin-top: 12px;">
                                    <button type="submit" class="btn-reject" style="flex: 1;">
                                        Kirim Penolakan
                                    </button>
                                    <button type="button" class="btn-secondary" style="flex: 1;" onclick="toggleRejectForm()">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    @elseif ($pengembalian->status_pembayaran === 'sudah_dibayar')
                        <div style="text-align: center; padding: 24px; background: #d1fae5; border-radius: 8px;">
                            <div style="font-size: 2rem; margin-bottom: 8px;">✓</div>
                            <div style="font-size: 1.125rem; font-weight: 600; color: #065f46;">
                                Pembayaran Disetujui
                            </div>
                            <div style="font-size: 0.875rem; color: #047857; margin-top: 4px;">
                                Member sudah lunas
                            </div>
                        </div>
                    @else
                        <div style="text-align: center; padding: 24px; background: #fee2e2; border-radius: 8px;">
                            <div style="font-size: 1.125rem; font-weight: 600; color: #991b1b;">
                                Belum Ada Pembayaran
                            </div>
                            <div style="font-size: 0.875rem; color: #7f1d1d; margin-top: 4px;">
                                Menunggu member untuk upload bukti
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.denda.index') }}" class="btn-secondary" style="display: block; text-align: center; margin-top: 20px; padding: 12px; border-radius: 8px;">
                ← Kembali ke Daftar
            </a>
        </div>
    </div>

    <script>
        function toggleRejectForm() {
            const form = document.getElementById('reject-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
@endsection
