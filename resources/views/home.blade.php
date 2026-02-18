@extends('layouts.app')

@section('title', 'Dashboard')
@section('icon', 'mdi-home')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Kategori <i class="mdi mdi-folder mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ \App\Models\Kategori::count() }}</h2>
                <h6 class="card-text">Kategori buku tersedia</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Buku <i class="mdi mdi-book-open-page-variant mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ \App\Models\Buku::count() }}</h2>
                <h6 class="card-text">Buku dalam koleksi</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Welcome <i class="mdi mdi-account mdi-24px float-right"></i></h4>
                <h2 class="mb-5">{{ Auth::user()->name }}</h2>
                <h6 class="card-text">Selamat datang kembali!</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
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
                            @forelse(\App\Models\Buku::with('kategori')->latest('idbuku')->take(5)->get() as $buku)
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
@endsection
