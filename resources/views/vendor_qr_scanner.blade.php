@extends("layouts.app")

@section("content")
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">📱 Vendor QR Code Scanner - Praktikum 2</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Scan QR Code dari pelanggan untuk melihat pesanan yang dipesan</p>
                    <div id="qr-reader" style="width: 100%; height: 400px; border: 2px solid #ddd; border-radius: 5px;"></div>
                    <p class="text-muted mt-2 text-center"><small>Arahkan QR Code ke depan kamera</small></p>
                </div>
            </div>

            <div id="result-container" style="display: none;" class="mt-4">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">✓ Detail Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>ID Pesanan:</strong></p>
                                <p id="id_pesanan" class="h5 text-primary"></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nama Pelanggan:</strong></p>
                                <p id="nama_customer" class="h5"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Status Bayar:</strong></p>
                                <p id="status_bayar" class="h5"><span id="badge-status" class="badge"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total:</strong></p>
                                <p id="total" class="h5 text-success"></p>
                            </div>
                        </div>
                        <hr/>
                        <h6 class="mt-4 mb-3"><i class="fas fa-list"></i> Daftar Menu</h6>
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="menu-list">
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" onclick="restartScanner()">
                            🔄 Scan QR Lain
                        </button>
                    </div>
                </div>
            </div>

            <div id="error-container" style="display: none;" class="mt-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>⚠️ Error:</strong>
                    <span id="error_message"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset("node_modules/html5-qrcode/minified/html5-qrcode.min.js") }}"></script>

<script>
function playBeep(duration = 200, frequency = 800) {
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioCtx.createOscillator();
    const gainNode = audioCtx.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(audioCtx.destination);

    oscillator.frequency.value = frequency;
    oscillator.type = "sine";

    gainNode.gain.setValueAtTime(0.3, audioCtx.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration / 1000);

    oscillator.start(audioCtx.currentTime);
    oscillator.stop(audioCtx.currentTime + duration / 1000);
}

const html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", 
    { 
        fps: 20,
        qrbox: { width: 250, height: 250 },
        rememberLastUsedCamera: true,
        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
    }
);

let isProcessing = false;

function onScanSuccess(decodedText, decodedResult) {
    if (isProcessing) return;
    
    isProcessing = true;
    playBeep(200, 800);
    html5QrcodeScanner.pause();
    scanQRCode(decodedText);
}

function onScanError(errorMessage) {
    console.log(errorMessage);
}

html5QrcodeScanner.render(onScanSuccess, onScanError);

function scanQRCode(idPesanan) {
    const csrfToken = document.querySelector("meta[name=\"csrf-token\"]")?.getAttribute("content");

    fetch("{{ route("api.vendor.scan-qr") }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({ id_pesanan: idPesanan })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const d = data.data;
            document.getElementById("id_pesanan").textContent = d.id_pesanan;
            document.getElementById("nama_customer").textContent = d.nama_customer;
            document.getElementById("total").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(d.total);
            
            const badge = document.getElementById("badge-status");
            badge.textContent = d.status_bayar.toUpperCase();
            badge.className = d.status_bayar === "paid" ? "badge bg-success" : "badge bg-warning";
            
            const menuHtml = d.menus.map(m => `
                <tr>
                    <td>${m.nama_menu}</td>
                    <td>${m.jumlah}</td>
                    <td>Rp ${new Intl.NumberFormat("id-ID").format(m.harga)}</td>
                    <td>Rp ${new Intl.NumberFormat("id-ID").format(m.subtotal)}</td>
                </tr>
            `).join("");
            document.getElementById("menu-list").innerHTML = menuHtml;
            
            document.getElementById("result-container").style.display = "block";
            document.getElementById("error-container").style.display = "none";
        } else {
            showError(data.message);
        }
        isProcessing = false;
    })
    .catch(error => {
        showError("Error: " + error.message);
        isProcessing = false;
    });
}

function restartScanner() {
    document.getElementById("result-container").style.display = "none";
    document.getElementById("error-container").style.display = "none";
    isProcessing = false;
    html5QrcodeScanner.resume();
}

function showError(message) {
    document.getElementById("error_message").textContent = message;
    document.getElementById("error-container").style.display = "block";
    document.getElementById("result-container").style.display = "none";
    isProcessing = false;
}
</script>

<style>
    #qr-reader {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    #qr-reader__scan_region {
        border-radius: 5px;
    }
</style>
@endsection
