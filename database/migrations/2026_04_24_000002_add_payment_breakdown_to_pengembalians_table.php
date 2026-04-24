<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            if (! Schema::hasColumn('pengembalians', 'denda_telat')) {
                $table->unsignedInteger('denda_telat')->default(0)->after('denda');
            }

            if (! Schema::hasColumn('pengembalians', 'denda_kerusakan')) {
                $table->unsignedInteger('denda_kerusakan')->default(0)->after('denda_telat');
            }

            if (! Schema::hasColumn('pengembalians', 'metode_pembayaran')) {
                $table->enum('metode_pembayaran', ['tunai', 'transfer'])->nullable()->after('status_pembayaran');
            }

            if (! Schema::hasColumn('pengembalians', 'catatan_penolakan')) {
                $table->text('catatan_penolakan')->nullable()->after('bukti_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengembalians', function (Blueprint $table) {
            $columns = [];

            foreach (['denda_telat', 'denda_kerusakan', 'metode_pembayaran', 'catatan_penolakan'] as $column) {
                if (Schema::hasColumn('pengembalians', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
