@extends('layouts.app')

@section('title', 'Tambah Customer (Blob)')
@section('icon', 'mdi-account-plus')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
<li class="breadcrumb-item active">Tambah (Blob)</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Customer (Foto via Kamera → Blob)</h4>
                <p class="card-description">Data foto akan dikonversi menjadi biner dan disimpan ke database.</p>
                <form action="{{ route('customer.storeBlob') }}" method="POST" class="forms-sample">
                    @csrf

                    <input type="hidden" name="foto_base64" id="foto_base64">

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Nama Customer">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Alamat">
                    </div>
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" placeholder="Provinsi">
                    </div>
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" name="kota" class="form-control" placeholder="Kota">
                    </div>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan">
                    </div>
                    <div class="form-group">
                        <label>Kodepos - Kelurahan</label>
                        <input type="text" name="kodepos" class="form-control" placeholder="Kodepos - Kelurahan">
                    </div>

                    {{-- Foto preview + tombol (sesuai contoh modul) --}}
                    <div class="form-group">
                        <label>Foto</label>
                        <div class="d-flex align-items-center gap-3">
                            <div id="foto-box"
                                 style="width:130px; height:110px; border:1px solid #ebedf2;
                                        display:flex; align-items:center; justify-content:center;
                                        border-radius:4px; background:#f8f9fa; overflow:hidden; flex-shrink:0;">
                                <span class="text-muted" id="foto-placeholder">Foto</span>
                                <img id="foto-img" src="" alt="preview"
                                     style="display:none; width:100%; height:100%; object-fit:cover;">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary"
                                        data-bs-toggle="modal" data-bs-target="#modalKamera">
                                    Ambil Foto
                                </button>
                                <button type="submit" class="btn btn-success">
                                    Simpan Data
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Kamera --}}
<div class="modal fade" id="modalKamera" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal Ambil Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <p class="text-center mb-1">Video</p>
                        <video id="video" autoplay playsinline
                               style="width:100%; border:1px solid #ebedf2; border-radius:4px;"></video>
                    </div>
                    <div class="col-6">
                        <p class="text-center mb-1">Snapshot</p>
                        <canvas id="canvas"
                                style="width:100%; border:1px solid #ebedf2; border-radius:4px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary"
                            onclick="pilihKamera()">Pilihan Kamera</button>
                    <button type="button" class="btn btn-primary"
                            onclick="ambilFoto()">Ambil Foto</button>
                </div>
                <button type="button" class="btn btn-success"
                        onclick="simpanFoto()">Simpan Foto</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let stream = null;
let devices = [];
let deviceIndex = 0;

// Buka kamera saat modal ditampilkan
document.getElementById('modalKamera').addEventListener('show.bs.modal', function () {
    startKamera();
});

// Stop kamera saat modal ditutup
document.getElementById('modalKamera').addEventListener('hide.bs.modal', function () {
    if (stream) {
        stream.getTracks().forEach(t => t.stop());
        stream = null;
    }
});

// Fungsi untuk memulai kamera
function startKamera(deviceId = null) {
    if (stream) stream.getTracks().forEach(t => t.stop());

    const constraints = deviceId
        ? { video: { deviceId: { exact: deviceId } } }
        : { video: true };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(s => {
            stream = s;
            document.getElementById('video').srcObject = s;
            return navigator.mediaDevices.enumerateDevices();
        })
        .then(d => { devices = d.filter(x => x.kind === 'videoinput'); })
        .catch(err => alert('Kamera tidak dapat diakses: ' + err.message));
}

// Fungsi untuk memilih kamera (jika ada lebih dari 1)
function pilihKamera() {
    if (devices.length <= 1) { alert('Hanya ada 1 kamera tersedia.'); return; }
    deviceIndex = (deviceIndex + 1) % devices.length;
    startKamera(devices[deviceIndex].deviceId);
}

// Fungsi untuk mengambil foto dari video
function ambilFoto() {
    const video  = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
}

// Fungsi untuk menyimpan foto ke input hidden dan preview
function simpanFoto() {
    const canvas = document.getElementById('canvas');
    if (canvas.width === 0) { alert('Ambil foto terlebih dahulu!'); return; }

    const dataUrl = canvas.toDataURL('image/png');
    document.getElementById('foto_base64').value = dataUrl;
    document.getElementById('foto-img').src = dataUrl;
    document.getElementById('foto-img').style.display = 'block';
    document.getElementById('foto-placeholder').style.display = 'none';

    bootstrap.Modal.getInstance(document.getElementById('modalKamera')).hide();
}
</script>
@endpush
@endsection