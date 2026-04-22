@extends('layouts.portal')

@section('title', 'Tambah Akun')
@section('page_heading', 'Tambah Akun Baru')
@section('page_description', 'Daftarkan admin atau anggota baru secara langsung dari panel perpustakaan.')

@section('page_actions')
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Kembali ke Anggota</a>
@endsection

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST" class="form-shell">
        @csrf

        <div class="form-panel">
            <div class="form-grid">
                <div>
                    <label class="field-label" for="name">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="role">Role</label>
                    <select id="role" name="role" class="field-select" required>
                        <option value="">Pilih role</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="user" @selected(old('role') === 'user')>User</option>
                    </select>
                </div>
                <div>
                    <label class="field-label" for="password">Password</label>
                    <input id="password" type="password" name="password" class="field-input" required>
                </div>
                <div class="md:col-span-2">
                    <label class="field-label" for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="field-input" required>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-primary">Simpan Akun</button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Petunjuk Peran</span>
            <div class="stack-list mt-4">
                <div class="stack-item">
                    <strong class="block">Admin</strong>
                    <span class="detail-subvalue">Dapat mengelola buku, kategori, anggota, dan transaksi peminjaman.</span>
                </div>
                <div class="stack-item">
                    <strong class="block">User</strong>
                    <span class="detail-subvalue">Dapat melihat katalog, mengajukan pinjaman, dan mengembalikan buku.</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
