<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kategoris')) {
            try {
                Schema::table('kategoris', function (Blueprint $table) {
                    $table->string('deskripsi')->nullable()->after('nama_kategori');
                });
            } catch (\Throwable $e) {
                // Kolom bisa saja sudah ada pada database lama.
            }
        }

        if (! Schema::hasTable('bukus')) {
            Schema::create('bukus', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->string('penulis')->nullable();
                $table->string('penerbit')->nullable();
                $table->year('tahun_terbit')->nullable();
                $table->string('isbn')->nullable()->unique();
                $table->integer('stok')->default(0);
                $table->string('kondisi')->default('baik');
                $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('alats')) {
            $alatRows = DB::table('alats')->get();

            foreach ($alatRows as $alat) {
                DB::table('bukus')->updateOrInsert(
                    ['id' => $alat->id],
                    [
                        'judul' => $alat->nama_alat,
                        'penulis' => null,
                        'penerbit' => null,
                        'tahun_terbit' => null,
                        'isbn' => null,
                        'stok' => $alat->stok,
                        'kondisi' => strtolower($alat->kondisi ?? 'baik'),
                        'kategori_id' => $alat->kategori_id ?? null,
                        'created_at' => $alat->created_at ?? now(),
                        'updated_at' => $alat->updated_at ?? now(),
                    ]
                );
            }
        }

        if (! Schema::hasTable('peminjamans')) {
            Schema::create('peminjamans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
                $table->integer('jumlah')->default(1);
                $table->date('tanggal_pinjam');
                $table->date('tanggal_kembali');
                $table->string('status')->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('peminjamen')) {
            $rows = DB::table('peminjamen')->get();

            foreach ($rows as $row) {
                DB::table('peminjamans')->updateOrInsert(
                    ['id' => $row->id],
                    [
                        'user_id' => $row->user_id,
                        'buku_id' => $row->alat_id,
                        'jumlah' => $row->jumlah ?? 1,
                        'tanggal_pinjam' => $row->tanggal_pinjam,
                        'tanggal_kembali' => $row->tanggal_kembali,
                        'status' => $row->status ?? 'pending',
                        'approved_by' => $row->approved_by ?? null,
                        'approved_at' => $row->approved_at ?? null,
                        'created_at' => $row->created_at ?? now(),
                        'updated_at' => $row->updated_at ?? now(),
                    ]
                );
            }
        }

        Schema::dropIfExists('peminjamen');
        Schema::dropIfExists('alats');
    }

    public function down(): void
    {
        if (! Schema::hasTable('alats')) {
            Schema::create('alats', function (Blueprint $table) {
                $table->id();
                $table->string('nama_alat');
                $table->integer('stok');
                $table->string('kondisi');
                $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('peminjamen')) {
            Schema::create('peminjamen', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedBigInteger('alat_id');
                $table->integer('jumlah')->default(1);
                $table->date('tanggal_pinjam');
                $table->date('tanggal_kembali');
                $table->string('status');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }

        Schema::dropIfExists('peminjamans');
        Schema::dropIfExists('bukus');
    }
};
