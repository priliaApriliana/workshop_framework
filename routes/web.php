<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;

// =====================
// ROUTES AUTH (Laravel UI - Otomatis)
// =====================
Auth::routes(['register' => false]); // Disable register jika tidak diperlukan

// =====================
// ROUTES YANG PERLU LOGIN
// =====================
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('home');
    })->name('home');

    // Redirect /home ke / (karena Laravel UI default ke /home)
    Route::get('/home', function () {
        return redirect()->route('home');
    });

    // =============================================
    // Routes untuk KATEGORI
    // =============================================
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // =============================================
    // Routes untuk BUKU
    // =============================================
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
