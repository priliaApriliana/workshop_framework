@extends('layouts.app')

@section('title', 'Customer Order')
@section('icon', 'mdi-store')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Customer Order</li>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-pilih-vendor').forEach(function(btn) {
        btn.addEventListener('click', function() {
            btn.classList.add('disabled');
            btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memuat...';
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
                <h4 class="card-title">Pilih Vendor</h4>
                <div class="row">
                    @forelse($vendors as $vendor)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5>{{ $vendor->nama_vendor }}</h5>
                                    <p class="text-muted mb-3">{{ $vendor->menus->count() }} menu tersedia</p>
                                    <a href="{{ route('customer.order.show', $vendor->id_vendor) }}" class="btn btn-primary btn-sm btn-pilih-vendor">
                                        Pilih Vendor
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12">
                            <div class="alert alert-info">Belum ada vendor.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
