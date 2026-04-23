<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Perpustakaan',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'anggota@example.com'],
            [
                'name' => 'Anggota Perpustakaan',
                'password' => bcrypt('user12345'),
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('user12345'),
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'ani@example.com'],
            [
                'name' => 'Ani Wijaya',
                'password' => bcrypt('user12345'),
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'citra@example.com'],
            [
                'name' => 'Citra Kusuma',
                'password' => bcrypt('user12345'),
                'role' => 'user',
            ]
        );

        $this->call(KategoriSeeder::class);
        $this->call(AlatSeeder::class);

        // Seed books with images
        $this->seedBooks();
    }

    private function seedBooks(): void
    {
        $novels = Kategori::where('nama_kategori', 'Novel')->first();
        $sains = Kategori::where('nama_kategori', 'Sains')->first();
        $teknologi = Kategori::where('nama_kategori', 'Teknologi')->first();
        $sejarah = Kategori::where('nama_kategori', 'Sejarah')->first();

        $bukuData = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang',
                'tahun_terbit' => 2005,
                'isbn' => '979-14-02223-1',
                'stok' => 5,
                'kondisi' => 'baik',
                'kategori_id' => $novels?->id,
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3af521d1?w=400&h=600&fit=crop',
                'deskripsi' => 'Laskar Pelangi adalah novel tentang perjuangan anak-anak berbakat dari keluarga miskin di Belitung. Mereka berdedikasi tinggi untuk mencapai mimpi dan meraih prestasi. Novel ini menginspirasi dengan kisah nyata persahabatan, keuletan, dan harapan.'
            ],
            [
                'judul' => 'Hari Kristen',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang',
                'tahun_terbit' => 2010,
                'isbn' => '979-14-02223-2',
                'stok' => 3,
                'kondisi' => 'baik',
                'kategori_id' => $novels?->id,
                'image' => 'https://images.unsplash.com/photo-1507842217343-583f20270319?w=400&h=600&fit=crop',
                'deskripsi' => 'Lanjutan dari Laskar Pelangi, Hari Kristen mengikuti perjalanan seorang tokoh utama saat memasuki fase baru dalam hidupnya. Kisah ini menceritakan tentang penemuan diri, cinta, dan makna kehidupan yang sesungguhnya.'
            ],
            [
                'judul' => 'Bumi',
                'penulis' => 'Tere Liye',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2014,
                'isbn' => '978-979-866-249-5',
                'stok' => 7,
                'kondisi' => 'baik',
                'kategori_id' => $novels?->id,
                'image' => 'https://images.unsplash.com/photo-1519995456281-b06c9d2583fa?w=400&h=600&fit=crop',
                'deskripsi' => 'Bumi adalah bagian pertama dari serial Anak Bulan yang menceritakan petualangan supernatural. Novel ini menggabungkan fantasi, misteri, dan keluarga dalam alur yang menarik dan penuh kejutan.'
            ],
            [
                'judul' => 'Bulan',
                'penulis' => 'Tere Liye',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2015,
                'isbn' => '978-979-866-249-6',
                'stok' => 6,
                'kondisi' => 'baik',
                'kategori_id' => $novels?->id,
                'image' => 'https://images.unsplash.com/photo-1507842217343-583f20270319?w=400&h=600&fit=crop',
                'deskripsi' => 'Bulan melanjutkan cerita dari Bumi dengan menghadirkan lebih banyak misteri dan petualangan yang mendebarkan. Karakter-karakter dikembangkan dengan baik dan alur cerita semakin kompleks.'
            ],
            [
                'judul' => 'Fisika Dasar',
                'penulis' => 'Yohanes Surya',
                'penerbit' => 'Andi Offset',
                'tahun_terbit' => 2015,
                'isbn' => '978-979-761-542-1',
                'stok' => 8,
                'kondisi' => 'baik',
                'kategori_id' => $sains?->id,
                'image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=400&h=600&fit=crop',
                'deskripsi' => 'Buku fisika dasar yang komprehensif mencakup mekanika, termodinamika, optik, dan elektromagnetisme. Dilengkapi dengan contoh-contoh praktis, soal-soal latihan, dan penjelasan yang mudah dipahami.'
            ],
            [
                'judul' => 'Biologi SMA Jilid 1',
                'penulis' => 'Irnaningtyas',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2016,
                'isbn' => '978-979-018-932-5',
                'stok' => 9,
                'kondisi' => 'baik',
                'kategori_id' => $sains?->id,
                'image' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400&h=600&fit=crop',
                'deskripsi' => 'Jilid 1 dari serial biologi SMA yang menguraikan materi-materi dasar biologi termasuk sel, jaringan, dan sistem organ. Buku ini dilengkapi dengan gambar dan diagram yang informatif.'
            ],
            [
                'judul' => 'Kimia Dasar',
                'penulis' => 'Sugiyarto',
                'penerbit' => 'Andi Offset',
                'tahun_terbit' => 2014,
                'isbn' => '978-979-761-621-3',
                'stok' => 5,
                'kondisi' => 'baik',
                'kategori_id' => $sains?->id,
                'image' => 'https://images.unsplash.com/photo-1564329007681-c8bdb3c8e33d?w=400&h=600&fit=crop',
                'deskripsi' => 'Pengantar lengkap tentang konsep-konsep dasar kimia, mulai dari atom, molekul, reaksi kimia, hingga stokiometri. Dilengkapi dengan praktikum sederhana dan soal-soal pembahasan.'
            ],
            [
                'judul' => 'Pemrograman Python',
                'penulis' => 'Budi Raharjo',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2018,
                'isbn' => '978-979-761-819-4',
                'stok' => 6,
                'kondisi' => 'baik',
                'kategori_id' => $teknologi?->id,
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=600&fit=crop',
                'deskripsi' => 'Panduan lengkap belajar Python dari pemula hingga mahir. Mencakup syntax dasar, struktur data, fungsi, OOP, file handling, dan library-library populer seperti Pandas dan NumPy.'
            ],
            [
                'judul' => 'Web Development Fundamental',
                'penulis' => 'Ade Imam Kamarudin',
                'penerbit' => 'Elex Media',
                'tahun_terbit' => 2019,
                'isbn' => '978-979-761-821-7',
                'stok' => 7,
                'kondisi' => 'baik',
                'kategori_id' => $teknologi?->id,
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=600&fit=crop',
                'deskripsi' => 'Fondasi web development yang mencakup HTML, CSS, JavaScript, dan konsep-konsep penting dalam membangun website modern. Cocok untuk pemula yang ingin belajar web development.'
            ],
            [
                'judul' => 'Artificial Intelligence Basics',
                'penulis' => 'Agus Kurniawan',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2020,
                'isbn' => '978-979-761-823-1',
                'stok' => 4,
                'kondisi' => 'baik',
                'kategori_id' => $teknologi?->id,
                'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=400&h=600&fit=crop',
                'deskripsi' => 'Pengenalan dasar tentang artificial intelligence, machine learning, deep learning, dan aplikasi-aplikasinya. Buku ini menyediakan pemahaman teoritis dan praktik dengan contoh-contoh nyata.'
            ],
            [
                'judul' => 'Sejarah Indonesia Kelas X',
                'penulis' => 'Syaiful Bahri',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2016,
                'isbn' => '978-979-018-945-5',
                'stok' => 10,
                'kondisi' => 'baik',
                'kategori_id' => $sejarah?->id,
                'image' => 'https://images.unsplash.com/photo-1507842217343-583f20270319?w=400&h=600&fit=crop',
                'deskripsi' => 'Materi sejarah Indonesia untuk kelas X yang mencakup sejarah nusantara, masa kolonial, pergerakan nasional, dan awal kemerdekaan. Dilengkapi dengan peta, gambar, dan pertanyaan reflektif.'
            ],
            [
                'judul' => 'Peradaban Dunia',
                'penulis' => 'Suganda Dua',
                'penerbit' => 'Andi Offset',
                'tahun_terbit' => 2015,
                'isbn' => '978-979-761-645-9',
                'stok' => 6,
                'kondisi' => 'baik',
                'kategori_id' => $sejarah?->id,
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3af521d1?w=400&h=600&fit=crop',
                'deskripsi' => 'Sejarah perkembangan peradaban dunia dari zaman kuno hingga modern. Mencakup berbagai aspek budaya, politik, ekonomi, dan sosial dari berbagai peradaban di dunia.'
            ],
        ];

        foreach ($bukuData as $buku) {
            Buku::firstOrCreate(
                ['isbn' => $buku['isbn']],
                $buku
            );
        }
    }
}
