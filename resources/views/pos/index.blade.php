@extends('layouts.app')

@section('title', 'Point of Sales (POS)')
@section('icon', 'mdi-cart')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Point of Sales</li>
@endsection

@push('styles')
{{-- SweetAlert2 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .card-title {
        color: #b66dff;
        font-weight: 600;
    }
    .badge-method {
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 10px;
        margin-left: 5px;
    }
    .badge-ajax {
        background-color: #ff6e40;
        color: white;
    }
    .badge-axios {
        background-color: #00d25b;
        color: white;
    }
    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }
    .table thead th {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 10;
    }
    .total-box {
        background-color: #b66dff;
        color: white;
        padding: 15px;
        border-radius: 5px;
        text-align: right;
        margin-top: 10px;
    }
    .total-label {
        font-size: 14px;
        font-weight: 500;
    }
    .total-value {
        font-size: 24px;
        font-weight: 700;
    }
    input:read-only {
        background-color: #e9ecef;
    }
    .btn-hapus {
        padding: 2px 8px;
        font-size: 12px;
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
                    <i class="mdi mdi-cart"></i> Kasir
                    <span class="badge-method badge-ajax">AJAX</span>
                </h4>
                
                {{-- Form Input Barang --}}
                <div class="form-group">
                    <label for="kodeBarang1">Kode Barang:</label>
                    <input type="text" class="form-control" id="kodeBarang1" 
                           placeholder="Scan atau ketik kode barang">
                </div>

                <div class="form-group">
                    <label for="namaBarang1">Nama Barang:</label>
                    <input type="text" class="form-control" id="namaBarang1" 
                           placeholder="Nama barang" readonly>
                </div>

                <div class="form-group">
                    <label for="hargaBarang1">Harga Barang:</label>
                    <input type="number" class="form-control" id="hargaBarang1" 
                           placeholder="Harga barang" readonly>
                </div>

                <div class="form-group">
                    <label for="jumlah1">Jumlah:</label>
                    <input type="number" class="form-control" id="jumlah1" 
                           value="1" min="1">
                </div>

                <button type="button" class="btn btn-success btn-block" 
                        id="btnTambah1" onclick="tambahBarangAjax(this)" disabled>
                    Tambahkan
                </button>

                <hr>

                {{-- Tabel Transaksi --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tablePenjualan1">
                        <thead>
                            <tr>
                                <th width="30%">Kode</th>
                                <th width="25%">Nama</th>
                                <th width="15%">Harga</th>
                                <th width="10%">Qty</th>
                                <th width="15%">Subtotal</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody id="bodyPenjualan1">
                            <tr id="emptyRow1">
                                <td colspan="6" class="text-center text-muted">
                                    <small>Belum ada barang</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Total --}}
                <div class="total-box">
                    <div class="total-label">Total</div>
                    <div class="total-value" id="totalNilai1">Rp 0</div>
                </div>

                {{-- Tombol Bayar --}}
                <button type="button" class="btn btn-primary btn-block mt-3" 
                        id="btnBayar1" onclick="bayarAjax(this)" disabled>
                    <i class="mdi mdi-cash"></i> Bayar
                </button>
            </div>
        </div>
    </div>

    {{-- ======================= CARD 2: AXIOS ======================= --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-cart"></i> Kasir
                    <span class="badge-method badge-axios">AXIOS</span>
                </h4>
                
                {{-- Form Input Barang --}}
                <div class="form-group">
                    <label for="kodeBarang2">Kode Barang:</label>
                    <input type="text" class="form-control" id="kodeBarang2" 
                           placeholder="Scan atau ketik kode barang">
                </div>

                <div class="form-group">
                    <label for="namaBarang2">Nama Barang:</label>
                    <input type="text" class="form-control" id="namaBarang2" 
                           placeholder="Nama barang" readonly>
                </div>

                <div class="form-group">
                    <label for="hargaBarang2">Harga Barang:</label>
                    <input type="number" class="form-control" id="hargaBarang2" 
                           placeholder="Harga barang" readonly>
                </div>

                <div class="form-group">
                    <label for="jumlah2">Jumlah:</label>
                    <input type="number" class="form-control" id="jumlah2" 
                           value="1" min="1">
                </div>

                <button type="button" class="btn btn-success btn-block" 
                        id="btnTambah2" onclick="tambahBarangAxios(this)" disabled>
                    Tambahkan
                </button>

                <hr>

                {{-- Tabel Transaksi --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tablePenjualan2">
                        <thead>
                            <tr>
                                <th width="30%">Kode</th>
                                <th width="25%">Nama</th>
                                <th width="15%">Harga</th>
                                <th width="10%">Qty</th>
                                <th width="15%">Subtotal</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody id="bodyPenjualan2">
                            <tr id="emptyRow2">
                                <td colspan="6" class="text-center text-muted">
                                    <small>Belum ada barang</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Total --}}
                <div class="total-box">
                    <div class="total-label">Total</div>
                    <div class="total-value" id="totalNilai2">Rp 0</div>
                </div>

                {{-- Tombol Bayar --}}
                <button type="button" class="btn btn-primary btn-block mt-3" 
                        id="btnBayar2" onclick="bayarAxios(this)" disabled>
                    <i class="mdi mdi-cash"></i> Bayar
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Axios CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// ============================================================
// GLOBAL VARIABLES
// ============================================================
let cartAjax = []; // Keranjang untuk AJAX
let cartAxios = []; // Keranjang untuk Axios
let currentBarangAjax = null; // Data barang saat ini (AJAX)
let currentBarangAxios = null; // Data barang saat ini (Axios)

// ============================================================
// CARD 1: AJAX VERSION
// ============================================================
$(document).ready(function() {
    // Event: Enter pada input kode barang
    $('#kodeBarang1').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const kode = $(this).val().trim();
            if (kode) {
                cariBarangAjax(kode);
            }
        }
    });

    // Event: Jumlah berubah
    $('#jumlah1').on('input', function() {
        checkTombolTambahAjax();
    });
});

// Cari barang via AJAX
function cariBarangAjax(kode) {
    $.ajax({
        url: "{{ url('pos/barang') }}/" + kode,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#kodeBarang1').prop('disabled', true);
            $('#namaBarang1').val('Mencari...');
            $('#hargaBarang1').val('');
            $('#btnTambah1').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Mencari...');
        },
        success: function(response) {
            $('#kodeBarang1').prop('disabled', false);
            $('#btnTambah1').html('Tambahkan');
            if (response.success) {
                currentBarangAjax = response.data;
                $('#namaBarang1').val(response.data.nama);
                $('#hargaBarang1').val(response.data.harga);
                $('#jumlah1').val(1).focus();
                checkTombolTambahAjax();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Barang Tidak Ditemukan',
                    text: response.message,
                    confirmButtonColor: '#b66dff'
                });
                resetFormAjax();
            }
        },
        error: function(xhr) {
            $('#kodeBarang1').prop('disabled', false);
            $('#btnTambah1').html('Tambahkan');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mencari barang',
                confirmButtonColor: '#b66dff'
            });
            resetFormAjax();
        }
    });
}

// Tambah barang ke keranjang (AJAX)
function tambahBarangAjax(btn) {
    if (!currentBarangAjax) return;

    const jumlah = parseInt($('#jumlah1').val());
    if (jumlah <= 0) return;

    const subtotal = currentBarangAjax.harga * jumlah;

    // Cek apakah barang sudah ada di cart
    const existingIndex = cartAjax.findIndex(item => item.id_barang === currentBarangAjax.id_barang);

    if (existingIndex >= 0) {
        // Update jumlah dan subtotal
        cartAjax[existingIndex].jumlah += jumlah;
        cartAjax[existingIndex].subtotal = cartAjax[existingIndex].harga * cartAjax[existingIndex].jumlah;
    } else {
        // Tambah item baru
        cartAjax.push({
            id_barang: currentBarangAjax.id_barang,
            nama: currentBarangAjax.nama,
            harga: currentBarangAjax.harga,
            jumlah: jumlah,
            subtotal: subtotal
        });
    }

    // Render ulang tabel
    renderTableAjax();
    resetFormAjax();
    $('#kodeBarang1').focus();
}

// Render tabel keranjang (AJAX)
function renderTableAjax() {
    const tbody = $('#bodyPenjualan1');
    tbody.empty();

    if (cartAjax.length === 0) {
        tbody.append(`
            <tr id="emptyRow1">
                <td colspan="6" class="text-center text-muted">
                    <small>Belum ada barang</small>
                </td>
            </tr>
        `);
        $('#btnBayar1').prop('disabled', true);
    } else {
        cartAjax.forEach((item, index) => {
            tbody.append(`
                <tr>
                    <td>${item.id_barang}</td>
                    <td>${item.nama}</td>
                    <td>${formatRupiah(item.harga)}</td>
                    <td><input type="number" class="form-control form-control-sm" value="${item.jumlah}" min="1" style="width:65px" onchange="updateJumlahAjax(${index}, this.value)"></td>
                    <td>${formatRupiah(item.subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-hapus" 
                                onclick="hapusItemAjax(${index})">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
        $('#btnBayar1').prop('disabled', false);
    }

    updateTotalAjax();
}

// Hapus item dari keranjang (AJAX)
function hapusItemAjax(index) {
    cartAjax.splice(index, 1);
    renderTableAjax();
}

// Update jumlah item di keranjang (AJAX)
function updateJumlahAjax(index, newQty) {
    newQty = parseInt(newQty);
    if (isNaN(newQty) || newQty <= 0) {
        hapusItemAjax(index);
        return;
    }
    cartAjax[index].jumlah = newQty;
    cartAjax[index].subtotal = cartAjax[index].harga * newQty;
    renderTableAjax();
}

// Update total (AJAX)
function updateTotalAjax() {
    const total = cartAjax.reduce((sum, item) => sum + item.subtotal, 0);
    $('#totalNilai1').text(formatRupiah(total));
}

// Reset form input (AJAX)
function resetFormAjax() {
    $('#kodeBarang1').val('');
    $('#namaBarang1').val('');
    $('#hargaBarang1').val('');
    $('#jumlah1').val(1);
    $('#btnTambah1').prop('disabled', true);
    currentBarangAjax = null;
}

// Check tombol tambah (AJAX)
function checkTombolTambahAjax() {
    const jumlah = parseInt($('#jumlah1').val());
    const enabled = currentBarangAjax !== null && jumlah > 0;
    $('#btnTambah1').prop('disabled', !enabled);
}

// Bayar transaksi (AJAX)
function bayarAjax(btn) {
    if (cartAjax.length === 0) return;

    const total = cartAjax.reduce((sum, item) => sum + item.subtotal, 0);

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        html: `
            <div class="text-left">
                <p><strong>Total Item:</strong> ${cartAjax.length}</p>
                <p><strong>Total Bayar:</strong> ${formatRupiah(total)}</p>
                <p class="text-muted">Apakah data sudah benar?</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#b66dff',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Bayar!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            simpanTransaksiAjax(btn, total);
        }
    });
}

// Simpan transaksi via AJAX
function simpanTransaksiAjax(btn, total) {
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

    $.ajax({
        url: "{{ route('pos.simpan') }}",
        type: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            items: cartAjax,
            total: total
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    confirmButtonColor: '#b66dff'
                }).then(() => {
                    // Reset semua data
                    cartAjax = [];
                    renderTableAjax();
                    resetFormAjax();
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan transaksi',
                confirmButtonColor: '#b66dff'
            });
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    });
}

// ============================================================
// CARD 2: AXIOS VERSION
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    // Event: Enter pada input kode barang
    document.getElementById('kodeBarang2').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const kode = this.value.trim();
            if (kode) {
                cariBarangAxios(kode);
            }
        }
    });

    // Event: Jumlah berubah
    document.getElementById('jumlah2').addEventListener('input', function() {
        checkTombolTambahAxios();
    });
});

// Cari barang via Axios
function cariBarangAxios(kode) {
    document.getElementById('kodeBarang2').disabled = true;
    document.getElementById('namaBarang2').value = 'Mencari...';
    document.getElementById('hargaBarang2').value = '';
    document.getElementById('btnTambah2').disabled = true;
    document.getElementById('btnTambah2').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mencari...';

    axios.get("{{ url('pos/barang') }}/" + kode)
        .then(function(response) {
            document.getElementById('kodeBarang2').disabled = false;
            document.getElementById('btnTambah2').innerHTML = 'Tambahkan';
            if (response.data.success) {
                currentBarangAxios = response.data.data;
                document.getElementById('namaBarang2').value = response.data.data.nama;
                document.getElementById('hargaBarang2').value = response.data.data.harga;
                document.getElementById('jumlah2').value = 1;
                document.getElementById('jumlah2').focus();
                checkTombolTambahAxios();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Barang Tidak Ditemukan',
                    text: response.data.message,
                    confirmButtonColor: '#b66dff'
                });
                resetFormAxios();
            }
        })
        .catch(function(error) {
            document.getElementById('kodeBarang2').disabled = false;
            document.getElementById('btnTambah2').innerHTML = 'Tambahkan';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mencari barang',
                confirmButtonColor: '#b66dff'
            });
            resetFormAxios();
        });
}

// Tambah barang ke keranjang (Axios)
function tambahBarangAxios(btn) {
    if (!currentBarangAxios) return;

    const jumlah = parseInt(document.getElementById('jumlah2').value);
    if (jumlah <= 0) return;

    const subtotal = currentBarangAxios.harga * jumlah;

    // Cek apakah barang sudah ada di cart
    const existingIndex = cartAxios.findIndex(item => item.id_barang === currentBarangAxios.id_barang);

    if (existingIndex >= 0) {
        // Update jumlah dan subtotal
        cartAxios[existingIndex].jumlah += jumlah;
        cartAxios[existingIndex].subtotal = cartAxios[existingIndex].harga * cartAxios[existingIndex].jumlah;
    } else {
        // Tambah item baru
        cartAxios.push({
            id_barang: currentBarangAxios.id_barang,
            nama: currentBarangAxios.nama,
            harga: currentBarangAxios.harga,
            jumlah: jumlah,
            subtotal: subtotal
        });
    }

    // Render ulang tabel
    renderTableAxios();
    resetFormAxios();
    document.getElementById('kodeBarang2').focus();
}

// Render tabel keranjang (Axios)
function renderTableAxios() {
    const tbody = document.getElementById('bodyPenjualan2');
    tbody.innerHTML = '';

    if (cartAxios.length === 0) {
        tbody.innerHTML = `
            <tr id="emptyRow2">
                <td colspan="6" class="text-center text-muted">
                    <small>Belum ada barang</small>
                </td>
            </tr>
        `;
        document.getElementById('btnBayar2').disabled = true;
    } else {
        cartAxios.forEach((item, index) => {
            const row = `
                <tr>
                    <td>${item.id_barang}</td>
                    <td>${item.nama}</td>
                    <td>${formatRupiah(item.harga)}</td>
                    <td><input type="number" class="form-control form-control-sm" value="${item.jumlah}" min="1" style="width:65px" onchange="updateJumlahAxios(${index}, this.value)"></td>
                    <td>${formatRupiah(item.subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-hapus" 
                                onclick="hapusItemAxios(${index})">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
        document.getElementById('btnBayar2').disabled = false;
    }

    updateTotalAxios();
}

// Hapus item dari keranjang (Axios)
function hapusItemAxios(index) {
    cartAxios.splice(index, 1);
    renderTableAxios();
}

// Update jumlah item di keranjang (Axios)
function updateJumlahAxios(index, newQty) {
    newQty = parseInt(newQty);
    if (isNaN(newQty) || newQty <= 0) {
        hapusItemAxios(index);
        return;
    }
    cartAxios[index].jumlah = newQty;
    cartAxios[index].subtotal = cartAxios[index].harga * newQty;
    renderTableAxios();
}

// Update total (Axios)
function updateTotalAxios() {
    const total = cartAxios.reduce((sum, item) => sum + item.subtotal, 0);
    document.getElementById('totalNilai2').textContent = formatRupiah(total);
}

// Reset form input (Axios)
function resetFormAxios() {
    document.getElementById('kodeBarang2').value = '';
    document.getElementById('namaBarang2').value = '';
    document.getElementById('hargaBarang2').value = '';
    document.getElementById('jumlah2').value = 1;
    document.getElementById('btnTambah2').disabled = true;
    currentBarangAxios = null;
}

// Check tombol tambah (Axios)
function checkTombolTambahAxios() {
    const jumlah = parseInt(document.getElementById('jumlah2').value);
    const enabled = currentBarangAxios !== null && jumlah > 0;
    document.getElementById('btnTambah2').disabled = !enabled;
}

// Bayar transaksi (Axios)
function bayarAxios(btn) {
    if (cartAxios.length === 0) return;

    const total = cartAxios.reduce((sum, item) => sum + item.subtotal, 0);

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        html: `
            <div class="text-left">
                <p><strong>Total Item:</strong> ${cartAxios.length}</p>
                <p><strong>Total Bayar:</strong> ${formatRupiah(total)}</p>
                <p class="text-muted">Apakah data sudah benar?</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#b66dff',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Bayar!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            simpanTransaksiAxios(btn, total);
        }
    });
}

// Simpan transaksi via Axios
function simpanTransaksiAxios(btn, total) {
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

    axios.post("{{ route('pos.simpan') }}", {
        items: cartAxios,
        total: total
    }, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(function(response) {
        if (response.data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response.data.message,
                confirmButtonColor: '#b66dff'
            }).then(() => {
                // Reset semua data
                cartAxios = [];
                renderTableAxios();
                resetFormAxios();
            });
        }
    })
    .catch(function(error) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan transaksi',
            confirmButtonColor: '#b66dff'
        });
    })
    .finally(function() {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    });
}

// ============================================================
// HELPER FUNCTIONS
// ============================================================
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush