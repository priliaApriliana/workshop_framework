<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan') - Purple Admin</title>
    
    <!-- Style Global -->
    @include('layouts.partials.style-global')
    
    <!-- Style Page (per halaman) -->
    @yield('styles')
</head>