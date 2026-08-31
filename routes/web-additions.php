<?php

// Tambahkan di routes/web.php

use App\Http\Controllers\ProfileSetupController;

// Route registrasi bawaan Breeze tetap dipakai (controller sudah di-override
// lewat namespace App\Http\Controllers\Auth\RegisteredUserController).

Route::middleware('auth')->group(function () {
    Route::get('/data-diri', [ProfileSetupController::class, 'create'])->name('profile.create');
    Route::post('/data-diri', [ProfileSetupController::class, 'store'])->name('profile.store');
});
