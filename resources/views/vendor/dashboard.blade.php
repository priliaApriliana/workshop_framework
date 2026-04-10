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
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Pendapatan <i class="mdi mdi-cash-multiple mdi-24px float-right"></i></h4>
                <h2 class="mb-5">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                <h6 class="card-text">Dari pesanan lunas</h6>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Pesanan Lunas <i class="mdi mdi-check-decagram mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ number_format($lunasPesanan) }}</h2>
                <h6 class="card-text">Tuntas dibayar</h6>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-warning card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Pesanan Pending <i class="mdi mdi-timer-sand mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ number_format($pendingOrders) }}</h2>
                <h6 class="card-text">Menunggu pembayaran</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Aksi Cepat Vendor</h4>
                <div class="d-flex flex-wrap">
                    <a href="{{ route('vendor.menu.create') }}" class="btn btn-outline-success btn-icon-text mt-2 mr-2">
                        <i class="mdi mdi-plus-box btn-icon-prepend"></i> Tambah Menu Baru
                    </a>
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-outline-primary btn-icon-text mt-2 mr-2">
                        <i class="mdi mdi-food-fork-drink btn-icon-prepend"></i> Lihat List Menu
                    </a>
                    <a href="{{ route('vendor.lunas-pesanan') }}" class="btn btn-gradient-success btn-icon-text mt-2">
                        <i class="mdi mdi-receipt btn-icon-prepend"></i> Cek Order Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
