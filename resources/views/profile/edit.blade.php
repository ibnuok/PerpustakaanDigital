@extends('layouts.portal')

@section('title', 'Profil')
@section('page_heading', 'Pengaturan Profil')
@section('page_description', 'Perbarui nama, email, password, atau hapus akun Anda dari satu halaman yang lebih rapi dan nyaman dipakai.')

@section('content')
    <div class="section-grid">
        <div class="stack-list">
            @include('profile.partials.update-profile-information-form')
            @include('profile.partials.update-password-form')
        </div>

        <div>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
