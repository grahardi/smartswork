<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->enum('jenis', ['bank', 'ewallet'])->default('bank')->after('user_id');
            // provider dipakai untuk e-wallet, nentuin badge warna: ovo, gopay, dana, shopeepay, linkaja, lainnya
            $table->string('provider')->nullable()->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'provider']);
        });
    }
};
