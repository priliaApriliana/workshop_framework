<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        {{-- Profile Section --}}
        @auth
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::user()->name ?? 'User' }}</span>
                    <span class="text-secondary text-small">Online</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        @endauth

        {{-- CUSTOMER (PUBLIC) --}}
        @if(!Auth::check())
        <li class="nav-item nav-category">
            <span class="nav-link">PUBLIC AREA</span>
        </li>
        <li class="nav-item {{ request()->is('customer/order*') && !request()->is('customer/order/*/payment') && !request()->is('customer/order/*/status') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('customer.order.index') }}">
                <span class="menu-title">Customer Order</span>
                <i class="mdi mdi-storefront menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('customer/order/*/payment') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('customer.order.index') }}">
                <span class="menu-title">Payment</span>
                <i class="mdi mdi-credit-card-outline menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('customer/order/*/status') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('customer.order.index') }}">
                <span class="menu-title">Status Pesanan</span>
                <i class="mdi mdi-check-circle-outline menu-icon"></i>
            </a>
        </li>
        @endif

        @auth
        {{-- MENU KHUSUS ADMIN --}}
        @if(Auth::user()->isAdmin())
        <li class="nav-item nav-category">
            <span class="nav-link">📚 Modul Koleksi Buku</span>
        </li>
        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('home') }}">
                <span class="menu-title">Admin Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-folder menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('barang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Barang</span>
                <i class="mdi mdi-package-variant menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('select-kota*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('select-kota.index') }}">
                <span class="menu-title">Select Kota</span>
                <i class="mdi mdi-city menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('wilayah*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('wilayah.index') }}">
                <span class="menu-title">Wilayah</span>
                <i class="mdi mdi-map-marker menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('pos*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pos.index') }}">
                <span class="menu-title">Point of Sales</span>
                <i class="mdi mdi-cart menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('pdf*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#pdf-menu" aria-expanded="{{ request()->is('pdf*') ? 'true' : 'false' }}" aria-controls="pdf-menu">
                <span class="menu-title">PDF Generator</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-file-pdf menu-icon"></i>
            </a>
            <div class="collapse {{ request()->is('pdf*') ? 'show' : '' }}" id="pdf-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pdf/sertifikat*') ? 'active' : '' }}" href="{{ route('pdf.sertifikat.form') }}">
                            Sertifikat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pdf/undangan*') ? 'active' : '' }}" href="{{ route('pdf.undangan.form') }}">
                            Undangan
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- TAMBAHAN BARU: Menu Customer (Modul 7) --}}
        <li class="nav-item {{ request()->is('data-customer*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#customer-menu"
               aria-expanded="{{ request()->is('data-customer*') ? 'true' : 'false' }}"
               aria-controls="customer-menu">
                <span class="menu-title">Customer</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-group menu-icon"></i>
            </a>
            <div class="collapse {{ request()->is('data-customer*') ? 'show' : '' }}" id="customer-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}"
                           href="{{ route('customer.index') }}">Data Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.createBlob') ? 'active' : '' }}"
                           href="{{ route('customer.createBlob') }}">Tambah Customer 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.createPath') ? 'active' : '' }}"
                           href="{{ route('customer.createPath') }}">Tambah Customer 2</a>
                    </li>
                </ul>
            </div>
        </li>
        {{-- END TAMBAHAN --}}

        @endif

        {{-- KHUSUS VENDOR --}}
        @if(Auth::user()->isVendor() || Auth::user()->isAdmin())
        <li class="nav-item nav-category">
            <span class="nav-link">🍽️ Modul Sistem Kantin</span>
        </li>
        <li class="nav-item {{ request()->is('vendor/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendor.dashboard') }}">
                <span class="menu-title">Vendor Dashboard</span>
                <i class="mdi mdi-view-dashboard text-success menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('vendor/menu*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendor.menu.index') }}">
                <span class="menu-title">Kelola Menu</span>
                <i class="mdi mdi-food text-warning menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('vendor/semua-pesanan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendor.semua-pesanan') }}">
                <span class="menu-title">Semua Pesanan</span>
                <i class="mdi mdi-cash-register text-info menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('vendor/lunas-pesanan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendor.lunas-pesanan') }}">
                <span class="menu-title">Pesanan Lunas</span>
                <i class="mdi mdi-check-decagram text-info menu-icon"></i>
            </a>
        </li>
        @endif
        @endauth
    </ul>
</nav>