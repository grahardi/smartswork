<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\WorkplaceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DailyActionController;
use App\Http\Controllers\FinanceCategoryController;
use App\Http\Controllers\FinanceTransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FriendDataController;
use App\Http\Controllers\ProjectCollaboratorController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
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

    // Data Diri (step setelah registrasi)
    Route::get('/data-diri', [ProfileSetupController::class, 'create'])->name('profile.create');
    Route::post('/data-diri', [ProfileSetupController::class, 'store'])->name('profile.store');

    // Tempat Kerja
    Route::get('/tempat-kerja', [WorkplaceController::class, 'index'])->name('workplaces.index');
    Route::get('/tempat-kerja/tambah', [WorkplaceController::class, 'create'])->name('workplaces.create');
    Route::post('/tempat-kerja', [WorkplaceController::class, 'store'])->name('workplaces.store');
    Route::get('/tempat-kerja/{workplace}/edit', [WorkplaceController::class, 'edit'])->name('workplaces.edit');
    Route::put('/tempat-kerja/{workplace}', [WorkplaceController::class, 'update'])->name('workplaces.update');

    // Tempat Tinggal
    Route::get('/tempat-tinggal', [ResidenceController::class, 'index'])->name('residences.index');
    Route::get('/tempat-tinggal/tambah', [ResidenceController::class, 'create'])->name('residences.create');
    Route::post('/tempat-tinggal', [ResidenceController::class, 'store'])->name('residences.store');
    Route::get('/tempat-tinggal/{residence}/edit', [ResidenceController::class, 'edit'])->name('residences.edit');
    Route::put('/tempat-tinggal/{residence}', [ResidenceController::class, 'update'])->name('residences.update');
    Route::delete('/tempat-tinggal/{residence}', [ResidenceController::class, 'destroy'])->name('residences.destroy');
    Route::patch('/tempat-tinggal/{residence}/jadikan-utama', [ResidenceController::class, 'makeDefault'])->name('residences.make-default');

    // Project (bersarang di bawah Tempat Kerja)
    Route::get('/tempat-kerja/{workplace}/project', [ProjectController::class, 'index'])->name('workplaces.projects.index');
    Route::get('/tempat-kerja/{workplace}/project/tambah', [ProjectController::class, 'create'])->name('workplaces.projects.create');
    Route::post('/tempat-kerja/{workplace}/project', [ProjectController::class, 'store'])->name('workplaces.projects.store');

    // Aksi Harian
    Route::get('/aksi-harian', [DailyActionController::class, 'index'])->name('daily-actions.index');
    Route::get('/aksi-harian/catat', [DailyActionController::class, 'create'])->name('daily-actions.create');
    Route::post('/aksi-harian', [DailyActionController::class, 'store'])->name('daily-actions.store');

    // Keuangan - Kategori (CRUD penuh ala WordPress/Joomla)
    Route::get('/keuangan/kategori', [FinanceCategoryController::class, 'index'])->name('finance.categories.index');
    Route::get('/keuangan/kategori/tambah', [FinanceCategoryController::class, 'create'])->name('finance.categories.create');
    Route::post('/keuangan/kategori', [FinanceCategoryController::class, 'store'])->name('finance.categories.store');
    Route::get('/keuangan/kategori/{category}/edit', [FinanceCategoryController::class, 'edit'])->name('finance.categories.edit');
    Route::put('/keuangan/kategori/{category}', [FinanceCategoryController::class, 'update'])->name('finance.categories.update');
    Route::delete('/keuangan/kategori/{category}', [FinanceCategoryController::class, 'destroy'])->name('finance.categories.destroy');

    // Keuangan - Transaksi
    Route::get('/keuangan', [FinanceTransactionController::class, 'index'])->name('finance.transactions.index');
    Route::get('/keuangan/catat', [FinanceTransactionController::class, 'create'])->name('finance.transactions.create');
    Route::post('/keuangan', [FinanceTransactionController::class, 'store'])->name('finance.transactions.store');
    Route::get('/keuangan/{transaction}/edit', [FinanceTransactionController::class, 'edit'])->name('finance.transactions.edit');
    Route::put('/keuangan/{transaction}', [FinanceTransactionController::class, 'update'])->name('finance.transactions.update');
    Route::delete('/keuangan/{transaction}', [FinanceTransactionController::class, 'destroy'])->name('finance.transactions.destroy');

    // Transfer saldo ke teman
    Route::get('/keuangan/transfer', [TransferController::class, 'create'])->name('finance.transfer.create');
    Route::post('/keuangan/transfer', [TransferController::class, 'store'])->name('finance.transfer.store');

    // Teman
    Route::get('/teman', [FriendController::class, 'index'])->name('friends.index');
    Route::get('/teman/cari', [FriendController::class, 'search'])->name('friends.search');
    Route::post('/teman', [FriendController::class, 'store'])->name('friends.store');
    Route::post('/teman/{friendship}/terima', [FriendController::class, 'accept'])->name('friends.accept');
    Route::post('/teman/{friendship}/tolak', [FriendController::class, 'decline'])->name('friends.decline');
    Route::delete('/teman/{friend}', [FriendController::class, 'destroy'])->name('friends.destroy');
    Route::patch('/teman/{friend}/label', [FriendController::class, 'updateLabel'])->name('friends.label');
    Route::patch('/teman/{friend}/akses', [FriendController::class, 'updatePermissions'])->name('friends.permissions');

    // Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifikasi/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Lihat data teman (read-only, harus berteman dulu)
    Route::get('/teman/{friend}/aksi-harian', [FriendDataController::class, 'dailyActions'])->name('friends.aksi-harian');
    Route::get('/teman/{friend}/keuangan', [FriendDataController::class, 'finance'])->name('friends.keuangan');
    Route::get('/teman/{friend}/tempat-kerja', [FriendDataController::class, 'workplaces'])->name('friends.tempat-kerja');

    // Kolaborator Project (cowork)
    Route::get('/project/{project}/kolaborator', [ProjectCollaboratorController::class, 'index'])->name('projects.collaborators.index');
    Route::post('/project/{project}/kolaborator', [ProjectCollaboratorController::class, 'store'])->name('projects.collaborators.store');
    Route::delete('/project/{project}/kolaborator/{user}', [ProjectCollaboratorController::class, 'destroy'])->name('projects.collaborators.destroy');

    // Coretan
    Route::get('/coretan', [NoteController::class, 'index'])->name('notes.index');
    Route::post('/coretan', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/coretan/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/coretan/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::patch('/coretan/{note}/pin', [NoteController::class, 'togglePin'])->name('notes.pin');
    Route::delete('/coretan/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Galeri
    Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('/galeri/tambah', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('/galeri', [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/galeri/{photo}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
    Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
});

require __DIR__.'/auth.php';
