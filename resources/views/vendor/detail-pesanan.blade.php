@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('icon', 'mdi-receipt')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('vendor.lunas-pesanan') }}">Pesanan Lunas</a></li>
<li class="breadcrumb-item active">Detail Pesanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Detail Pesanan #{{ str_pad($pesanan->id_pesanan, 6, '0', STR_PAD_LEFT) }}</h4>

                <div class="mb-3">
                    <strong>Nama Customer:</strong> {{ $pesanan->nama_customer }}<br>
                    <strong>Tipe Pesanan:</strong> {{ $pesanan->tipe_pesanan }}<br>
                    <strong>Status:</strong> 
                    @if($pesanan->status_bayar == 1)
                        <span class="badge badge-success">Lunas</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
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

                <a href="{{ route('vendor.lunas-pesanan') }}" class="btn btn-secondary btn-nav-spinner">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-nav-spinner').forEach(function(btn) {
        btn.addEventListener('click', function() {
            btn.classList.add('disabled');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memuat...';
        });
    });
});
</script>
@endpush
