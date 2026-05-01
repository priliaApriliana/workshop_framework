@extends('layouts.app')

@section('title', 'Barcode Scanner')
@section('content')
<div class="container mt-4" id="barcode-scanner-page" data-search-url="{{ route('api.barcode.search') }}">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Barcode Scanner - Praktikum 1</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <div class="fw-bold mb-1">Alur penggunaan</div>
                        <ol class="mb-0 ps-3">
                            <li>Buka halaman scanner.</li>
                            <li>Izinkan akses kamera di browser.</li>
                            <li>Klik tombol <strong>Aktifkan Suara</strong> sekali untuk membuka audio.</li>
                            <li>Arahkan barcode ke kamera.</li>
                            <li>Jika terbaca, scanner berhenti sementara, berbunyi beep, lalu data barang tampil.</li>
                        </ol>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <button type="button" class="btn btn-gradient-primary" id="btn-enable-sound">
                            Aktifkan Suara
                        </button>
                        <span class="text-muted small" id="audio-status">Suara belum aktif.</span>
                    </div>

                    <div id="scanner-status" class="alert alert-secondary py-2 mb-3" role="status">
                        Scanner siap. Silakan arahkan barcode ke kamera.
                    </div>

                    <div id="qr-reader" style="width: 100%; min-height: 400px; border: 2px solid #ddd; border-radius: 5px;"></div>
                    <p class="text-muted mt-2 text-center mb-0"><small>Arahkan barcode ke depan kamera</small></p>

                    <div id="beep-indicator" class="text-center mt-2" style="display:none;">
                        <span id="beep-badge" class="badge bg-success" style="font-size:1.1rem;padding:0.6rem 1rem;">Beep!</span>
                    </div>
                </div>
            </div>

            <div id="result-container" style="display: none;" class="mt-4">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Hasil Scan Barcode</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <p class="mb-1"><strong>ID Barang:</strong></p>
                                <p id="id_barang" class="h4 text-primary mb-0"></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nama Barang:</strong></p>
                                <p id="nama_barang" class="h5 mb-0"></p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <p class="mb-1"><strong>Harga Barang:</strong></p>
                                <p id="harga_barang" class="h4 text-success mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="text-muted small">Scanner dijeda sementara. Klik tombol di bawah untuk scan barang lain.</span>
                        <button class="btn btn-primary" id="btn-restart-scanner" type="button">Scan Barang Lain</button>
                    </div>
                </div>
            </div>

            <div id="error-container" style="display: none;" class="mt-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong>
                    <span id="error_message"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}"></script>
<script src="{{ asset('js/barcode_scanner.js') }}"></script>
@endsection