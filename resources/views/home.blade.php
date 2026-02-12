@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Selamat Datang, {{ Auth::user()->name ?? 'Guest' }}!</h3>
                <h6 class="font-weight-normal mb-0">Semua sistem berjalan dengan baik! <span class="text-primary">Kelola koleksi buku Anda.</span></h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Card Total Kategori --}}
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Kategori
                    <i class="mdi mdi-folder mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ \App\Models\Kategori::count() }}</h2>
                <h6 class="card-text">
                    <a href="{{ route('kategori.index') }}" class="text-white">Lihat Semua →</a>
                </h6>
            </div>
        </div>
    </div>

    {{-- Card Total Buku --}}
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">Total Buku
                    <i class="mdi mdi-book-open-page-variant mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ \App\Models\Buku::count() }}</h2>
                <h6 class="card-text">
                    <a href="{{ route('buku.index') }}" class="text-white">Lihat Semua →</a>
                </h6>
            </div>
        </div>
    </div>

    {{-- Card User --}}
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                <h4 class="font-weight-normal mb-3">User Aktif
                    <i class="mdi mdi-account-circle mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ Auth::user()->name ?? '-' }}</h2>
                <h6 class="card-text">{{ Auth::user()->email ?? '-' }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Data Terbaru --}}
<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Kategori Terbaru</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Kategori::latest('idkategori')->take(5)->get() as $kat)
                        <tr>
                            <td>{{ $kat->idkategori }}</td>
                            <td>{{ $kat->nama_kategori }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Buku Terbaru</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Buku::with('kategori')->latest('idbuku')->take(5)->get() as $buku)
                        <tr>
                            <td>{{ $buku->kode }}</td>
                            <td>{{ Str::limit($buku->judul, 20) }}</td>
                            <td>
                                <label class="badge badge-info">{{ $buku->kategori->nama_kategori ?? '-' }}</label>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection