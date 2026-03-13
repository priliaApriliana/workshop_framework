@extends('layouts.app')

@section('title', 'Wilayah Indonesia')
@section('icon', 'mdi-map-marker')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Wilayah Indonesia</li>
@endsection

@push('styles')
<style>
    .card-title {
        color: #b66dff;
        font-weight: 600;
    }
    .badge-ajax {
        background-color: #ff6e40;
        color: white;
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 10px;
    }
    .badge-axios {
        background-color: #00d25b;
        color: white;
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 10px;
    }
    .result-box {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        padding: 15px;
        min-height: 60px;
        margin-top: 20px;
    }
    .result-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 5px;
    }
    .result-value {
        font-size: 15px;
        color: #b66dff;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="row">
    
    {{-- ======================= CARD 1: AJAX ======================= --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    Cascading Select Wilayah 
                    <span class="badge-ajax">AJAX</span>
                </h4>
                <p class="card-description">Menggunakan jQuery AJAX untuk load data wilayah</p>

                {{-- Select Provinsi --}}
                <div class="form-group">
                    <label for="provinsi1">Provinsi:</label>
                    <select class="form-control" id="provinsi1">
                        <option value="0">-- Pilih Provinsi --</option>
                    </select>
                </div>

                {{-- Select Kota --}}
                <div class="form-group">
                    <label for="kota1">Kota/Kabupaten:</label>
                    <select class="form-control" id="kota1" disabled>
                        <option value="0">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Select Kecamatan --}}
                <div class="form-group">
                    <label for="kecamatan1">Kecamatan:</label>
                    <select class="form-control" id="kecamatan1" disabled>
                        <option value="0">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                {{-- Select Kelurahan --}}
                <div class="form-group">
                    <label for="kelurahan1">Kelurahan:</label>
                    <select class="form-control" id="kelurahan1" disabled>
                        <option value="0">-- Pilih Kelurahan --</option>
                    </select>
                </div>

                {{-- Hasil Pilihan --}}
                <div class="result-box">
                    <div class="result-label">Alamat Terpilih:</div>
                    <div class="result-value" id="hasil1">-</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================= CARD 2: AXIOS ======================= --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    Cascading Select Wilayah 
                    <span class="badge-axios">AXIOS</span>
                </h4>
                <p class="card-description">Menggunakan Axios untuk load data wilayah</p>

                {{-- Select Provinsi --}}
                <div class="form-group">
                    <label for="provinsi2">Provinsi:</label>
                    <select class="form-control" id="provinsi2">
                        <option value="0">-- Pilih Provinsi --</option>
                    </select>
                </div>

                {{-- Select Kota --}}
                <div class="form-group">
                    <label for="kota2">Kota/Kabupaten:</label>
                    <select class="form-control" id="kota2" disabled>
                        <option value="0">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Select Kecamatan --}}
                <div class="form-group">
                    <label for="kecamatan2">Kecamatan:</label>
                    <select class="form-control" id="kecamatan2" disabled>
                        <option value="0">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                {{-- Select Kelurahan --}}
                <div class="form-group">
                    <label for="kelurahan2">Kelurahan:</label>
                    <select class="form-control" id="kelurahan2" disabled>
                        <option value="0">-- Pilih Kelurahan --</option>
                    </select>
                </div>

                {{-- Hasil Pilihan --}}
                <div class="result-box">
                    <div class="result-label">Alamat Terpilih:</div>
                    <div class="result-value" id="hasil2">-</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Axios CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// ============================================================
// CARD 1: AJAX
// ============================================================
$(document).ready(function() {
    // Load Provinsi saat halaman dibuka
    loadProvinsiAjax();

    // Event: Provinsi berubah
    $('#provinsi1').on('change', function() {
        const provinsiId = $(this).val();
        const provinsiNama = $(this).find('option:selected').text();
        
        // Reset kota, kecamatan, kelurahan
        resetSelect('#kota1');
        resetSelect('#kecamatan1');
        resetSelect('#kelurahan1');
        updateHasil('#hasil1');

        if (provinsiId && provinsiId != '0') {
            loadKotaAjax(provinsiId);
            updateHasil('#hasil1', provinsiNama);
        }
    });

    // Event: Kota berubah
    $('#kota1').on('change', function() {
        const kotaId = $(this).val();
        const provinsiNama = $('#provinsi1 option:selected').text();
        const kotaNama = $(this).find('option:selected').text();

        // Reset kecamatan, kelurahan
        resetSelect('#kecamatan1');
        resetSelect('#kelurahan1');

        if (kotaId && kotaId != '0') {
            loadKecamatanAjax(kotaId);
            updateHasil('#hasil1', provinsiNama, kotaNama);
        } else {
            updateHasil('#hasil1', provinsiNama);
        }
    });

    // Event: Kecamatan berubah
    $('#kecamatan1').on('change', function() {
        const kecamatanId = $(this).val();
        const provinsiNama = $('#provinsi1 option:selected').text();
        const kotaNama = $('#kota1 option:selected').text();
        const kecamatanNama = $(this).find('option:selected').text();

        // Reset kelurahan
        resetSelect('#kelurahan1');

        if (kecamatanId && kecamatanId != '0') {
            loadKelurahanAjax(kecamatanId);
            updateHasil('#hasil1', provinsiNama, kotaNama, kecamatanNama);
        } else {
            updateHasil('#hasil1', provinsiNama, kotaNama);
        }
    });

    // Event: Kelurahan berubah
    $('#kelurahan1').on('change', function() {
        const provinsiNama = $('#provinsi1 option:selected').text();
        const kotaNama = $('#kota1 option:selected').text();
        const kecamatanNama = $('#kecamatan1 option:selected').text();
        const kelurahanNama = $(this).find('option:selected').text();

        if ($(this).val() && $(this).val() != '0') {
            updateHasil('#hasil1', provinsiNama, kotaNama, kecamatanNama, kelurahanNama);
        } else {
            updateHasil('#hasil1', provinsiNama, kotaNama, kecamatanNama);
        }
    });
});

// Load Provinsi via AJAX
function loadProvinsiAjax() {
    $.ajax({
        url: "{{ route('wilayah.provinsi') }}",
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#provinsi1').empty().append('<option value="0"><span class="spinner-border spinner-border-sm"></span> Memuat provinsi...</option>').prop('disabled', true);
        },
        success: function(data) {
            $('#provinsi1').empty().append('<option value="0">-- Pilih Provinsi --</option>');
            $.each(data, function(key, value) {
                $('#provinsi1').append('<option value="'+ value.id +'">'+ value.nama +'</option>');
            });
            $('#provinsi1').prop('disabled', false);
        },
        error: function(xhr) {
            console.error('Error loading provinsi:', xhr);
            $('#provinsi1').prop('disabled', false);
        }
    });
}

// Load Kota via AJAX
function loadKotaAjax(provinsiId) {
    $.ajax({
        url: "{{ url('wilayah/kota') }}/" + provinsiId,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#kota1').empty().append('<option value="0">Memuat kota...</option>').prop('disabled', true);
        },
        success: function(data) {
            $('#kota1').empty().append('<option value="0">-- Pilih Kota --</option>');
            $.each(data, function(key, value) {
                $('#kota1').append('<option value="'+ value.id +'">'+ value.nama +'</option>');
            });
            $('#kota1').prop('disabled', false);
        },
        error: function(xhr) {
            console.error('Error loading kota:', xhr);
            $('#kota1').prop('disabled', false);
        }
    });
}

// Load Kecamatan via AJAX
function loadKecamatanAjax(kotaId) {
    $.ajax({
        url: "{{ url('wilayah/kecamatan') }}/" + kotaId,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#kecamatan1').empty().append('<option value="0">Memuat kecamatan...</option>').prop('disabled', true);
        },
        success: function(data) {
            $('#kecamatan1').empty().append('<option value="0">-- Pilih Kecamatan --</option>');
            $.each(data, function(key, value) {
                $('#kecamatan1').append('<option value="'+ value.id +'">'+ value.nama +'</option>');
            });
            $('#kecamatan1').prop('disabled', false);
        },
        error: function(xhr) {
            console.error('Error loading kecamatan:', xhr);
            $('#kecamatan1').prop('disabled', false);
        }
    });
}

// Load Kelurahan via AJAX
function loadKelurahanAjax(kecamatanId) {
    $.ajax({
        url: "{{ url('wilayah/kelurahan') }}/" + kecamatanId,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#kelurahan1').empty().append('<option value="0">Memuat kelurahan...</option>').prop('disabled', true);
        },
        success: function(data) {
            $('#kelurahan1').empty().append('<option value="0">-- Pilih Kelurahan --</option>');
            $.each(data, function(key, value) {
                $('#kelurahan1').append('<option value="'+ value.id +'">'+ value.nama +'</option>');
            });
            $('#kelurahan1').prop('disabled', false);
        },
        error: function(xhr) {
            console.error('Error loading kelurahan:', xhr);
            $('#kelurahan1').prop('disabled', false);
        }
    });
}

// ============================================================
// CARD 2: AXIOS
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    // Load Provinsi saat halaman dibuka
    loadProvinsiAxios();

    // Event: Provinsi berubah
    document.getElementById('provinsi2').addEventListener('change', function() {
        const provinsiId = this.value;
        const provinsiNama = this.options[this.selectedIndex].text;

        // Reset kota, kecamatan, kelurahan
        resetSelectVanilla('#kota2');
        resetSelectVanilla('#kecamatan2');
        resetSelectVanilla('#kelurahan2');
        updateHasilVanilla('#hasil2');

        if (provinsiId && provinsiId != '0') {
            loadKotaAxios(provinsiId);
            updateHasilVanilla('#hasil2', provinsiNama);
        }
    });

    // Event: Kota berubah
    document.getElementById('kota2').addEventListener('change', function() {
        const kotaId = this.value;
        const provinsiNama = document.querySelector('#provinsi2 option:checked').text;
        const kotaNama = this.options[this.selectedIndex].text;

        // Reset kecamatan, kelurahan
        resetSelectVanilla('#kecamatan2');
        resetSelectVanilla('#kelurahan2');

        if (kotaId && kotaId != '0') {
            loadKecamatanAxios(kotaId);
            updateHasilVanilla('#hasil2', provinsiNama, kotaNama);
        } else {
            updateHasilVanilla('#hasil2', provinsiNama);
        }
    });

    // Event: Kecamatan berubah
    document.getElementById('kecamatan2').addEventListener('change', function() {
        const kecamatanId = this.value;
        const provinsiNama = document.querySelector('#provinsi2 option:checked').text;
        const kotaNama = document.querySelector('#kota2 option:checked').text;
        const kecamatanNama = this.options[this.selectedIndex].text;

        // Reset kelurahan
        resetSelectVanilla('#kelurahan2');

        if (kecamatanId && kecamatanId != '0') {
            loadKelurahanAxios(kecamatanId);
            updateHasilVanilla('#hasil2', provinsiNama, kotaNama, kecamatanNama);
        } else {
            updateHasilVanilla('#hasil2', provinsiNama, kotaNama);
        }
    });

    // Event: Kelurahan berubah
    document.getElementById('kelurahan2').addEventListener('change', function() {
        const provinsiNama = document.querySelector('#provinsi2 option:checked').text;
        const kotaNama = document.querySelector('#kota2 option:checked').text;
        const kecamatanNama = document.querySelector('#kecamatan2 option:checked').text;
        const kelurahanNama = this.options[this.selectedIndex].text;

        if (this.value && this.value != '0') {
            updateHasilVanilla('#hasil2', provinsiNama, kotaNama, kecamatanNama, kelurahanNama);
        } else {
            updateHasilVanilla('#hasil2', provinsiNama, kotaNama, kecamatanNama);
        }
    });
});

// Load Provinsi via Axios
function loadProvinsiAxios() {
    const selectProv = document.getElementById('provinsi2');
    selectProv.innerHTML = '<option value="0">Memuat provinsi...</option>';
    selectProv.disabled = true;
    
    axios.get("{{ route('wilayah.provinsi') }}")
        .then(function(response) {
            selectProv.innerHTML = '<option value="0">-- Pilih Provinsi --</option>';
            
            response.data.forEach(function(item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                selectProv.appendChild(option);
            });
            selectProv.disabled = false;
        })
        .catch(function(error) {
            console.error('Error loading provinsi:', error);
            selectProv.disabled = false;
        });
}

// Load Kota via Axios
function loadKotaAxios(provinsiId) {
    const select = document.getElementById('kota2');
    select.innerHTML = '<option value="0">Memuat kota...</option>';
    select.disabled = true;
    
    axios.get("{{ url('wilayah/kota') }}/" + provinsiId)
        .then(function(response) {
            select.innerHTML = '<option value="0">-- Pilih Kota --</option>';
            
            response.data.forEach(function(item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            
            select.disabled = false;
        })
        .catch(function(error) {
            console.error('Error loading kota:', error);
            select.disabled = false;
        });
}

// Load Kecamatan via Axios
function loadKecamatanAxios(kotaId) {
    const select = document.getElementById('kecamatan2');
    select.innerHTML = '<option value="0">Memuat kecamatan...</option>';
    select.disabled = true;
    
    axios.get("{{ url('wilayah/kecamatan') }}/" + kotaId)
        .then(function(response) {
            select.innerHTML = '<option value="0">-- Pilih Kecamatan --</option>';
            
            response.data.forEach(function(item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            
            select.disabled = false;
        })
        .catch(function(error) {
            console.error('Error loading kecamatan:', error);
            select.disabled = false;
        });
}

// Load Kelurahan via Axios
function loadKelurahanAxios(kecamatanId) {
    const select = document.getElementById('kelurahan2');
    select.innerHTML = '<option value="0">Memuat kelurahan...</option>';
    select.disabled = true;
    
    axios.get("{{ url('wilayah/kelurahan') }}/" + kecamatanId)
        .then(function(response) {
            select.innerHTML = '<option value="0">-- Pilih Kelurahan --</option>';
            
            response.data.forEach(function(item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                select.appendChild(option);
            });
            
            select.disabled = false;
        })
        .catch(function(error) {
            console.error('Error loading kelurahan:', error);
            select.disabled = false;
        });
}

// ============================================================
// HELPER FUNCTIONS
// ============================================================

// Reset select (jQuery version)
function resetSelect(selector) {
    $(selector).empty().append('<option value="0">-- Pilih ' + 
        $(selector).prev('label').text().split(':')[0] + ' --</option>')
        .prop('disabled', true);
}

// Reset select (Vanilla JS version)
function resetSelectVanilla(selector) {
    const elem = document.querySelector(selector);
    const label = elem.previousElementSibling.textContent.split(':')[0];
    elem.innerHTML = '<option value="0">-- Pilih ' + label + ' --</option>';
    elem.disabled = true;
}

// Update hasil (jQuery version)
function updateHasil(selector, provinsi, kota, kecamatan, kelurahan) {
    let text = [];
    if (kelurahan && kelurahan !== '-- Pilih Kelurahan --') text.push(kelurahan);
    if (kecamatan && kecamatan !== '-- Pilih Kecamatan --') text.push(kecamatan);
    if (kota && kota !== '-- Pilih Kota --') text.push(kota);
    if (provinsi && provinsi !== '-- Pilih Provinsi --') text.push(provinsi);
    
    $(selector).text(text.length > 0 ? text.join(', ') : '-');
}

// Update hasil (Vanilla JS version)
function updateHasilVanilla(selector, provinsi, kota, kecamatan, kelurahan) {
    let text = [];
    if (kelurahan && kelurahan !== '-- Pilih Kelurahan --') text.push(kelurahan);
    if (kecamatan && kecamatan !== '-- Pilih Kecamatan --') text.push(kecamatan);
    if (kota && kota !== '-- Pilih Kota --') text.push(kota);
    if (provinsi && provinsi !== '-- Pilih Provinsi --') text.push(provinsi);
    
    document.querySelector(selector).textContent = text.length > 0 ? text.join(', ') : '-';
}
</script>
@endpush