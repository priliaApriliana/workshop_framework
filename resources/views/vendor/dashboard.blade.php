@extends('layouts.app')

@section('title', 'Vendor Dashboard')
@section('icon', 'mdi-view-dashboard')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Vendor Dashboard</li>
@endsection

@section('content')
{{-- Info Vendor / Admin --}}
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert {{ Auth::user()->isAdmin() ? 'alert-info' : 'alert-success' }}">
            <i class="mdi {{ Auth::user()->isAdmin() ? 'mdi-shield-account' : 'mdi-store' }}"></i>
            @if(Auth::user()->isAdmin())
                <strong>Mode Admin</strong> — Anda melihat data dari <strong>semua vendor</strong>.
            @else
                <strong>{{ $myVendor->nama_vendor ?? 'Vendor' }}</strong> — Anda hanya melihat data vendor Anda.
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Pesanan Pending</h6>
                <h2 class="mb-0">{{ number_format($pendingOrders) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Pesanan Lunas</h6>
                <h2 class="mb-0">{{ number_format($lunasPesanan) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Total Revenue</h6>
                <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Aksi Cepat</h4>
                <a href="{{ route('vendor.menu.index') }}" class="btn btn-primary mr-2">
                    <i class="mdi mdi-food"></i> Kelola Menu
                </a>
                <a href="{{ route('vendor.lunas-pesanan') }}" class="btn btn-success">
                    <i class="mdi mdi-check-circle"></i> Lihat Pesanan Lunas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
