<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        {{-- Profile Section --}}
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

        {{-- Dashboard --}}
        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- Menu Kategori --}}
        <li class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-folder menu-icon"></i>
            </a>
        </li>

        {{-- Menu Buku --}}
        <li class="nav-item {{ request()->is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>

        {{-- Menu PDF Generator --}}
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
                            <i class="mdi mdi-certificate text-warning me-2"></i> Sertifikat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pdf/undangan*') ? 'active' : '' }}" href="{{ route('pdf.undangan.form') }}">
                            <i class="mdi mdi-email-outline text-info me-2"></i> Undangan
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>