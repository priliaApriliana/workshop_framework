import re

filepath = r'c:\laragon\www\workshop_framework\resources\views\pos\index.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. AJAX renderTableAjax - change Qty td to input
old_ajax_qty = '<td>$' + '{item.jumlah}</td>'
new_ajax_qty = '<td><input type="number" class="form-control form-control-sm" value="$' + '{item.jumlah}" min="1" style="width:65px" onchange="updateJumlahAjax($' + '{index}, this.value)"></td>'
content = content.replace(old_ajax_qty, new_ajax_qty, 1)

# 2. Axios renderTableAxios - change Qty td to input  
# (same pattern but second occurrence)
content = content.replace(old_ajax_qty, new_ajax_qty.replace('updateJumlahAjax', 'updateJumlahAxios'), 1)

# 3. Add updateJumlahAjax function after hapusItemAjax
insert_after_ajax = '''// Hapus item dari keranjang (AJAX)
function hapusItemAjax(index) {
    cartAjax.splice(index, 1);
    renderTableAjax();
}'''

insert_ajax_new = insert_after_ajax + '''

// Update jumlah item di keranjang (AJAX)
function updateJumlahAjax(index, newQty) {
    newQty = parseInt(newQty);
    if (isNaN(newQty) || newQty <= 0) {
        hapusItemAjax(index);
        return;
    }
    cartAjax[index].jumlah = newQty;
    cartAjax[index].subtotal = cartAjax[index].harga * newQty;
    renderTableAjax();
}'''
content = content.replace(insert_after_ajax, insert_ajax_new, 1)

# 4. Add updateJumlahAxios function after hapusItemAxios
insert_after_axios = '''// Hapus item dari keranjang (Axios)
function hapusItemAxios(index) {
    cartAxios.splice(index, 1);
    renderTableAxios();
}'''

insert_axios_new = insert_after_axios + '''

// Update jumlah item di keranjang (Axios)
function updateJumlahAxios(index, newQty) {
    newQty = parseInt(newQty);
    if (isNaN(newQty) || newQty <= 0) {
        hapusItemAxios(index);
        return;
    }
    cartAxios[index].jumlah = newQty;
    cartAxios[index].subtotal = cartAxios[index].harga * newQty;
    renderTableAxios();
}'''
content = content.replace(insert_after_axios, insert_axios_new, 1)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print('Step 1 done! Inline edit jumlah berhasil ditambahkan.')
print('Changes:')
print('- renderTableAjax: Qty td -> input number + onchange')
print('- renderTableAxios: Qty td -> input number + onchange')  
print('- Added updateJumlahAjax() function')
print('- Added updateJumlahAxios() function')
