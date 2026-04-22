<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::firstOrCreate(
            ['nama_kategori' => 'Novel'],
            [
                'nama_kategori' => 'Novel',
                'deskripsi' => 'Koleksi novel fiksi untuk literasi siswa.',
            ]
        );

        Kategori::firstOrCreate(
            ['nama_kategori' => 'Sains'],
            [
                'nama_kategori' => 'Sains',
                'deskripsi' => 'Buku pelajaran dan referensi ilmu pengetahuan.',
            ]
        );

        Kategori::firstOrCreate(
            ['nama_kategori' => 'Teknologi'],
            [
                'nama_kategori' => 'Teknologi',
                'deskripsi' => 'Materi komputer, desain, dan teknologi informasi.',
            ]
        );

        Kategori::firstOrCreate(
            ['nama_kategori' => 'Sejarah'],
            [
                'nama_kategori' => 'Sejarah',
                'deskripsi' => 'Buku sejarah nasional dan dunia.',
            ]
        );
    }
}
