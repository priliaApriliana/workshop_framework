@extends('layouts.app')

@section('title', 'Edit Menu Vendor')
@section('icon', 'mdi-pencil')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('vendor.menu.index') }}">Kelola Menu</a></li>
<li class="breadcrumb-item active">Edit Menu</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Menu
                    @if($myVendor)
                        <small class="text-muted">— {{ $myVendor->nama_vendor }}</small>
                    @endif
                </h4>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('vendor.menu.update', $menu->id_menu) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Admin: bisa pilih vendor. Vendor: auto-assign --}}
                    @if(Auth::user()->isAdmin())
                        <div class="form-group">
                            <label>Vendor</label>
                            <select name="id_vendor" class="form-control" required>
                                <option value="">-- Pilih Vendor --</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id_vendor }}" {{ old('id_vendor', $menu->id_vendor) == $vendor->id_vendor ? 'selected' : '' }}>
                                        {{ $vendor->nama_vendor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" value="{{ old('harga', $menu->harga) }}" required min="1000">
                    </div>

                    <div class="form-group">
                        <label>Gambar Saat Ini</label><br>
                        @if($menu->gambar)
                            <img src="{{ asset('uploads/menu/' . $menu->gambar) }}" width="100" alt="menu">
                        @else
                            <span>-</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Ganti Gambar (opsional)</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>

                    <button class="btn btn-primary" type="submit">Update</button>
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
