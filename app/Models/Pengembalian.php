<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengembalian',
        'denda',
        'ada_kerusakan',
        'deskripsi_kerusakan',
        'status_pembayaran',
        'tanggal_pembayaran',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'date',
        'tanggal_pembayaran' => 'datetime',
        'ada_kerusakan' => 'boolean',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function user()
    {
        return $this->peminjaman->user();
    }

    /* ================= STATUS ================= */

    public function isBelumDibayar(): bool
    {
        return $this->status_pembayaran === 'belum_dibayar';
    }

    public function isPendingApproval(): bool
    {
        return $this->status_pembayaran === 'pending_approval';
    }

    public function isSudahDibayar(): bool
    {
        return $this->status_pembayaran === 'sudah_dibayar';
    }

    /* ================= BADGE ================= */

    public function getStatusBadgeAttribute()
    {
        return match ($this->status_pembayaran) {
            'belum_dibayar' => '<span class="badge bg-danger">Belum Dibayar</span>',
            'pending_approval' => '<span class="badge bg-warning">Pending Approval</span>',
            'sudah_dibayar' => '<span class="badge bg-success">Sudah Dibayar</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    public function getDendaBadgeAttribute()
    {
        if ($this->denda > 0) {
            return '<span class="badge bg-danger">Rp ' . number_format($this->denda, 0, ',', '.') . '</span>';
        }
        return '<span class="badge bg-success">Tidak Ada Denda</span>';
    }
}
