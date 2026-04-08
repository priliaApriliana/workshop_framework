(() => {
  const qtyInputs = document.querySelectorAll('.qty');
  const cartList = document.getElementById('cart-list');
  const grandTotal = document.getElementById('grand-total');
  const btnOrder = document.getElementById('btn-order');
  const cfgEl = document.getElementById('customer-order-config');
  if (!cfgEl || !btnOrder) return;
  const cfg = cfgEl.dataset;

  function buildCart() {
    const items = [];
    let total = 0;

    qtyInputs.forEach((input) => {
      const qty = parseInt(input.value || '0', 10);
      if (qty > 0) {
        const harga = parseInt(input.dataset.price, 10);
        const subtotal = harga * qty;
        items.push({
          id_menu: parseInt(input.dataset.id, 10),
          nama: input.dataset.name,
          harga,
          jumlah: qty,
          subtotal,
        });
        total += subtotal;
      }
    });

    if (items.length === 0) {
      cartList.innerHTML = 'Belum ada item.';
      btnOrder.disabled = true;
    } else {
      cartList.innerHTML = items.map((i) => `<div>${i.nama} (${i.jumlah}x) - Rp ${i.subtotal.toLocaleString('id-ID')}</div>`).join('');
      btnOrder.disabled = false;
    }

    grandTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('items-json').value = JSON.stringify(items);
    document.getElementById('total-input').value = total;
  }

  qtyInputs.forEach((input) => input.addEventListener('input', buildCart));

  btnOrder.addEventListener('click', async () => {
    try {
      const form = document.getElementById('order-form');
      const response = await fetch(cfg.storeUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': cfg.csrfToken },
        body: new FormData(form),
      });

      const data = await response.json();
      if (!response.ok || !data.success) {
        Swal.fire('Gagal', data.message || 'Gagal membuat pesanan', 'error');
        return;
      }

      await Swal.fire('Berhasil', 'Pesanan ' + data.nama_customer + ' dibuat', 'success');
      window.location.href = cfg.paymentBaseUrl + '/' + data.id_pesanan + '/payment';
    } catch {
      Swal.fire('Error', 'Terjadi kesalahan saat memproses pesanan', 'error');
    }
  });
})();