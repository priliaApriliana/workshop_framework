<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\VendorOrderController;

// ==========================================
// LANDING PAGE (PUBLIC - Halaman Utama)
// ==========================================
Route::get('/', fn () => view('landing'))->name('landing');

Auth::routes(['register' => false]);

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/otp', [GoogleController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [GoogleController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend', [GoogleController::class, 'resendOtp'])->name('otp.resend');

// ==========================================
// PAYMENT GATEWAY - CUSTOMER ORDER (PUBLIC)
// ==========================================
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/order', [CustomerOrderController::class, 'index'])->name('order.index');
    Route::get('/order/vendor/{id_vendor}', [CustomerOrderController::class, 'show'])->name('order.show');
    Route::post('/order', [CustomerOrderController::class, 'store'])->name('order.store');
    Route::get('/order/{id_pesanan}/payment', [CustomerOrderController::class, 'payment'])->name('order.payment');
    Route::get('/order/{id_pesanan}/status', [CustomerOrderController::class, 'status'])->name('order.status');
    Route::post('/order/{id_pesanan}/update-status', [CustomerOrderController::class, 'updateStatus'])->name('order.update-status');
    Route::get('/order/{id_pesanan}/check-payment', [CustomerOrderController::class, 'checkPaymentStatus'])->name('order.check-payment');
});

// Midtrans Webhook Notification (server-to-server, tanpa CSRF)
Route::post('/midtrans/callback', [CustomerOrderController::class, 'midtransCallback'])->name('midtrans.callback');

// =====================
// ROUTES YANG PERLU LOGIN
// =====================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isVendor()) {
            return redirect()->route('vendor.dashboard');
        }
        return view('home');
    })->name('home');
    Route::get('/home', fn () => redirect()->route('home'));

    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

    Route::post('/barang/print-label', [BarangController::class, 'printLabel'])->name('barang.print-label');
    Route::resource('barang', BarangController::class);

    Route::get('/select-kota', fn () => view('select_kota.index'))->name('select-kota.index');

    Route::get('/barang-js/html-table', fn () => view('barang_js.html_table'))->name('barang-js.html-table');
    Route::get('/barang-js/datatable', fn () => view('barang_js.datatable'))->name('barang-js.datatable');

    Route::prefix('pdf')->group(function () {
        Route::get('/sertifikat', [PdfController::class, 'sertifikatForm'])->name('pdf.sertifikat.form');
        Route::get('/sertifikat/download', [PdfController::class, 'generateSertifikat'])->name('pdf.sertifikat.generate');
        Route::get('/undangan', [PdfController::class, 'undanganForm'])->name('pdf.undangan.form');
        Route::get('/undangan/download', [PdfController::class, 'generateUndangan'])->name('pdf.undangan.generate');
    });

    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('/wilayah/provinsi', [WilayahController::class, 'getProvinsi'])->name('wilayah.provinsi');
    Route::get('/wilayah/kota/{provinsi_id}', [WilayahController::class, 'getKota'])->name('wilayah.kota');
    Route::get('/wilayah/kecamatan/{kota_id}', [WilayahController::class, 'getKecamatan'])->name('wilayah.kecamatan');
    Route::get('/wilayah/kelurahan/{kecamatan_id}', [WilayahController::class, 'getKelurahan'])->name('wilayah.kelurahan');

    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/barang/{kode}', [PosController::class, 'cariBarang'])->name('pos.cari-barang');
    Route::post('/pos/simpan', [PosController::class, 'simpan'])->name('pos.simpan');

    // ==========================================
    // PAYMENT GATEWAY - VENDOR MENU MANAGEMENT
    // ==========================================
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorOrderController::class, 'dashboard'])->name('dashboard');
        Route::get('/semua-pesanan', [VendorOrderController::class, 'semuaPesanan'])->name('semua-pesanan');
        Route::get('/lunas-pesanan', [VendorOrderController::class, 'lunasPesanan'])->name('lunas-pesanan');
        Route::get('/detail-pesanan/{id_pesanan}', [VendorOrderController::class, 'detailPesanan'])->name('detail-pesanan');

        Route::prefix('menu')->name('menu.')->group(function () {
            Route::get('/', [VendorOrderController::class, 'menuIndex'])->name('index');
            Route::get('/create', [VendorOrderController::class, 'menuCreate'])->name('create');
            Route::post('/', [VendorOrderController::class, 'menuStore'])->name('store');
            Route::get('/{id_menu}/edit', [VendorOrderController::class, 'menuEdit'])->name('edit');
            Route::put('/{id_menu}', [VendorOrderController::class, 'menuUpdate'])->name('update');
            Route::delete('/{id_menu}', [VendorOrderController::class, 'menuDestroy'])->name('destroy');
        });
    });
});
