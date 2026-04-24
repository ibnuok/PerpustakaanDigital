# FITUR MANAJEMEN DENDA BUKU - DOKUMENTASI

## 📋 Ringkasan Fitur

Sistem manajemen denda perpustakaan yang komprehensif dengan fitur:
1. **Pemeriksaan Kerusakan Buku** oleh Admin
2. **Notifikasi Denda** otomatis ke User
3. **Pembayaran Denda** dengan bukti upload
4. **Approval Pembayaran** oleh Admin

---

## 🔧 INSTALASI

### 1. Jalankan Migration
```bash
php artisan migrate
```

Migration akan menambahkan kolom ke tabel `pengembalians`:
- `ada_kerusakan` (boolean) - Ada/tidaknya kerusakan
- `deskripsi_kerusakan` (text) - Deskripsi detail kerusakan
- `status_pembayaran` (enum) - Status: belum_dibayar, pending_approval, sudah_dibayar
- `tanggal_pembayaran` (timestamp) - Kapan user submit bukti
- `bukti_pembayaran` (string) - Nama file bukti pembayaran

### 2. Buat Folder Storage
```bash
mkdir -p storage/app/public/bukti_pembayaran
chmod 755 storage/app/public/bukti_pembayaran
```

### 3. Link Storage (jika belum)
```bash
php artisan storage:link
```

---

## 👨‍💼 FITUR ADMIN

### 1. Memeriksa Kerusakan Buku
**Lokasi:** Admin → Transaksi → Klik "Periksa" pada buku yang sudah dikembalikan

**Alur:**
1. Setelah user mengembalikan buku, status berubah menjadi "returned"
2. Admin klik tombol "Periksa"
3. Admin bisa memilih:
   - ✓ Buku dalam kondisi baik (tidak ada denda tambahan)
   - ✗ Buku ada kerusakan (denda +Rp 50.000)
4. Jika ada kerusakan, admin wajib isi deskripsi kerusakan
5. Total denda (keterlambatan + kerusakan) otomatis dihitung

**Denda:**
- Keterlambatan: Rp 10/detik
- Kerusakan Buku: Rp 50.000 (fixed)

### 2. Manajemen Pembayaran Denda
**Lokasi:** Admin → Denda

**Fitur:**
- Filter by Status: Belum Dibayar, Pending Approval, Sudah Dibayar
- Lihat detail pembayaran member
- Lihat preview bukti pembayaran
- Approve pembayaran
- Reject pembayaran (dengan alasan)

**Approval Process:**
1. User upload bukti pembayaran (status: pending_approval)
2. Admin review di Admin → Denda
3. Admin approve atau reject
4. Jika approve → status berubah "sudah_dibayar"
5. Jika reject → status kembali "belum_dibayar", user bisa upload ulang

---

## 👤 FITUR USER

### 1. Lihat Daftar Denda
**Lokasi:** User Dashboard → Menu → Denda

**Menampilkan:**
- Daftar semua denda yang belum/sedang dibayar
- Total denda keseluruhan
- Rincian denda: keterlambatan + kerusakan
- Status pembayaran

### 2. Bayar Denda
**Alur Pembayaran:**

1. **User klik "Bayar"** pada denda yang belum dibayar
2. **Lihat instruksi pembayaran** (rekening bank: BCA 1234567890)
3. **Transfer ke rekening perpustakaan** dengan nominal sesuai denda
4. **Upload bukti pembayaran** (screenshot/foto):
   - Format: JPG, PNG, GIF
   - Ukuran: max 2MB
   - Support drag & drop
5. **Submit** - status berubah "pending_approval"
6. **Tunggu approval admin** (max 1x24 jam)
7. **Pembayaran disetujui** - status berubah "sudah_dibayar"

### 3. Riwayat Pembayaran
**Lokasi:** User → Denda → Tab "Riwayat Pembayaran"

Menampilkan semua pembayaran denda yang sudah dilakukan.

---

## 📊 DATABASE SCHEMA

### Tabel: pengembalians
```
id                      integer (PK)
peminjaman_id           integer (FK)
tanggal_pengembalian    date
denda                   unsigned integer
ada_kerusakan           boolean (NEW)
deskripsi_kerusakan     text (NEW)
status_pembayaran       enum (NEW) - default: 'belum_dibayar'
tanggal_pembayaran      timestamp (NEW)
bukti_pembayaran        string (NEW)
created_at              timestamp
updated_at              timestamp
```

### Enum Status Pembayaran
- `belum_dibayar` - Denda belum dibayar
- `pending_approval` - Menunggu verifikasi admin
- `sudah_dibayar` - Pembayaran sudah disetujui

---

## 🛣️ ROUTES

### Admin Routes (prefix: /admin)
```
GET    /denda                              - List denda
GET    /denda/{pengembalian}               - Detail denda
POST   /denda/{pengembalian}/approve       - Approve pembayaran
POST   /denda/{pengembalian}/reject        - Reject pembayaran
GET    /denda/{pengembalian}/bukti         - View bukti pembayaran
GET    /denda/{pengembalian}/download-bukti - Download bukti

GET    /peminjaman/{peminjaman}/check-damage      - Form cek kerusakan
POST   /peminjaman/{peminjaman}/save-damage       - Simpan cek kerusakan
```

### User Routes (prefix: /user)
```
GET    /denda                              - List denda saya
GET    /denda/{pengembalian}               - Detail denda
GET    /denda/{pengembalian}/payment       - Form pembayaran
POST   /denda/{pengembalian}/submit-payment - Submit bukti pembayaran
GET    /denda/history                      - Riwayat pembayaran
```

---

## 🎨 CONTROLLERS

### Admin Controllers
- `AdminDendaController@index()` - List denda (filter by status)
- `AdminDendaController@show()` - Detail denda
- `AdminDendaController@approve()` - Approve pembayaran
- `AdminDendaController@reject()` - Reject pembayaran
- `PeminjamanController@checkDamageForm()` - Tampilkan form cek kerusakan
- `PeminjamanController@saveDamage()` - Simpan hasil cek kerusakan

### User Controllers
- `UserDendaController@index()` - List denda saya
- `UserDendaController@show()` - Detail denda
- `UserDendaController@paymentForm()` - Form pembayaran
- `UserDendaController@submitPayment()` - Submit bukti pembayaran
- `UserDendaController@history()` - Riwayat pembayaran

---

## 📁 VIEW FILES

### Admin Views
- `resources/views/admin/denda/index.blade.php` - List denda
- `resources/views/admin/denda/show.blade.php` - Detail & approval
- `resources/views/admin/peminjaman/check-damage.blade.php` - Form cek kerusakan

### User Views
- `resources/views/user/denda/index.blade.php` - List denda
- `resources/views/user/denda/show.blade.php` - Detail denda
- `resources/views/user/denda/payment.blade.php` - Form pembayaran
- `resources/views/user/denda/history.blade.php` - Riwayat pembayaran

---

## ⚙️ MODEL METHODS

### Pengembalian Model
```php
public function isBelumDibayar(): bool
public function isPendingApproval(): bool
public function isSudahDibayar(): bool
public function getStatusBadgeAttribute()
public function getDendaBadgeAttribute()
```

### Peminjaman Model
```php
public function isTerlambat(): bool        // Sudah ada, untuk cek keterlambatan
public function dendaRealtime(): int       // Sudah ada, hitung denda realtime
```

---

## 📝 CATATAN PENTING

1. **Denda Keterlambatan**: Rp 10 per detik
2. **Denda Kerusakan**: Rp 50.000 (fixed)
3. **File Bukti Pembayaran** disimpan di: `storage/app/public/bukti_pembayaran/`
4. **Constraint**: `peminjaman_id` unique di `pengembalians` (satu peminjaman hanya punya satu pengembalian)
5. **Error Lama**: Sudah diperbaiki - whitespace sebelum `<?php` di Peminjaman.php

---

## 🔄 WORKFLOW LENGKAP

### User Telat + Ada Kerusakan

```
1. User meminjam buku (status: pending)
   ↓
2. Admin approve (status: dipinjam)
   ↓
3. User kembali terlambat + rusak (status: returned)
   → Denda otomatis: Rp 10/detik (keterlambatan)
   ↓
4. Admin periksa kerusakan
   → Denda tambahan: Rp 50.000 (kerusakan)
   → Total denda: Rp X (gabungan)
   → Status pembayaran: belum_dibayar
   ↓
5. User lihat denda & klik "Bayar"
   ↓
6. User transfer ke rekening perpustakaan
   ↓
7. User upload bukti pembayaran
   → Status pembayaran: pending_approval
   ↓
8. Admin review & approve
   → Status pembayaran: sudah_dibayar
   ↓
9. Selesai! User sudah lunas
```

---

## 🎯 TESTING CHECKLIST

- [ ] Migration berhasil dijalankan
- [ ] Folder storage terbuat dan permission OK
- [ ] Admin bisa cek kerusakan buku
- [ ] Denda otomatis dihitung (keterlambatan + kerusakan)
- [ ] User lihat denda di dashboard
- [ ] User bisa upload bukti pembayaran
- [ ] Admin bisa review & approve pembayaran
- [ ] Admin bisa reject pembayaran
- [ ] File bukti tersimpan dengan benar
- [ ] Routes semua berjalan lancar

---

**Dibuat:** 24 April 2026
**Status:** ✅ Ready to Deploy
