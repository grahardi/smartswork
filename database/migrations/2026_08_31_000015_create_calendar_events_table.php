<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('warna', 7)->default('#2563EB');
            $table->timestamps();

            $table->index(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
