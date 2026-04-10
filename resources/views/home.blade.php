@extends('layouts.app')

@section('title', 'Dashboard')
@section('icon', 'mdi-home')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

@php
    // BUKU DATA
    $totalKategori = \App\Models\Kategori::count();
    $totalBuku = \App\Models\Buku::count();
    $bukuTerbaru = \App\Models\Buku::with('kategori')->latest('idbuku')->take(5)->get();

    // KANTIN DATA
    $totalMenuKantin = \App\Models\Menu::count();
    $pesananLunas = \App\Models\Pesanan::where('status_bayar', 1)->count();
    $pesananTerbaru = \App\Models\Pesanan::latest('id_pesanan')->take(5)->get();
@endphp

<div class="row">
    <div class="col-12 mb-3">
        <h4 class="text-primary font-weight-bold">📚 Modul Koleksi Buku</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Kategori <i class="mdi mdi-folder-multiple mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ $totalKategori }}</h2>
                <h6 class="card-text">Kategori buku terdaftar</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Buku <i class="mdi mdi-book-open-variant mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ $totalBuku }}</h2>
                <h6 class="card-text">Buku dalam sistem</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-primary card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Welcome <i class="mdi mdi-account mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ Auth::user()->name }}</h2>
                <h6 class="card-text">Selamat datang kembali!</h6>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Buku Terbaru</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukuTerbaru as $buku)
                            <tr>
                                <td>{{ $buku->kode }}</td>
                                <td>{{ $buku->judul }}</td>
                                <td>{{ $buku->pengarang }}</td>
                                <td><label class="badge badge-info">{{ $buku->kategori->nama_kategori ?? '-' }}</label></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data buku</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 mb-3">
        <h4 class="text-success font-weight-bold">🍽️ Modul Sistem Kantin</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-6 stretch-card grid-margin">
        <div class="card bg-gradient-warning card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Menu Tersedia <i class="mdi mdi-silverware-variant mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ $totalMenuKantin }}</h2>
                <h6 class="card-text">Dari seluruh vendor</h6>
            </div>
        </div>
    </div>
    <div class="col-md-6 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Pesanan Lunas <i class="mdi mdi-check-decagram mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ $pesananLunas }}</h2>
                <h6 class="card-text">Transaksi terselesaikan</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pesanan Kantin Terbaru</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Tipe</th>
                                <th>Total Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesananTerbaru as $order)
                            <tr>
                                <td>#{{ $order->id_pesanan }}</td>
                                <td>{{ $order->nama_pemesan ?? 'Guest' }}</td>
                                <td>{{ $order->tipe_pesanan ?? 'Dine In' }}</td>
                                <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status_bayar == 1)
                                        <label class="badge badge-success">Lunas</label>
                                    @else
                                        <label class="badge badge-warning">Pending</label>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada transaksi kantin</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
