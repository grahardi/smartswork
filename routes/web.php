<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\WorkplaceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DemoLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/demo', DemoLoginController::class)->name('demo.login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'restrict.demo'])->group(function () {
    // Profil akun bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Data Diri (step setelah registrasi)
    Route::get('/data-diri', [ProfileSetupController::class, 'create'])->name('profile.create');
    Route::post('/data-diri', [ProfileSetupController::class, 'store'])->name('profile.store');

    // Tempat Kerja
    Route::get('/tempat-kerja', [WorkplaceController::class, 'index'])->name('workplaces.index');
    Route::get('/tempat-kerja/tambah', [WorkplaceController::class, 'create'])->name('workplaces.create');
    Route::post('/tempat-kerja', [WorkplaceController::class, 'store'])->name('workplaces.store');

    // Project (bersarang di bawah Tempat Kerja)
    Route::get('/tempat-kerja/{workplace}/project', [ProjectController::class, 'index'])->name('workplaces.projects.index');
    Route::get('/tempat-kerja/{workplace}/project/tambah', [ProjectController::class, 'create'])->name('workplaces.projects.create');
    Route::post('/tempat-kerja/{workplace}/project', [ProjectController::class, 'store'])->name('workplaces.projects.store');
});

require __DIR__.'/auth.php';
