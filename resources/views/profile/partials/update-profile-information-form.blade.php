<section class="form-panel">
    <div>
        <span class="badge-soft">Profil Akun</span>
        <h2 class="mt-4 text-2xl font-bold">Informasi Dasar</h2>
        <p class="mt-2 text-sm leading-7" style="color: var(--muted);">
            Ubah nama dan email akun Anda agar identitas yang tampil di aplikasi selalu sesuai.
        </p>
    </div>

    <form method="post" action="{{ route('profile.update') }}" class="form-panel" style="padding: 0; box-shadow: none; border: none; background: transparent;">
        @csrf
        @method('patch')

        <div class="form-grid">
            <div>
                <label class="field-label" for="name">Nama</label>
                <input id="name" name="name" type="text" class="field-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            </div>
            <div>
                <label class="field-label" for="email">Email</label>
                <input id="email" name="email" type="email" class="field-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
            </div>
        </div>

        <div class="action-row">
            <button type="submit" class="btn-primary">Simpan Profil</button>
            @if (session('status') === 'profile-updated')
                <span class="badge-soft">Profil berhasil diperbarui</span>
            @endif
        </div>
    </form>
</section>
