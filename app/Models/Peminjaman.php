<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    // ✅ UBAH KE PER HARI
    public const DENDA_PER_HARI = 1000;

    protected $fillable = [
        'user_id',
        'buku_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    /* ================= RELASI ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    /* ================= STATUS ================= */

    public function isDipinjam(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function isTerlambat(): bool
    {
        return $this->isDipinjam() && now()->gt($this->tanggal_kembali);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function isLate(): bool
    {
        return $this->isTerlambat();
    }

    /* ================= DENDA (PER HARI) ================= */

    public function getDendaAttribute()
    {
        // kalau sudah dikembalikan
        if ($this->isReturned()) {
            return $this->pengembalian->denda ?? 0;
        }

        // kalau belum telat
        if (!$this->isTerlambat()) {
            return 0;
        }

        // hitung hari telat
        $hariTelat = $this->tanggal_kembali->diffInDays(now());

        return $hariTelat * self::DENDA_PER_HARI;
    }

    /* ================= SISA WAKTU ================= */

    public function sisaDetik(): int
    {
        return now()->diffInSeconds($this->tanggal_kembali, false);
    }

    public function getSisaWaktuAttribute(): string
    {
        $diff = $this->sisaDetik();

        if ($diff <= 0) {
            return '00:00:00';
        }

        $h = floor($diff / 3600);
        $m = floor(($diff % 3600) / 60);
        $s = $diff % 60;

        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    /* ================= LABEL ================= */

    public function getSisaWaktuLabelAttribute()
    {
        if ($this->isReturned()) {
            return $this->denda > 0
                ? 'Terlambat'
                : 'Tepat waktu';
        }

        if ($this->isTerlambat()) {
            return 'Terlambat';
        }

        return $this->sisa_waktu;
    }

    /* ================= BADGE ================= */

    public function getStatusBadgeAttribute()
    {
        if ($this->isReturned()) {
            return '<span class="badge badge-returned">Dikembalikan</span>';
        }

        if ($this->isTerlambat()) {
            return '<span class="badge" style="background:red;color:white;">TERLAMBAT</span>';
        }

        return '<span class="badge badge-approved">Dipinjam</span>';
    }

    /* ================= FORMAT ================= */

    public function getTanggalPinjamFormatAttribute()
    {
        return $this->tanggal_pinjam->format('d M Y H:i:s');
    }

    public function getTanggalKembaliFormatAttribute()
    {
        return $this->tanggal_kembali->format('d M Y H:i:s');
    }
}