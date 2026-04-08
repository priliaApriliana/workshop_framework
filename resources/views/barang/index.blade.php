@extends('layouts.app')

@section('title', 'Data Barang')
@section('icon', 'mdi-package-variant')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Barang</li>
@endsection

@push('styles')
<style>
    #barangTable_wrapper .dataTables_length,
    #barangTable_wrapper .dataTables_filter {
        margin-bottom: 12px;
    }
    #barangTable_wrapper .dataTables_length label,
    #barangTable_wrapper .dataTables_filter label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
    }
    #barangTable_wrapper .dataTables_length select,
    #barangTable_wrapper .dataTables_filter input {
        display: inline-block;
        width: auto;
        padding: 5px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 13px;
    }
    #barangTable_wrapper .dataTables_info {
        margin-top: 12px;
        font-size: 13px;
        color: #6c757d;
    }
    #barangTable_wrapper .dataTables_paginate {
        margin-top: 12px;
    }
    #barangTable_wrapper .dataTables_paginate .paginate_button {
        padding: 4px 10px !important;
        border-radius: 4px !important;
        cursor: pointer;
        font-size: 13px;
    }
    #barangTable_wrapper .dataTables_paginate .paginate_button.current,
    #barangTable_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #b66dff !important;
        color: #fff !important;
        border: 1px solid #b66dff !important;
    }
    #barangTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f3e6ff !important;
        color: #b66dff !important;
        border: 1px solid #b66dff !important;
    }
    #barangTable thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
        font-size: 13px;
    }
    #barangTable td:first-child,
    #barangTable th:first-child {
        text-align: center;
        width: 40px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Daftar Barang</h4>
                    <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary btn-sm">
                        <i class="mdi mdi-plus"></i> Tambah Barang
                    </a>
                </div>

                {{-- Form Cetak Label --}}
                <div class="row mb-4 bg-light p-3 rounded border">
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Posisi Mulai X (1-5)</label>
                        <input type="number" id="start_x" class="form-control" value="1" min="1" max="5">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Posisi Mulai Y (1-8)</label>
                        <input type="number" id="start_y" class="form-control" value="1" min="1" max="8">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="btnCetakPDF" class="btn btn-gradient-success w-100 fw-bold">
                            <i class="mdi mdi-printer"></i> CETAK LABEL PDF
                        </button>
                    </div>
                    <div class="col-md-5 d-flex align-items-end">
                        <small class="text-muted fst-italic">*Centang barang di tabel, lalu klik tombol cetak.</small>
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="table-responsive">
                    <table id="barangTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="10"><input type="checkbox" id="selectAll"></th>
                                <th>ID Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Timestamp</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{ $item->id_barang }}" class="item-checkbox">
                                </td>
                                <td>{{ $item->id_barang }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->timestamp)->format('d-m-Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('barang.edit', $item->id_barang) }}" class="btn btn-warning btn-sm">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteWithSpinner(this)">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    var table = $('#barangTable').DataTable({
        "language": {
            "search"      : "Cari:",
            "lengthMenu"  : "Tampilkan _MENU_ data",
            "info"        : "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            "infoEmpty"   : "Tidak ada data",
            "zeroRecords" : "Data tidak ditemukan",
            "paginate": {
                "first"    : "Pertama",
                "last"     : "Terakhir",
                "next"     : "›",
                "previous" : "‹"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [0, 5] }
        ],
        "order": [[1, 'asc']]
    });

    // Select All Checkbox
    $('#selectAll').on('click', function() {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input.item-checkbox', rows).prop('checked', this.checked);
    });

    // Tombol Cetak PDF - Form Submission (langsung buka PDF di tab baru)
    $(document).on('click', '#btnCetakPDF', function(e) {
        e.preventDefault();

        var checkedIds = [];
        table.$('input.item-checkbox:checked').each(function() {
            checkedIds.push($(this).val());
        });

        if (checkedIds.length === 0) {
            alert('Pilih minimal 1 barang untuk dicetak!');
            return;
        }

        var token = $('meta[name="csrf-token"]').attr('content');
        if (!token) {
            alert('Error: CSRF Token tidak ditemukan!');
            return;
        }

        var startX = $('#start_x').val();
        var startY = $('#start_y').val();

        if (startX < 1 || startX > 5 || startY < 1 || startY > 8) {
            alert('Koordinat X harus 1-5 dan Y harus 1-8!');
            return;
        }

        // form hidden dan submit ke tab baru
        var $form = $('<form>', {
            method: 'POST',
            action: '{{ route("barang.print-label") }}',
            target: '_blank'
        });

        $form.append($('<input>', {type:'hidden', name:'_token', value:token}));
        $form.append($('<input>', {type:'hidden', name:'start_x', value:startX}));
        $form.append($('<input>', {type:'hidden', name:'start_y', value:startY}));

        checkedIds.forEach(function(id) {
            $form.append($('<input>', {type:'hidden', name:'ids[]', value:id}));
        });

        $('body').append($form);
        $form.submit();
        $form.remove();
    });

});
</script>
@endpush