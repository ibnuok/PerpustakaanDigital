<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    public const DENDA_PER_HARI = 5000;
    public const DENDA_KERUSAKAN = 25000;

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

    public function calculateLateDays(?CarbonInterface $returnedAt = null): int
    {
        $returnedAt ??= now();

        if ($returnedAt->lessThanOrEqualTo($this->tanggal_kembali)) {
            return 0;
        }

        $lateSeconds = $this->tanggal_kembali->diffInSeconds($returnedAt);

        return max(1, (int) ceil($lateSeconds / 86400));
    }

    public function calculateLateFine(?CarbonInterface $returnedAt = null): int
    {
        return $this->calculateLateDays($returnedAt) * self::DENDA_PER_HARI;
    }

    public function calculateFine(?CarbonInterface $returnedAt = null): int
    {
        return $this->calculateLateFine($returnedAt);
    }

    public function getDendaAttribute()
    {
        if ($this->isReturned()) {
            return $this->pengembalian->denda ?? 0;
        }

        if (!$this->isTerlambat()) {
            return 0;
        }

        return $this->calculateLateFine(now());
    }

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

    public function getSisaWaktuLabelAttribute()
    {
        if ($this->isReturned()) {
            return $this->denda > 0 ? 'Terlambat' : 'Tepat waktu';
        }

        if ($this->isTerlambat()) {
            return 'Terlambat';
        }

        return $this->sisa_waktu;
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->isReturned()) {
            return '<span class="badge badge-returned">Dikembalikan</span>';
        }

        if ($this->isTerlambat()) {
            return '<span class="badge" style="background:red;color:white;">TERLAMBAT</span>';
        }

        if ($this->isPending()) {
            return '<span class="badge bg-yellow-100 text-yellow-700">Menunggu</span>';
        }

        return '<span class="badge badge-approved">Dipinjam</span>';
    }

    public function getTanggalPinjamFormatAttribute()
    {
        return $this->tanggal_pinjam->format('d M Y H:i:s');
    }

    public function getTanggalKembaliFormatAttribute()
    {
        return $this->tanggal_kembali->format('d M Y H:i:s');
    }
}
