@extends('layouts.portal')

@section('title', 'Kelola Anggota')
@section('page_heading', 'Kelola Anggota')
@section('page_description', 'Atur akun admin dan anggota perpustakaan, lengkap dengan pencarian cepat dan filter berdasarkan peran.')

@section('page_actions')
    <a href="{{ route('admin.users.create') }}" class="btn-primary">Tambah Akun</a>
@endsection

@section('content')
    <form method="GET" class="filter-panel">
        <div class="filter-grid">
            <input type="text" name="search" value="{{ request('search') }}" class="field-input" placeholder="Cari nama atau email">
            <select name="role" class="field-select">
                <option value="">Semua role</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                <option value="user" @selected(request('role') === 'user')>User</option>
            </select>
            <button class="btn-primary">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-wrap mt-6">
        <div class="table-head">
            <strong>Daftar Akun</strong>
            <p class="mt-2 text-sm" style="color: var(--muted);">Lihat semua admin dan anggota dalam satu tampilan yang rapi dan mudah dikelola.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white text-sm">
                <thead class="bg-stone-50 text-left">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge-soft">{{ strtoupper($user->role) }}</span>
                            </td>
                            <td>{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                            <td>
                                <div class="action-row" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">Detail</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">Belum ada akun yang sesuai dengan pencarian.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
@endsection
