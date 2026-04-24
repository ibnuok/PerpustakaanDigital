<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengembalian',
        'denda',
        'denda_telat',
        'denda_kerusakan',
        'ada_kerusakan',
        'deskripsi_kerusakan',
        'status_pembayaran',
        'metode_pembayaran',
        'tanggal_pembayaran',
        'bukti_pembayaran',
        'catatan_penolakan',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'datetime',
        'tanggal_pembayaran' => 'datetime',
        'ada_kerusakan' => 'boolean',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function user()
    {
        return $this->peminjaman?->user;
    }

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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'belum_dibayar' => 'Belum Lunas',
            'pending_approval' => 'Menunggu Verifikasi',
            'sudah_dibayar' => 'Lunas',
            default => 'Tidak Diketahui',
        };
    }

    public function getMetodePembayaranLabelAttribute(): string
    {
        return match ($this->metode_pembayaran) {
            'tunai' => 'Tunai',
            'transfer' => 'Transfer Rekening',
            default => '-',
        };
    }

    public function getJenisDendaListAttribute(): array
    {
        $items = [];

        if (($this->denda_telat ?? 0) > 0) {
            $items[] = 'Denda Terlambat';
        }

        if (($this->denda_kerusakan ?? 0) > 0) {
            $items[] = 'Denda Kerusakan';
        }

        return $items;
    }

    public function getJenisDendaLabelAttribute(): string
    {
        $items = $this->jenis_denda_list;

        return $items === [] ? 'Tidak Ada Denda' : implode(' + ', $items);
    }

    public function getHariTerlambatAttribute(): int
    {
        if (! $this->peminjaman || ! $this->tanggal_pengembalian) {
            return 0;
        }

        return $this->peminjaman->calculateLateDays($this->tanggal_pengembalian);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status_pembayaran) {
            'belum_dibayar' => '<span class="badge bg-danger">Belum Lunas</span>',
            'pending_approval' => '<span class="badge bg-warning">Menunggu Verifikasi</span>',
            'sudah_dibayar' => '<span class="badge bg-success">Lunas</span>',
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
