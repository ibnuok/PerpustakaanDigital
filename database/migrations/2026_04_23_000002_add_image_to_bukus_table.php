<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bukus') && !Schema::hasColumn('bukus', 'image')) {
            Schema::table('bukus', function (Blueprint $table) {
                $table->string('image')->nullable()->after('kategori_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bukus') && Schema::hasColumn('bukus', 'image')) {
            Schema::table('bukus', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
