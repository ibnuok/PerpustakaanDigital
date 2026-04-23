@extends('layouts.portal')

@section('title', 'Tambah Transaksi')
@section('page_heading', 'Tambah Transaksi Peminjaman')
@section('page_description', 'Buat transaksi baru untuk anggota secara cepat dengan memilih akun, buku, jumlah, dan tanggal peminjaman.')

@section('page_actions')
    <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Kembali ke Transaksi</a>
@endsection

@section('content')
    <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="form-shell">
        @csrf

        <div class="form-panel">
            <div class="form-grid">
                <div>
                    <label class="field-label" for="user_id">Anggota</label>
                    <select id="user_id" name="user_id" class="field-select" required>
                        <option value="">Pilih anggota</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} - {{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label" for="buku_id">Buku</label>
                    <select id="buku_id" name="buku_id" class="field-select" required>
                        <option value="">Pilih buku</option>
                        @foreach ($bukus as $buku)
                            <option value="{{ $buku->id }}" @selected(old('buku_id') == $buku->id)>{{ $buku->judul }} - stok {{ $buku->stok }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label" for="jumlah">Jumlah</label>
                    <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" class="field-input" required>
                </div>
                <div class="md:col-span-2">
                <label class="field-label">Durasi Peminjaman</label>

                <select name="durasi" class="field-select" required>
                    <option value="">Pilih durasi</option>
                    <option value="60">1 Menit</option>
                    <option value="300">5 Menit</option>
                    <option value="600">10 Menit</option>
                    <option value="1800">30 Menit</option>
                    <option value="3600">1 Jam</option>
                    <option value="7200">2 Jam</option>
                    <option value="86400">1 Hari</option>
                </select>

                <small style="color: gray;">
                    Sistem akan otomatis menghitung waktu kembali secara realtime
                </small>
            </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-primary">Simpan Transaksi</button>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Alur Cepat</span>
            <div class="stack-list mt-4">
                <div class="stack-item">
                    <strong class="block">Pilih anggota aktif</strong>
                    <span class="detail-subvalue">Pastikan akun yang dipilih memang akan melakukan peminjaman.</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Sesuaikan dengan stok</strong>
                    <span class="detail-subvalue">Jumlah buku tidak boleh melebihi stok koleksi yang tersedia.</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Transaksi masuk sebagai pending</strong>
                    <span class="detail-subvalue">Setelah dibuat, transaksi tetap bisa Anda setujui dari halaman daftar transaksi.</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
