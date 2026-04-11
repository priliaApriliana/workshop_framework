@extends('layouts.app')

@section('title', 'Data Customer')
@section('icon', 'mdi-account-multiple')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Customer</li>
@endsection

@section('content')

@php use Illuminate\Support\Facades\Storage; @endphp

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('customer.createPath') }}" class="btn btn-gradient-primary btn-sm">
            <i class="mdi mdi-plus"></i> Tambah Customer (Path)
        </a>
        <a href="{{ route('customer.createBlob') }}" class="btn btn-gradient-info btn-sm">
            <i class="mdi mdi-plus"></i> Tambah Customer (Blob)
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tabel Customer</h4>
                <p class="card-description">Total: <code>{{ count($customers) }}</code> customer</p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Provinsi</th>
                                <th>Kota</th>
                                <th>Kecamatan</th>
                                <th>Kodepos</th>
                                <th>Tipe Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $index => $c)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($c->foto_path)
                                        <img src="{{ Storage::url($c->foto_path) }}"
                                             width="50" height="50"
                                             style="object-fit:cover; border-radius:50%;">
                                    @elseif($c->foto_blob)
                                        <img src="{{ route('customer.fotoBlob', $c->id) }}?v={{ time() }}"
                                             width="50" height="50"
                                             style="object-fit:cover; border-radius:50%;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $c->nama }}</td>
                                <td>{{ $c->alamat ?? '-' }}</td>
                                <td>{{ $c->provinsi ?? '-' }}</td>
                                <td>{{ $c->kota ?? '-' }}</td>
                                <td>{{ $c->kecamatan ?? '-' }}</td>
                                <td>{{ $c->kodepos ?? '-' }}</td>
                                <td>
                                    @if($c->foto_path)
                                        <span class="badge bg-success">Path</span>
                                    @elseif($c->foto_blob)
                                        <span class="badge bg-primary">Blob</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('customer.destroy', $c->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus customer {{ $c->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">Tidak ada data customer.</td>
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