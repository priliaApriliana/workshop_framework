@extends('layouts.app')

@section('title', 'Kelola Menu Vendor')
@section('icon', 'mdi-food')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Vendor Dashboard</a></li>
<li class="breadcrumb-item active">Kelola Menu</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Data Menu</h4>
                    <a href="{{ route('vendor.menu.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Menu
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Vendor</th>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $index => $menu)
                                <tr>
                                    <td>{{ $menus->firstItem() + $index }}</td>
                                    <td>{{ $menu->vendor->nama_vendor ?? '-' }}</td>
                                    <td>{{ $menu->nama_menu }}</td>
                                    <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($menu->gambar)
                                            <img src="{{ asset('uploads/menu/' . $menu->gambar) }}" width="60" alt="menu">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('vendor.menu.edit', $menu->id_menu) }}" class="btn btn-sm btn-warning">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('vendor.menu.destroy', $menu->id_menu) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteWithSpinner(this)"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">Belum ada menu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $menus->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
