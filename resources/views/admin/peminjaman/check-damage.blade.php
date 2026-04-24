@extends('layouts.portal')

@section('title', 'Pemeriksaan Kerusakan')
@section('page_heading', 'Pemeriksaan Kerusakan Buku')
@section('page_description', 'Tentukan apakah ada denda kerusakan tambahan setelah buku dikembalikan.')

@section('page_actions')
    <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Kembali ke Transaksi</a>
@endsection

@section('content')
<div class="form-shell">
    <section class="form-panel">
        <span class="badge-soft">Informasi Pengembalian</span>
        <div class="detail-grid" style="margin-top:16px;">
            <div class="stack-item"><strong>Peminjam</strong><div>{{ $peminjaman->user->name }}</div></div>
            <div class="stack-item"><strong>Buku</strong><div>{{ $peminjaman->buku->judul }}</div></div>
            <div class="stack-item"><strong>Jatuh Tempo</strong><div>{{ $peminjaman->tanggal_kembali->format('d M Y H:i') }}</div></div>
            <div class="stack-item"><strong>Dikembalikan</strong><div>{{ $pengembalian->tanggal_pengembalian->format('d M Y H:i') }}</div></div>
        </div>

        <div class="stack-item" style="margin-top:16px;">
            <strong>Denda Telat Saat Ini</strong>
            <div style="font-size:28px; margin-top:8px; color: {{ ($pengembalian->denda_telat ?? $pengembalian->denda) > 0 ? 'var(--danger)' : 'var(--success)' }};">
                Rp {{ number_format($pengembalian->denda_telat ?? $pengembalian->denda, 0, ',', '.') }}
            </div>
        </div>

        <form action="{{ route('admin.peminjaman.save-damage', $peminjaman) }}" method="POST" style="margin-top:20px;">
            @csrf
            <div>
                <label class="field-label">Kondisi Buku</label>
                <div class="stack-list">
                    <label class="stack-item" style="cursor:pointer;">
                        <input type="radio" name="ada_kerusakan" value="0" checked onchange="toggleDamage(false)"> Buku baik, tidak ada denda kerusakan
                    </label>
                    <label class="stack-item" style="cursor:pointer;">
                        <input type="radio" name="ada_kerusakan" value="1" onchange="toggleDamage(true)"> Buku rusak, tambahkan denda kerusakan
                    </label>
                </div>
            </div>

            <div id="damage-fields" style="display:none; margin-top:16px;">
                <div class="form-grid">
                    <div>
                        <label class="field-label">Denda Kerusakan</label>
                        <input type="number" name="denda_kerusakan" id="denda_kerusakan" class="field-input" value="{{ \App\Models\Peminjaman::DENDA_KERUSAKAN }}" min="0">
                    </div>
                    <div>
                        <label class="field-label">Preview Total Denda</label>
                        <input type="text" id="total_preview" class="field-input" value="Rp {{ number_format($pengembalian->denda_telat ?? $pengembalian->denda, 0, ',', '.') }}" readonly>
                    </div>
                </div>
                <div style="margin-top:16px;">
                    <label class="field-label">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi_kerusakan" id="deskripsi_kerusakan" rows="4" class="field-textarea" placeholder="Jelaskan bagian buku yang rusak..."></textarea>
                </div>
            </div>

            <div class="action-row" style="justify-content:flex-end;">
                <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Pemeriksaan</button>
            </div>
        </form>
    </section>

    <aside class="guide-card">
        <span class="badge-soft">Catatan</span>
        <div class="stack-list" style="margin-top:16px;">
            <div class="stack-item">Denda telat dihitung Rp {{ number_format(\App\Models\Peminjaman::DENDA_PER_HARI, 0, ',', '.') }} per hari. Denda kerusakan default Rp {{ number_format(\App\Models\Peminjaman::DENDA_KERUSAKAN, 0, ',', '.') }} per kejadian kerusakan.</div>
            <div class="stack-item">Jika pembayaran ditolak, status akan kembali menjadi belum lunas agar peminjam bisa mengajukan ulang.</div>
        </div>
    </aside>
</div>

<script>
    const lateFine = {{ (int) ($pengembalian->denda_telat ?? $pengembalian->denda) }};
    const damageFields = document.getElementById('damage-fields');
    const damageInput = document.getElementById('denda_kerusakan');
    const totalPreview = document.getElementById('total_preview');
    const description = document.getElementById('deskripsi_kerusakan');

    function formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }

    function updatePreview() {
        const total = lateFine + Number(damageInput.value || 0);
        totalPreview.value = formatRupiah(total);
    }

    function toggleDamage(show) {
        damageFields.style.display = show ? 'block' : 'none';
        description.required = show;
        updatePreview();
    }

    if (damageInput) {
        damageInput.addEventListener('input', updatePreview);
    }
</script>
@endsection
