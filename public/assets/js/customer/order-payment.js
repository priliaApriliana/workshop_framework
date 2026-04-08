(() => {
  const cfgEl = document.getElementById('payment-config');
  const btn = document.getElementById('btn-generate');
  if (!cfgEl || !btn) return;
  const cfg = cfgEl.dataset;

  btn.addEventListener('click', async () => {
    const method = document.getElementById('payment_method').value;
    if (!method) {
      Swal.fire('Info', 'Pilih metode pembayaran terlebih dahulu', 'info');
      return;
    }

    const formData = new FormData();
    formData.append('payment_method', method);

    const response = await fetch(cfg.processUrl, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': cfg.csrfToken },
      body: formData,
    });

    const data = await response.json();
    if (!response.ok || !data.success) {
      Swal.fire('Error', data.message || 'Gagal generate instruksi', 'error');
      return;
    }

    const instruction = document.getElementById('instruction');
    if (method === 'virtual_account') {
      instruction.innerHTML = '<strong>Nomor VA:</strong> ' + data.payment_reference + '<br><strong>Nominal:</strong> Rp ' + Number(data.amount).toLocaleString('id-ID');
    } else {
      instruction.innerHTML = '<strong>Kode QRIS:</strong> ' + data.payment_reference + '<br><strong>Nominal:</strong> Rp ' + Number(data.amount).toLocaleString('id-ID');
    }
    instruction.style.display = 'block';

    Swal.fire('Berhasil', 'Instruksi pembayaran dibuat', 'success');
  });
})();