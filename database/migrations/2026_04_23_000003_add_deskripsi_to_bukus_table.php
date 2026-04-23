<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bukus') && !Schema::hasColumn('bukus', 'deskripsi')) {
            Schema::table('bukus', function (Blueprint $table) {
                $table->text('deskripsi')->nullable()->after('image');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bukus') && Schema::hasColumn('bukus', 'deskripsi')) {
            Schema::table('bukus', function (Blueprint $table) {
                $table->dropColumn('deskripsi');
            });
        }
    }
};
