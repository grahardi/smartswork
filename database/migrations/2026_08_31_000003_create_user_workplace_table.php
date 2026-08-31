<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_workplace', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workplace_id')->constrained()->cascadeOnDelete();
            $table->string('jabatan')->nullable();
            $table->date('tanggal_gabung')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'workplace_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_workplace');
    }
};
