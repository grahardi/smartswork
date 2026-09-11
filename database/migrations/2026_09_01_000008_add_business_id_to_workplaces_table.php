<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workplaces', function (Blueprint $table) {
            // Kalau tempat kerja ini otomatis dibuat dari SMARTS Business,
            // kolom ini jadi jembatan buat integrasi keuangan/jurnal ke depannya.
            $table->foreignId('business_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('workplaces', function (Blueprint $table) {
            $table->dropConstrainedForeignId('business_id');
        });
    }
};
