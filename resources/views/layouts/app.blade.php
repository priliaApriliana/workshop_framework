<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts.partials.head')
    
    {{-- Style Global --}}
    @include('layouts.partials.style-global')
    
    {{-- Style Page (khusus halaman tertentu) --}}
    @yield('styles')
</head>

<body>
    <div class="container-scroller">
        
        {{-- Navbar --}}
        @include('layouts.partials.navbar')
        
        <div class="container-fluid page-body-wrapper">
            
            {{-- Sidebar --}}
            @include('layouts.partials.sidebar')
            
            <div class="main-panel">
                {{-- Content --}}
                <div class="content-wrapper">
                    @yield('content')
                </div>
                
                {{-- Footer --}}
                @include('layouts.partials.footer')
            </div>
        </div>
    </div>

    {{-- Javascript Global --}}
    @include('layouts.partials.js-global')
    
    {{-- Javascript Page (khusus halaman tertentu) --}}
    @yield('scripts')
</body>
</html>