<section class="form-panel">
    <div>
        <span class="badge-soft">Keamanan</span>
        <h2 class="mt-4 text-2xl font-bold">Ubah Password</h2>
        <p class="mt-2 text-sm leading-7" style="color: var(--muted);">
            Gunakan password yang kuat agar akun perpustakaan tetap aman saat dipakai di jaringan lokal sekolah.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="form-panel" style="padding: 0; box-shadow: none; border: none; background: transparent;">
        @csrf
        @method('put')

        <div class="form-grid">
            <div class="md:col-span-2">
                <label class="field-label" for="update_password_current_password">Password Saat Ini</label>
                <input id="update_password_current_password" name="current_password" type="password" class="field-input" autocomplete="current-password">
            </div>
            <div>
                <label class="field-label" for="update_password_password">Password Baru</label>
                <input id="update_password_password" name="password" type="password" class="field-input" autocomplete="new-password">
            </div>
            <div>
                <label class="field-label" for="update_password_password_confirmation">Konfirmasi Password Baru</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="field-input" autocomplete="new-password">
            </div>
        </div>

        <div class="action-row">
            <button type="submit" class="btn-primary">Simpan Password</button>
            @if (session('status') === 'password-updated')
                <span class="badge-soft">Password berhasil diperbarui</span>
            @endif
        </div>
    </form>
</section>
