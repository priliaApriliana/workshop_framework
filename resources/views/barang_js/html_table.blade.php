@extends('layouts.app')

@section('title', 'Barang JS - HTML Table')
@section('icon', 'mdi-package-variant')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Barang JS (HTML Table)</li>
@endsection

@push('styles')
<style>
    #barangTableHtml tbody tr {
        cursor: pointer;
    }
    #barangTableHtml tbody tr:hover {
        background-color: #f3e6ff !important;
    }
    .btn-spinner .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }
</style>
@endpush

@section('content')
<div class="row">
    {{-- Form Tambah Barang --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Barang</h4>
                <p class="card-description">Data hanya disimpan di tabel (tidak ke database)</p>
                <form id="formTambahBarang" novalidate>
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="form-group">
                        <label for="harga_barang">Harga Barang</label>
                        <input type="number" class="form-control" id="harga_barang" name="harga_barang" placeholder="Masukkan harga barang" required>
                    </div>
                    <button type="button" class="btn btn-gradient-primary me-2" id="btnTambah" onclick="tambahBarang(this)">Submit</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Barang --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Barang</h4>
                <div class="table-responsive">
                    <table class="table table-striped" id="barangTableHtml">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="barangTbody">
                            {{-- Data akan diisi via JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit/Hapus --}}
<div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangLabel">Detail Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditBarang" novalidate>
                    <div class="form-group">
                        <label for="edit_id">ID Barang</label>
                        <input type="text" class="form-control" id="edit_id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama">Nama Barang</label>
                        <input type="text" class="form-control" id="edit_nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_harga">Harga Barang</label>
                        <input type="number" class="form-control" id="edit_harga" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnHapus" onclick="hapusBarang(this)">Hapus</button>
                <button type="button" class="btn btn-success" id="btnUbah" onclick="ubahBarang(this)">Ubah</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var barangCounter = 0;

function tambahBarang(btn) {
    var form = document.getElementById('formTambahBarang');

    // Cek HTML5 validity
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    var nama = document.getElementById('nama_barang').value.trim();
    var harga = document.getElementById('harga_barang').value.trim();

    if (!nama || !harga) return;

    // Spinner
    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';

    setTimeout(function() {
        barangCounter++;
        var id = 'BRG-' + String(barangCounter).padStart(3, '0');

        var tbody = document.getElementById('barangTbody');
        var row = document.createElement('tr');
        row.setAttribute('data-id', id);
        row.setAttribute('data-nama', nama);
        row.setAttribute('data-harga', harga);
        row.setAttribute('onclick', 'openModal(this)');
        row.innerHTML = '<td>' + id + '</td><td>' + nama + '</td><td>Rp ' + Number(harga).toLocaleString('id-ID') + '</td>';
        tbody.appendChild(row);

        // Reset form
        document.getElementById('nama_barang').value = '';
        document.getElementById('harga_barang').value = '';

        // Kembalikan button
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }, 600);
}

var currentRow = null;

function openModal(tr) {
    currentRow = tr;
    document.getElementById('edit_id').value = tr.getAttribute('data-id');
    document.getElementById('edit_nama').value = tr.getAttribute('data-nama');
    document.getElementById('edit_harga').value = tr.getAttribute('data-harga');

    var modal = new bootstrap.Modal(document.getElementById('modalBarang'));
    modal.show();
}

function hapusBarang(btn) {
    if (!currentRow) return;

    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    document.getElementById('btnUbah').disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

    setTimeout(function() {
        currentRow.remove();
        currentRow = null;

        btn.disabled = false;
        document.getElementById('btnUbah').disabled = false;
        btn.innerHTML = originalHTML;

        var modalEl = document.getElementById('modalBarang');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    }, 600);
}

function ubahBarang(btn) {
    var form = document.getElementById('formEditBarang');

    // Cek HTML5 validity
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    if (!currentRow) return;

    var nama = document.getElementById('edit_nama').value.trim();
    var harga = document.getElementById('edit_harga').value.trim();
    if (!nama || !harga) return;

    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    document.getElementById('btnHapus').disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';

    setTimeout(function() {
        currentRow.setAttribute('data-nama', nama);
        currentRow.setAttribute('data-harga', harga);
        currentRow.cells[1].textContent = nama;
        currentRow.cells[2].textContent = 'Rp ' + Number(harga).toLocaleString('id-ID');

        btn.disabled = false;
        document.getElementById('btnHapus').disabled = false;
        btn.innerHTML = originalHTML;

        var modalEl = document.getElementById('modalBarang');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        currentRow = null;
    }, 600);
}
</script>
@endpush
