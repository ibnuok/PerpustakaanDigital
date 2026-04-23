<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'stok',
        'kondisi',
        'kategori_id',
        'image',
        'deskripsi'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    public function getCoverUrlAttribute(): string
    {
        $palette = $this->coverPalette();
        $judul = $this->coverText($this->judul ?: 'Buku Perpustakaan', 18);
        $penulis = $this->coverText($this->penulis ?: 'Perpustakaan Digital', 24);
        $kategori = $this->coverText($this->kategori?->nama_kategori ?: 'Koleksi Sekolah', 26);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="320" height="460" viewBox="0 0 320 460">
  <defs>
    <linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$palette['start']}"/>
      <stop offset="100%" stop-color="{$palette['end']}"/>
    </linearGradient>
  </defs>
  <rect width="320" height="460" rx="30" fill="url(#g)"/>
  <rect x="22" y="22" width="276" height="416" rx="26" fill="rgba(255,255,255,0.09)"/>
  <rect x="42" y="48" width="66" height="8" rx="4" fill="rgba(255,255,255,0.55)"/>
  <text x="42" y="132" fill="#ffffff" font-size="28" font-family="Segoe UI, Arial, sans-serif" font-weight="700">{$judul}</text>
  <text x="42" y="178" fill="rgba(255,255,255,0.92)" font-size="18" font-family="Segoe UI, Arial, sans-serif">{$penulis}</text>
  <rect x="42" y="338" width="236" height="42" rx="21" fill="rgba(255,255,255,0.17)"/>
  <text x="62" y="365" fill="#ffffff" font-size="15" font-family="Segoe UI, Arial, sans-serif" font-weight="600">{$kategori}</text>
  <text x="42" y="414" fill="rgba(255,255,255,0.88)" font-size="14" font-family="Segoe UI, Arial, sans-serif">Perpustakaan Digital Sekolah</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    protected function coverPalette(): array
    {
        $palettes = [
            ['start' => '#15314B', 'end' => '#315F84'],
            ['start' => '#6D4C41', 'end' => '#A1765D'],
            ['start' => '#204B57', 'end' => '#3D7A87'],
            ['start' => '#5C3B58', 'end' => '#9E658E'],
            ['start' => '#2B4A3D', 'end' => '#4F8A73'],
            ['start' => '#7A4A22', 'end' => '#C78952'],
        ];

        return $palettes[$this->id % count($palettes)];
    }

    protected function coverText(string $text, int $limit): string
    {
        $clean = trim($text);

        if (mb_strlen($clean) <= $limit) {
            return e($clean);
        }

        return e(mb_substr($clean, 0, $limit - 1) . '...');
    }
}
