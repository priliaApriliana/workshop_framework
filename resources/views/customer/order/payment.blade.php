@extends('layouts.app')

@section('title', 'Pembayaran')
@section('icon', 'mdi-credit-card')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('customer.order.index') }}">Customer Order</a></li>
<li class="breadcrumb-item active">Pembayaran</li>
@endsection

@section('content')
<div class="row">
    {{-- KOLOM KIRI: Detail Pesanan & Tombol Bayar --}}
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pembayaran Pesanan #{{ str_pad($pesanan->id_pesanan, 6, '0', STR_PAD_LEFT) }}</h4>

                {{-- Detail item pesanan --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
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

                @if($pesanan->status_bayar == 1)
                    {{-- Sudah lunas --}}
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle"></i> Pesanan sudah <strong>LUNAS</strong>!
                    </div>
                    <a href="{{ route('customer.order.status', $pesanan->id_pesanan) }}" class="btn btn-info">
                        <i class="mdi mdi-eye"></i> Lihat Status
                    </a>
                @else
                    {{-- Belum bayar: tampilkan tombol bayar Midtrans --}}
                    @if($snapToken)
                        <button id="btn-pay" class="btn btn-primary btn-lg btn-block">
                            <i class="mdi mdi-credit-card-check"></i> Bayar Sekarang - Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                        </button>
                        <p class="text-muted mt-2">
                            <small>Anda akan diarahkan ke halaman pembayaran Midtrans. Pilih metode: Virtual Account atau QRIS.</small>
                        </p>
                    @else
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert"></i> Gagal memuat halaman pembayaran. Silakan refresh halaman ini.
                        </div>
                    @endif
                @endif

                <a href="{{ route('customer.order.index') }}" class="btn btn-secondary mt-2">
                    <i class="mdi mdi-arrow-left"></i> Order Baru
                </a>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Ringkasan --}}
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5>Ringkasan Pesanan</h5>
                <hr>
                <p><strong>Customer:</strong> {{ $pesanan->nama_customer }}</p>
                <p><strong>Total Item:</strong> {{ $pesanan->detailPesanans->sum('jumlah') }} item</p>
                <p><strong>Total Bayar:</strong></p>
                <h3 class="text-primary">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</h3>
                <hr>
                <p><strong>Status:</strong>
                    @if($pesanan->status_bayar == 1)
                        <span class="badge badge-success">Lunas</span>
                    @else
                        <span class="badge badge-warning">Menunggu Pembayaran</span>
                    @endif
                </p>
                <p><strong>Metode:</strong>
                    @if($pesanan->metode_bayar == 1)
                        Virtual Account
                    @elseif($pesanan->metode_bayar == 2)
                        QRIS
                    @else
                        Belum dipilih
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Midtrans Snap JS (Sandbox) --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnPay = document.getElementById('btn-pay');
    if (!btnPay) return;

    btnPay.addEventListener('click', function() {
        // Show spinner loading state
        const originalContent = btnPay.innerHTML;
        btnPay.disabled = true;
        btnPay.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses Pembayaran...';

        // Buka popup Midtrans Snap
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                // Pembayaran berhasil
                updatePaymentStatus(result, 'Pembayaran Berhasil!');
            },
            onPending: function(result) {
                // Pembayaran pending (misal VA belum dibayar)
                updatePaymentStatus(result, 'Menunggu Pembayaran...');
            },
            onError: function(result) {
                Swal.fire('Gagal', 'Pembayaran gagal. Silakan coba lagi.', 'error');
                btnPay.disabled = false;
                btnPay.innerHTML = originalContent;
            },
            onClose: function() {
                Swal.fire('Info', 'Pembayaran belum selesai. Anda bisa klik tombol bayar lagi.', 'info');
                btnPay.disabled = false;
                btnPay.innerHTML = originalContent;
            }
        });
    });

    function updatePaymentStatus(result, title) {
        fetch('{{ route("customer.order.update-status", $pesanan->id_pesanan) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                transaction_status: result.transaction_status,
                payment_type: result.payment_type,
                order_id: result.order_id
            })
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire('Sukses', title, 'success').then(() => {
                window.location.href = '{{ route("customer.order.status", $pesanan->id_pesanan) }}';
            });
        })
        .catch(error => {
            // Tetap redirect ke status meskipun update gagal
            window.location.href = '{{ route("customer.order.status", $pesanan->id_pesanan) }}';
        });
    }
});
</script>
@endpush