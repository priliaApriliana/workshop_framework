@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open"></i>
        </span> Detail Buku
    </h3>
    <nav aria-label="breadcrumb">
        <a href="{{ route('buku.index') }}" class="btn btn-gradient-secondary btn-sm">
            <i class="mdi mdi-arrow-left"></i> Kembali
        </a>
    </nav>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Buku</h4>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ID Buku</th>
                        <td>: {{ $buku->idbuku }}</td>
                    </tr>
                    <tr>
                        <th>Kode</th>
                        <td>: {{ $buku->kode }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td>: {{ $buku->judul }}</td>
                    </tr>
                    <tr>
                        <th>Pengarang</th>
                        <td>: {{ $buku->pengarang }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: <span class="badge badge-info">{{ $buku->kategori->nama_kategori ?? '-' }}</span></td>
                    </tr>
                </table>
                <a href="{{ route('buku.edit', $buku->idbuku) }}" class="btn btn-gradient-warning">
                    <i class="mdi mdi-pencil"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection