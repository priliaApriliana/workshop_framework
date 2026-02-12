{{-- Plugin CSS --}}
<link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">

{{-- Main CSS Template Purple --}}
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

{{-- Custom CSS untuk Menu Active Highlight --}}
<style>
    /* Pastikan menu active ter-highlight */
    .sidebar .nav .nav-item.active {
        background: #e9e9e9;
        border-radius: 0;
    }
    
    .sidebar .nav .nav-item.active > .nav-link {
        background: transparent;
    }
    
    .sidebar .nav .nav-item.active > .nav-link .menu-title {
        color: #b66dff;
        font-weight: 600;
    }
    
    .sidebar .nav .nav-item.active > .nav-link .menu-icon {
        color: #b66dff;
    }
    
    /* Hover effect */
    .sidebar .nav .nav-item:not(.active):hover {
        background: #f8f8f8;
    }
</style>

{{-- Favicon --}}
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

@stack('styles')