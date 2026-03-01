<!DOCTYPE html>
<html lang="en">
{{-- ========================================== --}}
{{-- HEADER (termasuk meta tags & style global) --}}
{{-- ========================================== --}}
@include('layouts.partials.header')

<body>
    <div class="container-scroller">
        {{-- ========================================== --}}
        {{-- NAVBAR (navigation bar atas) --}}
        {{-- ========================================== --}}
        @include('layouts.partials.navbar')

        <div class="container-fluid page-body-wrapper">
            {{-- ========================================== --}}
            {{-- SIDEBAR (menu samping) --}}
            {{-- ========================================== --}}
            @include('layouts.partials.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    {{-- ========================================== --}}
                    {{-- PAGE HEADER dengan BREADCRUMB --}}
                    {{-- ========================================== --}}
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi @yield('icon', 'mdi-home')"></i>
                            </span> @yield('title', 'Dashboard')
                        </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>

                    {{-- ========================================== --}}
                    {{-- CONTENT (isi halaman - berbeda tiap page) --}}
                    {{-- ========================================== --}}
                    @yield('content')
                </div>

                {{-- ========================================== --}}
                {{-- FOOTER --}}
                {{-- ========================================== --}}
                @include('layouts.partials.footer')
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- JAVASCRIPT GLOBAL --}}
    {{-- ========================================== --}}
    @include('layouts.partials.js-global')

    {{-- ========================================== --}}
    {{-- JAVASCRIPT PAGE (per halaman) --}}
    {{-- ========================================== --}}
    @stack('scripts')
</body>
</html>