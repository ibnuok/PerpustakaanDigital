@extends('layouts.portal')

@section('title', 'Ajukan Peminjaman')
@section('page_heading', 'Ajukan Peminjaman Buku')
@section('page_description', 'Lengkapi tanggal dan waktu peminjaman agar admin bisa memproses dengan cepat.')

@section('page_actions')
    <a href="{{ route('user.bukus') }}" class="btn-secondary">Kembali ke Katalog</a>
@endsection

@section('content')
    <form action="{{ route('user.peminjaman.store') }}" method="POST" class="form-shell">
        @csrf
        <input type="hidden" name="buku_id" value="{{ $buku->id }}">

        <div class="form-panel">
            <div class="detail-row">
                <span class="detail-label">Buku Dipilih</span>
                <span class="detail-value">{{ $buku->judul }}</span>
                <span class="detail-subvalue">{{ $buku->penulis }} • {{ $buku->penerbit }} • stok {{ $buku->stok }}</span>
            </div>

            <div class="form-grid">
                <div>
                    <label class="field-label" for="jumlah">Jumlah</label>
                    <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" max="{{ $buku->stok }}" class="field-input" required>
                </div>

                {{-- TANGGAL & JAM PINJAM --}}
                <div>
                    <label class="field-label" for="tanggal_pinjam">Tanggal Pinjam</label>
                    <input id="tanggal_pinjam" type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', now()->toDateString()) }}" class="field-input" required>
                </div>

                <div>
                    <label class="field-label" for="jam_pinjam">Jam Pinjam (HH:MM)</label>
                    <input id="jam_pinjam" type="time" name="jam_pinjam" value="{{ old('jam_pinjam', now()->format('H:i')) }}" class="field-input" required>
                </div>

                {{-- TANGGAL & JAM KEMBALI --}}
                <div>
                    <label class="field-label" for="tanggal_kembali">Tanggal Kembali</label>
                    <input id="tanggal_kembali" type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}" class="field-input" required>
                </div>

                <div>
                    <label class="field-label" for="jam_kembali">Jam Kembali (HH:MM)</label>
                    <input id="jam_kembali" type="time" name="jam_kembali" value="{{ old('jam_kembali', now()->format('H:i')) }}" class="field-input" required>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-primary">Kirim Pengajuan</button>
                <a href="{{ route('user.bukus') }}" class="btn-secondary">Batal</a>
            </div>
        </div>

        <aside class="guide-card text-center">
            <span class="badge-soft">Preview Buku</span>
            <div class="mt-5">
                <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="cover-hero">
            </div>
        </aside>
    </form>

    <script>
        // Auto-set jam_kembali same as jam_pinjam when jam_pinjam changes
        document.getElementById('jam_pinjam').addEventListener('change', function() {
            const jamKembaliInput = document.getElementById('jam_kembali');
            if (!jamKembaliInput.value) {
                jamKembaliInput.value = this.value;
            }
        });

        // Validasi tanggal kembali >= tanggal pinjam
        document.getElementById('tanggal_kembali').addEventListener('change', function() {
            const tglPinjam = new Date(document.getElementById('tanggal_pinjam').value);
            const tglKembali = new Date(this.value);

            if (tglKembali < tglPinjam) {
                alert('Tanggal kembali harus lebih besar dari tanggal pinjam');
                this.value = '';
            }
        });
    </script>
@endsection
