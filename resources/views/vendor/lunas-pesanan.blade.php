@extends('layouts.app')

@section('title', 'Pesanan Lunas')
@section('icon', 'mdi-cash-check')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Vendor Dashboard</a></li>
<li class="breadcrumb-item active">Pesanan Lunas</li>
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

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Pesanan Lunas</h4>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Customer</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $pesanan)
                                <tr>
                                    <td>#{{ str_pad($pesanan->id_pesanan, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $pesanan->nama_customer }}</td>
                                    <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                                    <td>{{ $pesanan->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('vendor.detail-pesanan', $pesanan->id_pesanan) }}" class="btn btn-sm btn-info btn-nav-spinner">
                                            <i class="mdi mdi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada pesanan lunas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $pesanans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
