@extends('layouts.portal')

@section('title', 'Denda Saya')
@section('page_heading', 'Denda Saya')
@section('page_description', 'Bayar denda telat dan kerusakan dari sini. Status pembayaran tetap sinkron dengan backend yang sudah ada.')

@section('page_actions')
    <a href="{{ route('user.denda.history') }}" class="btn-secondary">Riwayat Pembayaran</a>
@endsection

@section('content')
<section class="stat-card" style="margin-bottom:20px;">
    <div class="stat-label">Total Denda Belum Lunas</div>
    <div class="stat-value" style="color: {{ $totalDenda > 0 ? 'var(--danger)' : 'var(--success)' }};">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
</section>

<div class="table-wrap">
    <table class="responsive-table">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Rincian</th>
                <th>Status</th>
                <th>Metode</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalians as $item)
                @php($pengembalian = $item->pengembalian)
                <tr>
                    <td data-label="Buku">
                        <strong>{{ $item->buku->judul }}</strong><br>
                        <small>{{ $item->buku->penulis ?? 'Penulis tidak tersedia' }}</small>
                    </td>
                    <td data-label="Rincian">
                        <div><strong>{{ $pengembalian->jenis_denda_label }}</strong></div>
                        @if (($pengembalian->denda_telat ?? 0) > 0)
                            <div>Terlambat {{ $pengembalian->hari_terlambat }} hari: Rp {{ number_format($pengembalian->denda_telat ?? 0, 0, ',', '.') }}</div>
                        @endif
                        @if (($pengembalian->denda_kerusakan ?? 0) > 0)
                            <div>Kerusakan buku: Rp {{ number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') }}</div>
                        @endif
                        <strong style="display:block; margin-top:6px; color:var(--danger);">Total Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</strong>
                        @if ($pengembalian->catatan_penolakan)
                            <small style="color:var(--danger);">Catatan admin: {{ $pengembalian->catatan_penolakan }}</small>
                        @endif
                    </td>
                    <td data-label="Status">
                        @if ($pengembalian->status_pembayaran === 'belum_dibayar')
                            <span class="status-badge status-belum">Belum Lunas</span>
                        @elseif ($pengembalian->status_pembayaran === 'pending_approval')
                            <span class="status-badge status-pending">Menunggu Verifikasi</span>
                        @else
                            <span class="status-badge status-lunas">Lunas</span>
                        @endif
                    </td>
                    <td data-label="Metode">{{ $pengembalian->metode_pembayaran_label }}</td>
                    <td data-label="Aksi">
                        @if ($pengembalian->status_pembayaran !== 'sudah_dibayar')
                            <button type="button" class="btn-primary" data-open-payment data-target="user-denda-modal-{{ $pengembalian->id }}">Bayar</button>
                        @else
                            <span class="btn-secondary">Selesai</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">Tidak ada denda aktif. Semua transaksi Anda aman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px;">{{ $pengembalians->links() }}</div>

@foreach ($pengembalians as $item)
    @php($pengembalian = $item->pengembalian)
    <div class="modal-backdrop" id="user-denda-modal-{{ $pengembalian->id }}">
        <div class="modal-card">
            <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap;">
                <div>
                    <span class="badge-soft">Pembayaran Denda</span>
                    <h3 style="margin:12px 0 4px;">{{ $item->buku->judul }}</h3>
                    <p style="margin:0; color:var(--muted);">Silakan pilih metode pembayaran untuk tagihan ini.</p>
                </div>
                <button type="button" class="btn-secondary" data-close-modal>Tutup</button>
            </div>

            <div class="detail-grid" style="margin-top:18px;">
                <div class="stack-item"><strong>Denda Terlambat</strong><div>{{ ($pengembalian->denda_telat ?? 0) > 0 ? $pengembalian->hari_terlambat . ' hari • Rp ' . number_format($pengembalian->denda_telat ?? 0, 0, ',', '.') : 'Tidak terkena denda terlambat' }}</div></div>
                <div class="stack-item"><strong>Denda Kerusakan</strong><div>{{ ($pengembalian->denda_kerusakan ?? 0) > 0 ? 'Rp ' . number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') : 'Tidak terkena denda kerusakan' }}</div></div>
            </div>

            <form action="{{ route('user.denda.submit-payment', $pengembalian) }}" method="POST" enctype="multipart/form-data" style="margin-top:18px;">
                @csrf
                <label class="field-label">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="field-select payment-method" data-proof-target="user-proof-{{ $pengembalian->id }}" required>
                    <option value="">Pilih metode pembayaran</option>
                    <option value="tunai">Tunai</option>
                    <option value="transfer">Transfer Rekening</option>
                </select>

                <div class="stack-item" style="margin-top:16px; background:var(--brand-soft); border-color:#d3dcea;">
                    <strong>Total Tagihan</strong>
                    <div style="font-size:28px; margin-top:8px; color:var(--danger);">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</div>
                    <small>BCA 1234567890 a.n. Perpustakaan Digital</small>
                </div>

                <div id="user-proof-{{ $pengembalian->id }}" style="display:none; margin-top:16px;">
                    <label class="field-label">Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" accept="image/*" class="field-input">
                    <small>Wajib jika memilih transfer rekening.</small>
                </div>

                <div class="action-row" style="justify-content:flex-end;">
                    <button type="button" class="btn-secondary" data-close-modal>Batal</button>
                    <button type="submit" class="btn-primary">Kirim</button>
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
                if (event.target === modal) modal.classList.remove('show');
            });
        });

        document.querySelectorAll('.payment-method').forEach(function (select) {
            const target = document.getElementById(select.dataset.proofTarget);
            const toggle = function () {
                if (!target) return;
                target.style.display = select.value === 'transfer' ? 'block' : 'none';
                const input = target.querySelector('input[type="file"]');
                if (input) input.required = select.value === 'transfer';
            };
            select.addEventListener('change', toggle);
            toggle();
        });
    });
</script>
@endsection
