@extends('layouts.app')

@section('title', 'Daftar Buku')
@section('icon', 'mdi-book-open-page-variant')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Buku</li>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-sm">
            <i class="mdi mdi-plus"></i> Tambah Buku
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
                <h4 class="card-title">Tabel Buku</h4>
                <p class="card-description">Total: <code>{{ count($buku) }}</code> buku</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buku as $index => $b)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $b->kode }}</td>
                            <td>{{ $b->judul }}</td>
                            <td>{{ $b->pengarang }}</td>
                            <td>
                                <label class="badge badge-info">{{ $b->kategori->nama_kategori ?? '-' }}</label>
                            </td>
                            <td>
                                <a href="{{ route('buku.show', $b->idbuku) }}" class="btn btn-gradient-info btn-sm">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="{{ route('buku.edit', $b->idbuku) }}" class="btn btn-gradient-warning btn-sm">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <form action="{{ route('buku.destroy', $b->idbuku) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
