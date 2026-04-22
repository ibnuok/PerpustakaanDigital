<section class="guide-card">
    <div>
        <span class="badge-soft" style="background: #fff1f4; color: #c2255c;">Zona Hati-Hati</span>
        <h2 class="mt-4 text-2xl font-bold">Hapus Akun</h2>
        <p class="mt-2 text-sm leading-7" style="color: var(--muted);">
            Jika akun dihapus, seluruh akses masuk akan hilang. Masukkan password untuk mengonfirmasi tindakan ini.
        </p>
    </div>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-5">
        @csrf
        @method('delete')

        <label class="field-label" for="delete_password">Password</label>
        <input id="delete_password" name="password" type="password" class="field-input" placeholder="Masukkan password akun Anda">

        <div class="action-row mt-4">
            <button type="submit" class="btn-danger" onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus Akun</button>
        </div>
    </form>
</section>
