<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;

class PosController extends Controller
{
    // Halaman POS
    public function index()
    {
        return view('pos.index');
    }

    // AJAX: Cari barang berdasarkan kode
    public function cariBarang($kode)
    {
        $barang = Barang::query()
            ->where('id_barang', $kode)
            ->first();

        if ($barang) {
            return response()->json([
                'success' => true,
                'data' => $barang
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ]);
        }
    }

    // AJAX: Simpan penjualan
    public function simpan(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            // Insert ke tabel penjualan
            $penjualan = Penjualan::create([
                'total' => $request->total
            ]);

            // Insert ke tabel penjualan_detail
            foreach ($request->items as $item) {
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'id_penjualan' => $penjualan->id_penjualan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
