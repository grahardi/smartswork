<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('friend_permissions', function (Blueprint $table) {
            $table->id();
            // user_id = pemilik data, friend_user_id = teman yang diberi akses.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('friend_user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('can_view_aksi_harian')->default(false);
            $table->boolean('can_view_keuangan')->default(false);
            $table->boolean('can_view_tempat_kerja')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'friend_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friend_permissions');
    }
};
