@extends('layouts.portal')

@section('title', 'Pembayaran Denda')
@section('page_heading', 'Pembayaran Denda')
@section('page_description', 'Kirim pembayaran tunai atau transfer untuk tagihan denda Anda.')

@section('page_actions')
    <a href="{{ route('user.denda.index') }}" class="btn-secondary">Kembali ke Denda</a>
@endsection

@section('content')
<div class="form-shell">
    <section class="form-panel">
        <span class="badge-soft">Rincian Tagihan</span>
        <div class="detail-grid" style="margin-top: 16px;">
            <div class="stack-item">
                <strong>Buku</strong>
                <div>{{ $pengembalian->peminjaman->buku->judul }}</div>
            </div>
            <div class="stack-item">
                <strong>Jenis Denda</strong>
                <div>{{ $pengembalian->jenis_denda_label }}</div>
            </div>
            <div class="stack-item">
                <strong>Total Denda</strong>
                <div style="font-size: 28px; color: var(--danger);">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</div>
            </div>
            <div class="stack-item">
                <strong>Denda Terlambat</strong>
                <div>{{ ($pengembalian->denda_telat ?? 0) > 0 ? $pengembalian->hari_terlambat . ' hari • Rp ' . number_format($pengembalian->denda_telat ?? 0, 0, ',', '.') : 'Tidak terkena denda terlambat' }}</div>
            </div>
            <div class="stack-item">
                <strong>Denda Kerusakan</strong>
                <div>{{ ($pengembalian->denda_kerusakan ?? 0) > 0 ? 'Rp ' . number_format($pengembalian->denda_kerusakan ?? 0, 0, ',', '.') : 'Tidak terkena denda kerusakan' }}</div>
            </div>
        </div>

        @if ($pengembalian->deskripsi_kerusakan)
            <div class="stack-item" style="margin-top:16px;">
                <strong>Catatan Kerusakan</strong>
                <div>{{ $pengembalian->deskripsi_kerusakan }}</div>
            </div>
        @endif

        <form action="{{ route('user.denda.submit-payment', $pengembalian) }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            @csrf
            <div>
                <label class="field-label">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" class="field-select" required>
                    <option value="">Pilih metode pembayaran</option>
                    <option value="tunai">Tunai</option>
                    <option value="transfer">Transfer Rekening</option>
                </select>
            </div>

            <div class="stack-item" style="margin-top:16px; background: var(--brand-soft); border-color: #d3dcea;">
                <strong>Instruksi Transfer</strong>
                <div style="margin-top: 8px; line-height: 1.7; color: var(--muted);">BCA 1234567890 a.n. Perpustakaan Digital</div>
                <small>Untuk metode tunai, Anda cukup kirim pengajuan lalu admin akan memverifikasi pembayaran.</small>
            </div>

            <div id="proof-wrapper" style="display:none; margin-top:16px;">
                <label class="field-label">Bukti Pembayaran</label>
                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" class="field-input">
                <small>Wajib untuk pembayaran transfer.</small>
            </div>

            <div class="action-row" style="justify-content:flex-end;">
                <a href="{{ route('user.denda.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Kirim Pembayaran</button>
            </div>
        </form>
    </section>

    <aside class="guide-card">
        <span class="badge-soft">Status Saat Ini</span>
        <div class="stack-list" style="margin-top:16px;">
            <div class="stack-item"><strong>Status</strong><div>{{ $pengembalian->status_label }}</div></div>
            <div class="stack-item"><strong>Metode Terakhir</strong><div>{{ $pengembalian->metode_pembayaran_label }}</div></div>
            @if ($pengembalian->catatan_penolakan)
                <div class="stack-item" style="color:var(--danger);"><strong>Catatan Admin</strong><div>{{ $pengembalian->catatan_penolakan }}</div></div>
            @endif
        </div>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const method = document.getElementById('metode_pembayaran');
        const proofWrapper = document.getElementById('proof-wrapper');
        const proofInput = document.getElementById('bukti_pembayaran');

        const toggle = function () {
            const transfer = method.value === 'transfer';
            proofWrapper.style.display = transfer ? 'block' : 'none';
            proofInput.required = transfer;
        };

        method.addEventListener('change', toggle);
        toggle();
    });
</script>
@endsection
