@extends('layouts.app')

@section('title', 'Detail Buku')
@section('icon', 'mdi-book-open')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
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
                <a href="{{ route('buku.index') }}" class="btn btn-light">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
