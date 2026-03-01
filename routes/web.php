<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;



// =====================
// ROUTES AUTH (Laravel UI - Otomatis)
// =====================
Auth::routes(['register' => false]);

// =====================
// ROUTES GOOGLE AUTH & OTP
// =====================
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/otp', [GoogleController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [GoogleController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend', [GoogleController::class, 'resendOtp'])->name('otp.resend');

// =====================
// ROUTES YANG PERLU LOGIN
// =====================
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('home');
    })->name('home');

    // Redirect /home ke /
    Route::get('/home', function () {
        return redirect()->route('home');
    });

    // Routes untuk KATEGORI
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Routes untuk BUKU
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // =============================================
    // Routes untuk PDF Generator
    // =============================================
    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikatForm'])->name('pdf.sertifikat.form');
    Route::get('/pdf/sertifikat/download', [PdfController::class, 'generateSertifikat'])->name('pdf.sertifikat.generate');
    Route::get('/pdf/undangan', [PdfController::class, 'undanganForm'])->name('pdf.undangan.form');
    Route::get('/pdf/undangan/download', [PdfController::class, 'generateUndangan'])->name('pdf.undangan.generate');

    // Barang
    Route::post('/print-label', [BarangController::class, 'printLabel'])->name('barang.print-label');
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');

});


