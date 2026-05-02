@extends("layouts.app")

@section("content")
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"> QR Code Pesanan Anda</h4>
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
                    <div id="qr-meta"
                         data-order-id="{{ $pesanan->id_pesanan }}"
                         data-total="Rp {{ number_format($pesanan->total, 0, ',', '.') }}"
                         data-customer="{{ e($pesanan->nama_customer ?? '') }}"
                         data-payment-method="{{ e($payment->payment_method ?? '') }}"
                         data-logo-url="{{ asset('assets/images/logo.svg') }}"
                         style="display:none"></div>
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
function getQRCodeDataURL() {
  console.log("Looking for #qrcode element");
  const qEl = document.getElementById("qrcode");
  console.log("qEl found:", qEl);
  if (!qEl) return null;
  const canvas = qEl.querySelector("canvas");
  if (canvas) return canvas.toDataURL("image/png");
  const img = qEl.querySelector("img");
  if (img) return img.src;
  return null;
}

function saveQRCodeToLocalStorage() {
  const dataUrl = getQRCodeDataURL();
  if (!dataUrl) return;
  try {
    localStorage.setItem("qrcode_pesanan", JSON.stringify({
      id_pesanan: "{{ $pesanan->id_pesanan }}",
      timestamp: new Date().toISOString(),
      qrUrl: dataUrl
    }));
  } catch (e) {
    console.warn('Failed to save QR to localStorage', e);
  }
}

function waitForQRCodeAndSave(retries = 10, interval = 200) {
  const dataUrl = getQRCodeDataURL();
  if (dataUrl) {
    saveQRCodeToLocalStorage();
    return;
  }
  if (retries <= 0) return;
  setTimeout(() => waitForQRCodeAndSave(retries -1, interval), interval);
}

console.log("DOMContentLoaded fired, QRCode available:", typeof QRCode !== "undefined");
document.addEventListener("DOMContentLoaded", function() {
  console.log("Looking for #qrcode element");
  const qEl = document.getElementById("qrcode");
  console.log("qEl found:", qEl);
  if (qEl) {
    console.log("Generating QRCode with text:", "{{ $pesanan->id_pesanan }}");
    new QRCode(qEl, {
      text: "{{ $pesanan->id_pesanan }}",
      width: 300,
      height: 300,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H
    });
    waitForQRCodeAndSave();
  }
});

function downloadQRCode() {
  const dataUrl = getQRCodeDataURL();
  if (!dataUrl) {
    alert('QR code belum tersedia untuk diunduh.');
    return;
  }
  const link = document.createElement('a');
  link.href = dataUrl;
  link.download = 'QRCode-Pesanan-{{ $pesanan->id_pesanan }}.png';
  document.body.appendChild(link);
  link.click();
  link.remove();
}function printQRCode() {
  const dataUrl = getQRCodeDataURL();
  const meta = document.getElementById('qr-meta');
  const orderId = meta ? (meta.dataset.orderId || '') : '';
  const total = meta ? (meta.dataset.total || '') : '';
  const customer = meta ? (meta.dataset.customer || '') : '';
  const paymentMethod = meta ? (meta.dataset.paymentMethod || '') : '';
  const logoUrl = meta ? (meta.dataset.logoUrl || '') : '';
  const date = new Date().toLocaleString();
  const qrMarkup = dataUrl ? '<img src="' + dataUrl + '" alt="QR" />' : '';

  const html = [
    '<!doctype html>',
    '<html>',
    '<head>',
    '  <meta charset="utf-8">',
    '  <title>QR Pesanan ' + orderId + '</title>',
    '  <style>',
    '    body { font-family: Arial, sans-serif; padding: 20px; color: #111; }',
    '    .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; max-width: 600px; margin: 0 auto; }',
    '    .header { display: flex; align-items: center; gap: 12px; }',
    '    .logo { width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; }',
    '    .logo img { width: 70px; height: 70px; object-fit: contain; }',
    '    .title { margin: 0; }',
    '    .subtitle { font-size: 12px; color: #666; }',
    '    .qr { text-align: center; margin: 14px 0; }',
    '    .qr img { width: 240px; height: 240px; display: inline-block; }',
    '    .info { margin-top: 10px; font-size: 14px; }',
    '    .row { display: flex; justify-content: space-between; padding: 6px 0; border-top: 1px dashed #eee; }',
    '    .footer { margin-top: 15px; font-size: 12px; color: #666; text-align: center; }',
    '    @media print { body { margin: 0; } .card { box-shadow: none; border: none; } }',
    '  </style>',
    '</head>',
    '<body>',
    '  <div class="card">',
    '    <div class="header">',
    '      <div class="logo"><img src="' + logoUrl + '" alt="Logo"></div>',
    '      <div>',
    '        <h2 class="title">Koleksi Buku</h2>',
    '        <div class="subtitle">QR Code Pesanan</div>',
    '      </div>',
    '    </div>',
    '    <hr>',
    '    <div class="qr">' + qrMarkup + '</div>',
    '    <div class="info">',
    '      <div class="row"><div><strong>ID Pesanan</strong></div><div>' + orderId + '</div></div>',
    '      <div class="row"><div><strong>Nama</strong></div><div>' + customer + '</div></div>',
    '      <div class="row"><div><strong>Metode Bayar</strong></div><div>' + paymentMethod + '</div></div>',
    '      <div class="row"><div><strong>Total</strong></div><div>' + total + '</div></div>',
    '      <div class="row"><div><strong>Waktu</strong></div><div>' + date + '</div></div>',
    '    </div>',
    '    <div class="footer">Tunjukkan QR ini ke vendor untuk mengambil pesanan. Terima kasih.</div>',
    '  </div>',
    '</body>',
    '</html>'
  ].join('\n');

  const iframe = document.createElement('iframe');
  iframe.style.position = 'fixed';
  iframe.style.right = '0';
  iframe.style.bottom = '0';
  iframe.style.width = '0';
  iframe.style.height = '0';
  iframe.style.border = '0';
  iframe.setAttribute('aria-hidden', 'true');
  iframe.srcdoc = html;
  document.body.appendChild(iframe);

  iframe.onload = () => {
    const frameWindow = iframe.contentWindow;
    if (!frameWindow) {
      document.body.removeChild(iframe);
      alert('Browser tidak mengizinkan pencetakan otomatis. Gunakan Download.');
      return;
    }

    frameWindow.focus();
    frameWindow.print();
    setTimeout(() => {
      if (iframe.parentNode) iframe.parentNode.removeChild(iframe);
    }, 1000);
  };
}</script>
@endsection