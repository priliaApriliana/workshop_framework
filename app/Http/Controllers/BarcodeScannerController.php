<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarcodeScannerController extends Controller
{
    // PRAKTIKUM 1: Tampilkan halaman barcode scanner
    // GET /barcode-scanner
    public function index()
    {
        return view('barcode_scanner');
    }

    /**
     * API: Cari barang berdasarkan ID (dari barcode yang terbaca)
     * POST /api/barcode/search
     * 
     * Request JSON:
     * {
     *   "id_barang": "1001"
     * }
     * 
     * Response:
     * {
     *   "success": true,
     *   "data": {
     *     "id_barang": "1001",
     *     "nama": "Barang A",
     *     "harga": 50000
     *   }
     * }
     */

    public function search(Request $request)
    {
        $idBarang = $request->input('id_barang');
        if (!$idBarang) {
            return response()->json(['success'=>false,'message'=>'Data tidak ditemukan'],400);
        }
        $barang = Barang::find($idBarang);
        if (!$barang) {
            return response()->json(['success'=>false,'message'=>'Data tidak ditemukan'],404);
        }
        return response()->json(['success'=>true,'data'=>['id_barang'=>$barang->id_barang,'nama'=>$barang->nama,'harga'=>$barang->harga]]);
    }
}