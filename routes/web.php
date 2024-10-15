<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Auth::routes();

Route::get('/surat', [App\Http\Controllers\SuratController::class, 'index'])->name('surat');

Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\SuratController::class, 'index']);

    Route::resource('surat', SuratController::class);
    Route::get('/surat/create', [SuratController::class, 'create'])->name('surat.create');
    Route::get('/surat/generate-nomor-perprodi/{prodiId}', [SuratController::class, 'generateNomorPerProdi']);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


require __DIR__.'/auth.php';

