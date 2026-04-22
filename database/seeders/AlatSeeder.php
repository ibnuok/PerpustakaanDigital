<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriNovel = Kategori::firstOrCreate(['nama_kategori' => 'Novel']);
        $kategoriSains = Kategori::firstOrCreate(['nama_kategori' => 'Sains']);
        $kategoriTeknologi = Kategori::firstOrCreate(['nama_kategori' => 'Teknologi']);
        $kategoriSejarah = Kategori::firstOrCreate(['nama_kategori' => 'Sejarah']);

        $bukus = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'isbn' => '9789791227202',
                'stok' => 8,
                'kondisi' => 'baik',
                'kategori_id' => $kategoriNovel->id,
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Lentera Dipantara',
                'tahun_terbit' => 1980,
                'isbn' => '9789799731237',
                'stok' => 5,
                'kondisi' => 'baik',
                'kategori_id' => $kategoriNovel->id,
            ],
            [
                'judul' => 'Fisika Dasar',
                'penulis' => 'Halliday Resnick',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2019,
                'isbn' => '9786024341238',
                'stok' => 6,
                'kondisi' => 'baik',
                'kategori_id' => $kategoriSains->id,
            ],
            [
                'judul' => 'Biologi untuk SMA',
                'penulis' => 'Irnaningtyas',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2020,
                'isbn' => '9786022988756',
                'stok' => 7,
                'kondisi' => 'baik',
                'kategori_id' => $kategoriSains->id,
            ],
            [
                'judul' => 'Dasar-Dasar Pemrograman Web',
                'penulis' => 'Betha Sidik',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2021,
                'isbn' => '9786237131901',
                'stok' => 9,
                'kondisi' => 'baik',
                'kategori_id' => $kategoriTeknologi->id,
            ],
            [
                'judul' => 'Jaringan Komputer',
                'penulis' => 'Abdul Kadir',
                'penerbit' => 'Andi',
                'tahun_terbit' => 2018,
                'isbn' => '9789792958082',
                'stok' => 4,
                'kondisi' => 'rusak_ringan',
                'kategori_id' => $kategoriTeknologi->id,
            ],
            [
                'judul' => 'Sejarah Indonesia Modern',
                'penulis' => 'M.C. Ricklefs',
                'penerbit' => 'Gadjah Mada University Press',
                'tahun_terbit' => 2008,
                'isbn' => '9789794206884',
                'stok' => 5,
                'kondisi' => 'baik',
                'kategori_id' => $kategoriSejarah->id,
            ],
        ];

        foreach ($bukus as $buku) {
            Buku::updateOrCreate(['isbn' => $buku['isbn']], $buku);
        }
    }
}
