@extends('layouts.portal')

@section('title', 'Detail Akun')
@section('page_heading', 'Detail Akun')
@section('page_description', 'Lihat informasi lengkap akun admin atau anggota sebelum melakukan perubahan data.')

@section('page_actions')
    <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">Edit Akun</a>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Kembali</a>
@endsection

@section('content')
    <div class="detail-card">
        <span class="badge-soft">Profil Pengguna</span>
        <div class="detail-grid mt-5">
            <div class="detail-row">
                <span class="detail-label">Nama</span>
                <span class="detail-value">{{ $user->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Role</span>
                <span class="detail-value">{{ strtoupper($user->role) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Bergabung Sejak</span>
                <span class="detail-value">{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</span>
            </div>
        </div>
    </div>
@endsection
