@extends("layouts.app")

@section("content")
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📍 QR Code Pesanan Anda</h4>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-4">Tunjukkan QR Code ini kepada vendor untuk mengambil pesanan Anda</p>
                    
                    <div id="qrcode" style="display: inline-block; padding: 20px; background: white; border-radius: 10px;"></div>
                    
                    <hr style="margin: 30px 0;"/>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Pesanan:</strong></p>
                            <p class="h5 text-primary">{{ $pesanan->id_pesanan }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Pembayaran:</strong></p>
                            <p class="h5 text-success">Rp {{ number_format($pesanan->total, 0, ",", ".") }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button class="btn btn-primary" onclick="printQRCode()">
                            <i class="fas fa-print"></i> Cetak QR Code
                        </button>
                        <button class="btn btn-secondary" onclick="downloadQRCode()">
                            <i class="fas fa-download"></i> Download QR Code
                        </button>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>QR Code ini akan tersimpan di browser Anda untuk diakses nanti</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
// Generate QR Code
const qr = new QRCode(document.getElementById("qrcode"), {
    text: "{{ $pesanan->id_pesanan }}",
    width: 300,
    height: 300,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

// Simpan QR code ke localStorage agar bisa diakses nanti
localStorage.setItem("qrcode_pesanan", JSON.stringify({
    id_pesanan: "{{ $pesanan->id_pesanan }}",
    timestamp: new Date().toISOString(),
    qrUrl: document.getElementById("qrcode").querySelector("img").src
}));

function printQRCode() {
    window.print();
}

function downloadQRCode() {
    const canvas = document.getElementById("qrcode").querySelector("canvas");
    const link = document.createElement("a");
    link.href = canvas.toDataURL("image/png");
    link.download = "QRCode-Pesanan-{{ $pesanan->id_pesanan }}.png";
    link.click();
}
</script>

<style>
    @media print {
        .card-footer, .btn {
            display: none;
        }
        .card {
            border: none;
            box-shadow: none;
        }
    }
</style>
@endsection
