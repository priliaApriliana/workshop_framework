@extends('layouts.app')

@section('title', 'Daftar Kategori')
@section('icon', 'mdi-folder')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Kategori</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-sm">
            <i class="mdi mdi-plus"></i> Tambah Kategori
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tabel Kategori</h4>
                <p class="card-description">Total: <code>{{ count($kategori) }}</code> data</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Kategori</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $index => $kat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kat->idkategori }}</td>
                            <td>{{ $kat->nama_kategori }}</td>
                            <td>
                                <a href="{{ route('kategori.edit', $kat->idkategori) }}" class="btn btn-gradient-warning btn-sm">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <form action="{{ route('kategori.destroy', $kat->idkategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-gradient-danger btn-sm">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
