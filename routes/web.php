<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;

// =====================
// ROUTES AUTH (Laravel UI - tanpa register)
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
    Route::get('/', fn () => view('home'))->name('home');
    Route::get('/home', fn () => redirect()->route('home'));

    // Kategori (CRUD)
    Route::resource('kategori', KategoriController::class);

    // Buku (CRUD) 
    Route::resource('buku', BukuController::class);

    // Barang (CRUD + Print Label) 
    // Print label harus didaftarkan SEBELUM resource agar tidak bentrok dengan {barang}
    Route::post('/barang/print-label', [BarangController::class, 'printLabel'])->name('barang.print-label');
    Route::resource('barang', BarangController::class);


    // -- Select Kota (Client-side only) --
    Route::get('/select-kota', fn () => view('select_kota.index'))->name('select-kota.index');

    // -- Barang JS (Client-side only, no DB) --
    Route::get('/barang-js/html-table', fn () => view('barang_js.html_table'))->name('barang-js.html-table');
    Route::get('/barang-js/datatable', fn () => view('barang_js.datatable'))->name('barang-js.datatable');

    //PDF Generator 
    Route::prefix('pdf')->group(function () {
        Route::get('/sertifikat', [PdfController::class, 'sertifikatForm'])->name('pdf.sertifikat.form');
        Route::get('/sertifikat/download', [PdfController::class, 'generateSertifikat'])->name('pdf.sertifikat.generate');
        Route::get('/undangan', [PdfController::class, 'undanganForm'])->name('pdf.undangan.form');
        Route::get('/undangan/download', [PdfController::class, 'generateUndangan'])->name('pdf.undangan.generate');
    });

});
