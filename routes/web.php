<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes defined here will be assigned to the "web" middleware group.
*/

Auth::routes();

Route::middleware('auth')->group(function () {
    // Rute utama diarahkan ke daftar surat
    Route::get('/', [SuratController::class, 'index']);
    
    // Resource route untuk surat (CRUD)
    Route::resource('surat', SuratController::class);
    
    Route::post('/surat/check-nomor', [SuratController::class, 'checkNomor'])->name('surat.checkNomor');
    Route::post('/surat/generate-nomor-per-prodi', [SuratController::class, 'generateNomorPerProdi'])->name('surat.generateNomorPerProdi');
    Route::post('/surat/filter-prodi', [SuratController::class, 'filterProdi'])->name('surat.filterProdi');
    Route::put('/surat/{id}', [SuratController::class, 'update'])->name('surat.update');
    Route::delete('/surat/{surat}/lampiran/{lampiran}', [SuratController::class, 'deleteLampiran'])->name('surat.deleteLampiran');
    Route::get('/surat/{surat}/edit', [SuratController::class, 'edit'])->name('surat.edit');

    // Rute untuk mengelola profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
