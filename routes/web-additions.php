<?php

// Tambahkan di routes/web.php

use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\WorkplaceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DemoLoginController;

Route::get('/demo', DemoLoginController::class)->name('demo.login');

// Route registrasi bawaan Breeze tetap dipakai (controller sudah di-override
// lewat namespace App\Http\Controllers\Auth\RegisteredUserController).

Route::middleware(['auth', 'restrict.demo'])->group(function () {
    Route::get('/data-diri', [ProfileSetupController::class, 'create'])->name('profile.create');
    Route::post('/data-diri', [ProfileSetupController::class, 'store'])->name('profile.store');

    Route::get('/tempat-kerja', [WorkplaceController::class, 'index'])->name('workplaces.index');
    Route::get('/tempat-kerja/tambah', [WorkplaceController::class, 'create'])->name('workplaces.create');
    Route::post('/tempat-kerja', [WorkplaceController::class, 'store'])->name('workplaces.store');

    Route::get('/tempat-kerja/{workplace}/project', [ProjectController::class, 'index'])->name('workplaces.projects.index');
    Route::get('/tempat-kerja/{workplace}/project/tambah', [ProjectController::class, 'create'])->name('workplaces.projects.create');
    Route::post('/tempat-kerja/{workplace}/project', [ProjectController::class, 'store'])->name('workplaces.projects.store');
});
