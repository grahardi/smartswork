<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->enum('type', ['pemasukan', 'pengeluaran']);
            $table->string('warna', 7)->default('#3E5C4E');
            $table->timestamps();

            $table->unique(['user_id', 'nama', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_categories');
    }
};
