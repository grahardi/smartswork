<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum via raw SQL (MySQL) supaya bisa tambah 'custom' tanpa perlu doctrine/dbal.
        DB::statement("ALTER TABLE businesses MODIFY skema_pajak ENUM('umkm_final','badan_normal','custom') NOT NULL DEFAULT 'umkm_final'");

        Schema::table('businesses', function (Blueprint $table) {
            $table->decimal('pajak_custom_persen', 5, 2)->nullable()->after('skema_pajak');
            $table->enum('pajak_custom_basis', ['omzet', 'laba'])->nullable()->after('pajak_custom_persen');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['pajak_custom_persen', 'pajak_custom_basis']);
        });

        DB::statement("ALTER TABLE businesses MODIFY skema_pajak ENUM('umkm_final','badan_normal') NOT NULL DEFAULT 'umkm_final'");
    }
};
