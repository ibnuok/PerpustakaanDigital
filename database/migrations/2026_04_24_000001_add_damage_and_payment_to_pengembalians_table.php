<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            // Kolom untuk kerusakan buku
            $table->boolean('ada_kerusakan')->default(false)->after('denda');
            $table->text('deskripsi_kerusakan')->nullable()->after('ada_kerusakan');
            
            // Kolom untuk status pembayaran denda
            $table->enum('status_pembayaran', ['belum_dibayar', 'pending_approval', 'sudah_dibayar'])->default('belum_dibayar')->after('deskripsi_kerusakan');
            $table->timestamp('tanggal_pembayaran')->nullable()->after('status_pembayaran');
            $table->string('bukti_pembayaran')->nullable()->after('tanggal_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $table->dropColumn([
                'ada_kerusakan',
                'deskripsi_kerusakan',
                'status_pembayaran',
                'tanggal_pembayaran',
                'bukti_pembayaran'
            ]);
        });
    }
};
