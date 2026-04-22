@extends('layouts.portal')

@section('title', 'Edit Akun')
@section('page_heading', 'Edit Akun')
@section('page_description', 'Perbarui identitas akun dan peran pengguna sesuai kebutuhan pengelolaan perpustakaan.')

@section('page_actions')
    <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">Lihat Detail</a>
@endsection

@section('content')
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="form-shell">
        @csrf
        @method('PUT')

        <div class="form-panel">
            <div class="form-grid">
                <div>
                    <label class="field-label" for="name">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="field-input" required>
                </div>
                <div>
                    <label class="field-label" for="role">Role</label>
                    <select id="role" name="role" class="field-select" required>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                        <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                    </select>
                </div>
                <div>
                    <label class="field-label" for="password">Password Baru</label>
                    <input id="password" type="password" name="password" class="field-input" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-primary">Update Akun</button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </div>

        <aside class="guide-card">
            <span class="badge-soft">Ringkasan Akun</span>
            <div class="stack-list mt-4">
                <div class="stack-item">
                    <strong class="block">{{ $user->name }}</strong>
                    <span class="detail-subvalue">{{ $user->email }}</span>
                </div>
                <div class="stack-item">
                    <strong class="block">Role Saat Ini</strong>
                    <span class="detail-subvalue">{{ strtoupper($user->role) }}</span>
                </div>
            </div>
        </aside>
    </form>
@endsection
