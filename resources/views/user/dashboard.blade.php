@extends('layouts.portal')

@section('title', 'Dashboard Peminjam')
@section('page_heading', 'Dashboard Peminjam')
@section('page_description', 'Lihat pinjaman aktif, tagihan denda, dan riwayat terbaru Anda dalam satu halaman.')

@section('page_actions')
    <a href="{{ route('user.bukus') }}" class="btn-secondary">Cari Buku</a>
    <a href="{{ route('user.denda.index') }}" class="btn-primary">Lihat Denda</a>
@endsection

@section('content')
<div class="dashboard-grid">
    <section class="stat-card">
        <div class="stat-label">Pinjaman Aktif</div>
        <div class="stat-value">{{ $peminjamanAktif->count() }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Buku Tersedia</div>
        <div class="stat-value">{{ $bukuTersedia }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Riwayat Selesai</div>
        <div class="stat-value">{{ $peminjamanSelesai }}</div>
    </section>
    <section class="stat-card">
        <div class="stat-label">Tagihan Aktif</div>
        <div class="stat-value" style="color: {{ $totalTagihan > 0 ? 'var(--danger)' : 'var(--success)' }};">
            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
        </div>
    </section>
</div>

<div class="grid-2" style="margin-top: 20px; align-items:start;">
    <section class="table-wrap">
        <div style="padding: 22px 22px 0; display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap;">
            <div>
                <h3 style="margin:0;">Riwayat Terbaru</h3>
                <p style="margin:8px 0 0; color:var(--muted);">Status peminjaman dan pengembalian Anda akan tampil di sini.</p>
            </div>
            <a href="{{ route('user.peminjaman.index') }}" class="btn-secondary">Kelola Peminjaman</a>
        </div>
        <table class="responsive-table">
            <thead>
                <tr>
                    <th>Buku</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjamanTerbaru as $item)
                    <tr>
                        <td data-label="Buku">
                            <strong>{{ $item->buku->judul }}</strong><br>
                            <small>{{ $item->buku->penulis ?? 'Penulis tidak tersedia' }}</small>
                        </td>
                        <td data-label="Periode">
                            <strong>{{ $item->tanggal_pinjam->format('d M Y H:i') }}</strong><br>
                            <small>Sampai {{ $item->tanggal_kembali->format('d M Y H:i') }}</small>
                        </td>
                        <td data-label="Status">
                            @if ($item->status === 'pending')
                                <span class="status-badge status-pending">Menunggu</span>
                            @elseif ($item->status === 'dipinjam')
                                <span class="status-badge badge-approved">Dipinjam</span>
                            @else
                                <span class="status-badge status-lunas">Dikembalikan</span>
                            @endif
                            <div><small>{{ $item->sisa_waktu_label }}</small></div>
                        </td>
                        <td data-label="Denda">
                            @if (($item->pengembalian?->denda ?? 0) > 0)
                                <strong style="color:var(--danger);">Rp {{ number_format($item->pengembalian->denda, 0, ',', '.') }}</strong><br>
                                <small>{{ $item->pengembalian->status_label }}</small>
                            @else
                                <span style="color:var(--success); font-weight:700;">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-state">Belum ada transaksi peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="stack-list">
        <section class="card">
            <span class="badge-soft">Aksi Cepat</span>
            <div class="action-row" style="margin-top:14px;">
                <a href="{{ route('user.bukus') }}" class="btn-secondary">Cari Buku</a>
                <a href="{{ route('user.peminjaman.index') }}" class="btn-primary">Peminjaman Saya</a>
            </div>
        </section>

        <section class="card">
            <div style="display:flex; justify-content:space-between; gap:10px; align-items:center; flex-wrap:wrap;">
                <span class="badge-soft">Tagihan Denda</span>
                <strong style="color: {{ $totalTagihan > 0 ? 'var(--danger)' : 'var(--success)' }};">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </strong>
            </div>

            <div class="stack-list" style="margin-top:14px;">
                @forelse ($tagihanAktif as $item)
                    @php($pengembalian = $item->pengembalian)
                    <div class="stack-item">
                        <strong>{{ $item->buku->judul }}</strong>
                        <div style="margin-top:8px;"><strong>{{ $pengembalian->jenis_denda_label }}</strong></div>
                        @if (($pengembalian->denda_telat ?? 0) > 0)
                            <div>Terlambat {{ $pengembalian->hari_terlambat }} hari: Rp {{ number_format($pengembalian->denda_telat ?? 0, 0, ',', '.') }}</div>
                        @endif
                        @if (($pengembalian->denda_kerusakan ?? 0) > 0)
                            <div>Kerusakan buku: Rp {{ number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') }}</div>
                        @endif
                        <div style="margin-top:8px;"><small>Status: {{ $pengembalian->status_label }}</small></div>
                        @if ($pengembalian->catatan_penolakan)
                            <div style="margin-top:8px; color:var(--danger);"><small>Catatan admin: {{ $pengembalian->catatan_penolakan }}</small></div>
                        @endif
                        @if ($pengembalian->status_pembayaran !== 'sudah_dibayar')
                            <div class="action-row">
                                <button type="button" class="btn-primary" data-open-payment data-target="payment-modal-{{ $pengembalian->id }}">Bayar</button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="stack-item">Belum ada denda aktif. Bagus, tetap seperti ini.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>

@foreach ($tagihanAktif as $item)
    @php($pengembalian = $item->pengembalian)
    <div class="modal-backdrop" id="payment-modal-{{ $pengembalian->id }}">
        <div class="modal-card">
            <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap;">
                <div>
                    <span class="badge-soft">Pembayaran Denda</span>
                    <h3 style="margin:12px 0 4px;">{{ $item->buku->judul }}</h3>
                    <p style="margin:0; color:var(--muted);">Pilih metode pembayaran, lalu kirim pengajuan verifikasi ke admin.</p>
                </div>
                <button type="button" class="btn-secondary" data-close-modal>Tutup</button>
            </div>

            <div class="detail-grid" style="margin-top:18px;">
                <div class="stack-item">
                    <strong>Denda Terlambat</strong>
                    <div>{{ ($pengembalian->denda_telat ?? 0) > 0 ? $pengembalian->hari_terlambat . ' hari • Rp ' . number_format($pengembalian->denda_telat ?? 0, 0, ',', '.') : 'Tidak terkena denda terlambat' }}</div>
                </div>
                <div class="stack-item">
                    <strong>Denda Kerusakan</strong>
                    <div>{{ ($pengembalian->denda_kerusakan ?? 0) > 0 ? 'Rp ' . number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') : 'Tidak terkena denda kerusakan' }}</div>
                </div>
            </div>

            <div class="stack-item" style="margin-top:16px;">
                <strong>Total Tagihan</strong>
                <div style="font-size:30px; margin-top:6px; color:var(--danger);">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</div>
                @if ($pengembalian->deskripsi_kerusakan)
                    <small>Catatan kerusakan: {{ $pengembalian->deskripsi_kerusakan }}</small>
                @endif
            </div>

            <form action="{{ route('user.denda.submit-payment', $pengembalian) }}" method="POST" enctype="multipart/form-data" style="margin-top:18px;">
                @csrf
                <div>
                    <label class="field-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="field-select payment-method" data-proof-target="proof-{{ $pengembalian->id }}" required>
                        <option value="">Pilih metode pembayaran</option>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer Rekening</option>
                    </select>
                </div>

                <div class="stack-item" style="margin-top:16px; background:var(--brand-soft); border-color:#d3dcea;">
                    <strong>Instruksi</strong>
                    <div style="margin-top:8px; line-height:1.7; color:var(--muted);">
                        Tunai: langsung kirim pengajuan lalu tunggu admin konfirmasi.<br>
                        Transfer: kirim ke rekening perpustakaan lalu upload bukti pembayaran.
                    </div>
                    <div style="margin-top:10px;"><strong>BCA 1234567890</strong> a.n. Perpustakaan Digital</div>
                </div>

                <div id="proof-{{ $pengembalian->id }}" style="display:none; margin-top:16px;">
                    <label class="field-label">Bukti Pembayaran Transfer</label>
                    <input type="file" name="bukti_pembayaran" accept="image/*" class="field-input">
                    <small>Wajib untuk metode transfer. Format gambar maksimal 2MB.</small>
                </div>

                <div class="action-row" style="justify-content:flex-end;">
                    <button type="button" class="btn-secondary" data-close-modal>Batal</button>
                    <button type="submit" class="btn-primary">Kirim Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-open-payment]').forEach(function (button) {
            button.addEventListener('click', function () {
                const modal = document.getElementById(button.dataset.target);
                if (modal) modal.classList.add('show');
            });
        });

        document.querySelectorAll('[data-close-modal]').forEach(function (button) {
            button.addEventListener('click', function () {
                const modal = button.closest('.modal-backdrop');
                if (modal) modal.classList.remove('show');
            });
        });

        document.querySelectorAll('.modal-backdrop').forEach(function (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });

        document.querySelectorAll('.payment-method').forEach(function (select) {
            const target = document.getElementById(select.dataset.proofTarget);
            const toggle = function () {
                if (!target) return;
                target.style.display = select.value === 'transfer' ? 'block' : 'none';
                const fileInput = target.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.required = select.value === 'transfer';
                }
            };
            select.addEventListener('change', toggle);
            toggle();
        });
    });
</script>
@endsection
