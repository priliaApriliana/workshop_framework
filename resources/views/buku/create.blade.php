@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('icon', 'mdi-book-plus')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Buku</h4>
                <form action="{{ route('buku.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode" value="{{ old('kode') }}" placeholder="Contoh: NV-01" required>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul buku" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control @error('pengarang') is-invalid @enderror" id="pengarang" name="pengarang" value="{{ old('pengarang') }}" placeholder="Masukkan nama pengarang" required>
                        @error('pengarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select class="form-control @error('idkategori') is-invalid @enderror" id="idkategori" name="idkategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->idkategori }}" {{ old('idkategori') == $kat->idkategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('idkategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Simpan</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
