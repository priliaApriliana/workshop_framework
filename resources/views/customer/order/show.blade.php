@extends('layouts.app')

@section('title', 'Pilih Menu')
@section('icon', 'mdi-food')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('customer.order.index') }}">Customer Order</a></li>
<li class="breadcrumb-item active">{{ $vendor->nama_vendor }}</li>
@endsection

@section('content')
<div class="row">
    {{-- KOLOM KIRI: Daftar Menu --}}
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Menu {{ $vendor->nama_vendor }}</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th width="120">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td>
                                        @if($menu->gambar)
                                            <img src="{{ asset('uploads/menu/' . $menu->gambar) }}" width="40" class="mr-2 rounded" alt="menu">
                                        @endif
                                        {{ $menu->nama_menu }}
                                    </td>
                                    <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <input type="number" class="form-control qty" min="0" value="0"
                                            data-id="{{ $menu->id_menu }}"
                                            data-name="{{ $menu->nama_menu }}"
                                            data-price="{{ $menu->harga }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Ringkasan Cart --}}
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h5><i class="mdi mdi-cart"></i> Ringkasan Pesanan</h5>
                <hr>
                <div id="cart-list" class="mb-3 text-muted">Belum ada item dipilih.</div>
                <hr>
                <h5>Total: <span id="grand-total" class="text-primary">Rp 0</span></h5>
                <button id="btn-order" class="btn btn-primary btn-block mt-3" disabled>
                    <i class="mdi mdi-send"></i> Buat Pesanan & Bayar
                </button>
                <a href="{{ route('customer.order.index') }}" class="btn btn-secondary btn-block mt-2">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Hidden form data --}}
<form id="order-form" style="display:none;">
    @csrf
    <input type="hidden" name="id_vendor" value="{{ $vendor->id_vendor }}">
    <input type="hidden" name="items" id="items-json">
    <input type="hidden" name="total" id="total-input">
</form>
<div id="customer-order-config" style="display:none"
     data-store-url="{{ route('customer.order.store') }}"
     data-payment-base-url="{{ url('/customer/order') }}"
     data-csrf-token="{{ csrf_token() }}"></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/customer/order-show.js') }}"></script>
@endpush