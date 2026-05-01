@extends('layouts.app')

@section('title', 'Status Pesanan')
@section('icon', 'mdi-receipt-text')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('customer.order.index') }}">Customer Order</a></li>
<li class="breadcrumb-item active">Status Pesanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Status Pesanan #{{ str_pad($pesanan->id_pesanan, 6, '0', STR_PAD_LEFT) }}</h4>

                {{-- Status Badge Besar --}}
                <div class="text-center mb-4" id="status-display">
                    @if($pesanan->status_bayar == 1)
                        <div class="d-inline-block p-3 rounded" style="background: #d4edda;">
                            <i class="mdi mdi-check-circle text-success" style="font-size: 48px;"></i>
                            <h3 class="text-success mt-2 mb-0">LUNAS</h3>
                            <p class="text-muted">Pembayaran berhasil diterima</p>
                        </div>
                    @else
                        <div class="d-inline-block p-3 rounded" style="background: #fff3cd;">
                            <i class="mdi mdi-clock-outline text-warning" style="font-size: 48px;"></i>
                            <h3 class="text-warning mt-2 mb-0">MENUNGGU PEMBAYARAN</h3>
                            <p class="text-muted">Silakan selesaikan pembayaran, lalu klik tombol "Cek Pembayaran"</p>
                        </div>
                    @endif
                </div>

                {{-- Info Customer --}}
                <div class="mb-3">
                    <p><strong>Nama Customer:</strong> {{ $pesanan->nama_customer }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                    <p><strong>Metode Bayar:</strong>
                        @if($pesanan->metode_bayar == 1)
                            <span class="badge badge-primary">Virtual Account</span>
                        @elseif($pesanan->metode_bayar == 2)
                            <span class="badge badge-info">QRIS</span>
                        @else
                            <span class="badge badge-secondary">Belum dipilih</span>
                        @endif
                    </p>
                    <p><strong>Waktu Order:</strong> {{ $pesanan->created_at->format('d M Y H:i:s') }}</p>
                </div>

                <hr>

                {{-- Tabel Detail Item --}}
                <h5>Detail Pesanan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr><th>Menu</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->detailPesanans as $detail)
                                <tr>
                                    <td>{{ $detail->menu->nama_menu ?? '-' }}</td>
                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Info Payment dari Midtrans --}}
                @if($pesanan->payments->count() > 0)
                    <hr>
                    <h5>Info Pembayaran</h5>
                    @php $latestPayment = $pesanan->payments->first(); @endphp
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Payment Reference</strong></td>
                            <td>{{ $latestPayment->payment_reference }}</td>
                        </tr>
                        <tr>
                            <td><strong>Metode</strong></td>
                            <td>{{ ucfirst(str_replace('_', ' ', $latestPayment->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                @if($latestPayment->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @elseif($latestPayment->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($latestPayment->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($latestPayment->paid_at)
                        <tr>
                            <td><strong>Dibayar pada</strong></td>
                            <td>{{ $latestPayment->paid_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        @endif
                    </table>
                @endif

                {{-- Tampilkan QR Code hanya jika sudah lunas --}}
                @if($pesanan->status_bayar == 1 && $qrBase64)
                    <div class="text-center mt-4">
                        <p class="fw-bold">QR Code Pesanan Anda:</p>
                        <img src="data:image/png;base64,{{ $qrBase64 }}"
                            alt="QR Code"
                            style="width:180px; height:180px; border:1px solid #ddd; padding:5px; border-radius:8px;">
                        <p class="text-muted mt-1" style="font-size:12px;">
                            ID Pesanan: <strong>{{ $pesanan->id_pesanan }}</strong>
                        </p>
                        <a href="{{ route('customer.order.qrcode', $pesanan->id_pesanan) }}" class="btn btn-outline-primary btn-sm mt-2">
                            Buka Halaman QR Permanen
                        </a>
                    </div>
                @endif

                {{-- Tombol Aksi --}}
                <div class="mt-3">
                    @if($pesanan->status_bayar == 0)
                        <button id="btn-check-payment" class="btn btn-success btn-lg mr-2">
                            <i class="mdi mdi-refresh"></i> Cek Pembayaran
                        </button>
                        <a href="{{ route('customer.order.payment', $pesanan->id_pesanan) }}" class="btn btn-primary mr-2">
                            <i class="mdi mdi-credit-card"></i> Bayar Sekarang
                        </a>
                    @endif
                    <a href="{{ route('customer.order.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-plus"></i> Order Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCheck = document.getElementById('btn-check-payment');
    if (!btnCheck) return;

    btnCheck.addEventListener('click', function() {
        btnCheck.disabled = true;
        btnCheck.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mengecek...';

        fetch('{{ route("customer.order.check-payment", $pesanan->id_pesanan) }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.status === 'settlement') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else if (data.success && data.status === 'pending') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Masih Pending',
                        text: 'Pembayaran belum diterima. Silakan bayar dulu lewat simulator Midtrans, lalu klik Cek Pembayaran lagi.',
                        confirmButtonText: 'OK'
                    });
                    btnCheck.disabled = false;
                    btnCheck.innerHTML = '<i class="mdi mdi-refresh"></i> Cek Pembayaran';
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Status',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                    btnCheck.disabled = false;
                    btnCheck.innerHTML = '<i class="mdi mdi-refresh"></i> Cek Pembayaran';
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Gagal mengecek status pembayaran', 'error');
                btnCheck.disabled = false;
                btnCheck.innerHTML = '<i class="mdi mdi-refresh"></i> Cek Pembayaran';
            });
    });
});
</script>
@endpush
