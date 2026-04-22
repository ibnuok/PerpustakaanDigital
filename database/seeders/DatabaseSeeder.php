<?php

namespace Database\Seeders;

use App\Models\User;
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
    }
}
