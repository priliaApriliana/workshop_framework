(() => {
  const btnConfirm = document.getElementById('btn-confirm');
  const cfgEl = document.getElementById('status-config');
  if (!btnConfirm || !cfgEl) return;
  const cfg = cfgEl.dataset;

  btnConfirm.addEventListener('click', async () => {
    const response = await fetch(cfg.confirmUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': cfg.csrfToken,
        'Content-Type': 'application/json'
      }
    });

    const data = await response.json();
    if (!response.ok || !data.success) {
      Swal.fire('Error', data.message || 'Gagal konfirmasi pembayaran', 'error');
      return;
    }

    await Swal.fire('Sukses', data.message, 'success');
    location.reload();
  });
})();