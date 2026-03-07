{{-- Vendor JS --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

{{-- Plugin JS --}}
<script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

{{-- DataTables JS (diperlukan untuk halaman yang pakai tabel) --}}
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

{{-- Main JS --}}
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>

{{-- Auto Dismiss Alert --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() { alert.remove(); }, 500);
            }, 3000);
        });
    });
</script>

{{-- SPINNER SUBMIT HANDLER --}}
<script>
    function submitWithSpinner(btn) {
        var form = btn.closest('form');
        if (!form) return;

        // Cek HTML5 validity
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Simpan teks asli button
        var originalHTML = btn.innerHTML;

        // Disable button & tampilkan spinner
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';

        // Submit form via JavaScript
        form.submit();
    }

    /**
     * Handle delete form dengan konfirmasi + spinner
     */
    function deleteWithSpinner(btn) {
        var form = btn.closest('form');
        if (!form) return;

        // Konfirmasi hapus
        if (!confirm('Yakin hapus data ini?')) {
            return;
        }

        // Disable button & tampilkan spinner
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

        // Submit form via JavaScript
        form.submit();
    }
</script>

