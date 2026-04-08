@extends('layouts.app')

@section('title', 'Barang JS - DataTable')
@section('icon', 'mdi-package-variant')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Barang JS (DataTable)</li>
@endsection

@push('styles')
<style>
    #barangTableDt tbody tr {
        cursor: pointer;
    }
    #barangTableDt tbody tr:hover {
        background-color: #f3e6ff !important;
    }
    .btn-spinner .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }
    #barangTableDt_wrapper .dataTables_length,
    #barangTableDt_wrapper .dataTables_filter {
        margin-bottom: 12px;
    }
    #barangTableDt_wrapper .dataTables_length label,
    #barangTableDt_wrapper .dataTables_filter label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
    }
    #barangTableDt_wrapper .dataTables_length select,
    #barangTableDt_wrapper .dataTables_filter input {
        display: inline-block;
        width: auto;
        padding: 5px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 13px;
    }
    #barangTableDt_wrapper .dataTables_info {
        margin-top: 12px;
        font-size: 13px;
        color: #6c757d;
    }
    #barangTableDt_wrapper .dataTables_paginate {
        margin-top: 12px;
    }
    #barangTableDt_wrapper .dataTables_paginate .paginate_button {
        padding: 4px 10px !important;
        border-radius: 4px !important;
        cursor: pointer;
        font-size: 13px;
    }
    #barangTableDt_wrapper .dataTables_paginate .paginate_button.current,
    #barangTableDt_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #b66dff !important;
        color: #fff !important;
        border: 1px solid #b66dff !important;
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
                <form id="formTambahBarangDt" novalidate>
                    <div class="form-group">
                        <label for="nama_barang_dt">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang_dt" name="nama_barang" placeholder="Masukkan nama barang" required>
                    </div>
                    <div class="form-group">
                        <label for="harga_barang_dt">Harga Barang</label>
                        <input type="number" class="form-control" id="harga_barang_dt" name="harga_barang" placeholder="Masukkan harga barang" required>
                    </div>
                    <button type="button" class="btn btn-gradient-primary me-2" id="btnTambahDt" onclick="tambahBarangDt(this)">Submit</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Barang (DataTable) --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Barang (DataTable)</h4>
                <div class="table-responsive">
                    <table class="table table-striped" id="barangTableDt">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi via JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit/Hapus --}}
<div class="modal fade" id="modalBarangDt" tabindex="-1" aria-labelledby="modalBarangDtLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangDtLabel">Detail Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditBarangDt" novalidate>
                    <div class="form-group">
                        <label for="edit_id_dt">ID Barang</label>
                        <input type="text" class="form-control" id="edit_id_dt" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_dt">Nama Barang</label>
                        <input type="text" class="form-control" id="edit_nama_dt" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_harga_dt">Harga Barang</label>
                        <input type="number" class="form-control" id="edit_harga_dt" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnHapusDt" onclick="hapusBarangDt(this)">Hapus</button>
                <button type="button" class="btn btn-success" id="btnUbahDt" onclick="ubahBarangDt(this)">Ubah</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var barangCounterDt = 0;
var dataTableInstance;
var currentRowDt = null;

$(document).ready(function() {
    dataTableInstance = $('#barangTableDt').DataTable({
        "language": {
            "search"      : "Cari:",
            "lengthMenu"  : "Tampilkan _MENU_ data",
            "info"        : "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            "infoEmpty"   : "Tidak ada data",
            "zeroRecords" : "Data tidak ditemukan",
            "paginate": {
                "first"    : "Pertama",
                "last"     : "Terakhir",
                "next"     : String.fromCharCode(8250),
                "previous" : String.fromCharCode(8249)
            }
        },
        "order": [[0, 'asc']]
    });

    // Row click handler
    $('#barangTableDt tbody').on('click', 'tr', function() {
        var data = dataTableInstance.row(this).data();
        if (!data) return;

        currentRowDt = dataTableInstance.row(this);

        document.getElementById('edit_id_dt').value = data[0];
        // Ambil nama dari data[1]
        document.getElementById('edit_nama_dt').value = data[1];
        // Ambil harga: hapus "Rp " dan titik pemisah ribuan
        var hargaStr = data[2].replace(/[^0-9]/g, '');
        document.getElementById('edit_harga_dt').value = hargaStr;

        var modal = new bootstrap.Modal(document.getElementById('modalBarangDt'));
        modal.show();
    });
});

function tambahBarangDt(btn) {
    var form = document.getElementById('formTambahBarangDt');

    // Cek HTML5 validity
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    var nama = document.getElementById('nama_barang_dt').value.trim();
    var harga = document.getElementById('harga_barang_dt').value.trim();

    if (!nama || !harga) return;

    // Spinner
    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';

    setTimeout(function() {
        barangCounterDt++;
        var id = 'BRG-' + String(barangCounterDt).padStart(3, '0');

        dataTableInstance.row.add([
            id,
            nama,
            'Rp ' + Number(harga).toLocaleString('id-ID')
        ]).draw(false);

        // Reset form
        document.getElementById('nama_barang_dt').value = '';
        document.getElementById('harga_barang_dt').value = '';

        // Kembalikan button
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }, 600);
}

function hapusBarangDt(btn) {
    if (!currentRowDt) return;

    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    document.getElementById('btnUbahDt').disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

    setTimeout(function() {
        currentRowDt.remove().draw(false);
        currentRowDt = null;

        btn.disabled = false;
        document.getElementById('btnUbahDt').disabled = false;
        btn.innerHTML = originalHTML;

        var modalEl = document.getElementById('modalBarangDt');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    }, 600);
}

function ubahBarangDt(btn) {
    var form = document.getElementById('formEditBarangDt');

    // Cek HTML5 validity
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    if (!currentRowDt) return;

    var nama = document.getElementById('edit_nama_dt').value.trim();
    var harga = document.getElementById('edit_harga_dt').value.trim();
    if (!nama || !harga) return;

    var id = document.getElementById('edit_id_dt').value;

    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    document.getElementById('btnHapusDt').disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';

    setTimeout(function() {
        currentRowDt.data([
            id,
            nama,
            'Rp ' + Number(harga).toLocaleString('id-ID')
        ]).draw(false);

        btn.disabled = false;
        document.getElementById('btnHapusDt').disabled = false;
        btn.innerHTML = originalHTML;

        var modalEl = document.getElementById('modalBarangDt');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        currentRowDt = null;
    }, 600);
}
</script>
@endpush
