{{-- Vendor JS --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

{{-- Plugin JS --}}
<script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

{{-- Main JS --}}
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>

{{-- Auto Dismiss Alert --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto dismiss alert setelah 3 detik (3000ms)
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                // Fade out effect
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                
                // Remove element setelah fade out
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 3000); // 3 detik
        });
    });
</script>

<!-- Javascript Page (per halaman) -->
@stack('scripts')