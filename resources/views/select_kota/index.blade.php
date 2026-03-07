@extends('layouts.app')

@section('title', 'Select Kota')
@section('icon', 'mdi-city')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Select Kota</li>
@endsection

@push('styles')
{{-- Select2 CSS --}}
<link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
<style>
    .kota-terpilih {
        font-size: 15px;
        font-weight: 500;
        color: #b66dff;
        min-height: 24px;
    }
    /* Select2 Container */
    .select2-container {
        width: 100% !important;
    }
    /* Match Bootstrap form-control height & style */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 4px 8px;
        background-color: #fff;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px;
        color: #495057;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 4px;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear {
        margin-right: 4px;
        font-size: 18px;
        color: #999;
    }
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #b66dff;
        box-shadow: 0 0 0 0.15rem rgba(182, 109, 255, 0.25);
    }
    .select2-dropdown {
        border-color: #ced4da;
        border-radius: 4px;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #b66dff;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 6px 8px;
    }
</style>
@endpush

@section('content')
<div class="row">

    {{-- ======================= CARD 1: Select Biasa ======================= --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select</h4>
                <p class="card-description">Menggunakan element <code>&lt;select&gt;</code> biasa</p>

                {{-- Input Kota + Tombol Tambahkan --}}
                <div class="form-group">
                    <label for="inputKota1">Kota:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputKota1" placeholder="Masukkan nama kota">
                        <button type="button" class="btn btn-success" id="btnTambah1" onclick="tambahKota1(this)">Tambahkan</button>
                    </div>
                </div>

                {{-- Select Kota --}}
                <div class="form-group">
                    <label for="selectKota1">Select Kota:</label>
                    <select class="form-control" id="selectKota1" onchange="pilihKota1()">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Kota Terpilih --}}
                <div class="form-group">
                    <label>Kota Terpilih:</label>
                    <div class="kota-terpilih" id="kotaTerpilih1">-</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================= CARD 2: Select2 ======================= --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select 2</h4>
                <p class="card-description">Menggunakan element <code>Select2</code></p>

                {{-- Input Kota + Tombol Tambahkan --}}
                <div class="form-group">
                    <label for="inputKota2">Kota:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputKota2" placeholder="Masukkan nama kota">
                        <button type="button" class="btn btn-success" id="btnTambah2" onclick="tambahKota2(this)">Tambahkan</button>
                    </div>
                </div>

                {{-- Select2 Kota --}}
                <div class="form-group">
                    <label for="selectKota2">Select Kota:</label>
                    <select class="form-control" id="selectKota2">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Kota Terpilih --}}
                <div class="form-group">
                    <label>Kota Terpilih:</label>
                    <div class="kota-terpilih" id="kotaTerpilih2">-</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Select2 JS --}}
<script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 pada card kedua
    $('#selectKota2').select2({
        placeholder: '-- Pilih Kota --',
        allowClear: true,
        width: '100%'
    });

    // Event change Select2
    $('#selectKota2').on('change', function() {
        var val = $(this).val();
        document.getElementById('kotaTerpilih2').textContent = val ? val : '-';
    });
});

// ========================
// CARD 1: Select Biasa
// ========================
function tambahKota1(btn) {
    var input = document.getElementById('inputKota1');
    var kota = input.value.trim();

    if (!kota) {
        input.focus();
        return;
    }

    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

    setTimeout(function() {
        var select = document.getElementById('selectKota1');

        var exists = false;
        for (var i = 0; i < select.options.length; i++) {
            if (select.options[i].value.toLowerCase() === kota.toLowerCase()) {
                exists = true;
                break;
            }
        }

        if (!exists) {
            var option = document.createElement('option');
            option.value = kota;
            option.textContent = kota;
            select.appendChild(option);
        }

        input.value = '';
        input.focus();

        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }, 400);
}

function pilihKota1() {
    var select = document.getElementById('selectKota1');
    var val = select.value;
    document.getElementById('kotaTerpilih1').textContent = val ? val : '-';
}

// ========================
// CARD 2: Select2
// ========================
function tambahKota2(btn) {
    var input = document.getElementById('inputKota2');
    var kota = input.value.trim();

    if (!kota) {
        input.focus();
        return;
    }

    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

    setTimeout(function() {
        var $select = $('#selectKota2');

        var exists = false;
        $select.find('option').each(function() {
            if ($(this).val().toLowerCase() === kota.toLowerCase()) {
                exists = true;
                return false;
            }
        });

        if (!exists) {
            var newOption = new Option(kota, kota, false, false);
            $select.append(newOption).trigger('change');
        }

        input.value = '';
        input.focus();

        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }, 400);
}
</script>
@endpush
