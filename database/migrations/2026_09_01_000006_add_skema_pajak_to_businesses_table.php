<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            // umkm_final: PPh Final 0.5% x omzet (PP 55/2022)
            // badan_normal: PPh Badan 22% x laba kena pajak
            $table->enum('skema_pajak', ['umkm_final', 'badan_normal'])->default('umkm_final')->after('mata_uang');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('skema_pajak');
        });
    }
};
