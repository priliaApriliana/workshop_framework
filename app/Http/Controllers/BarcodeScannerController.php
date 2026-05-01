<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarcodeScannerController extends Controller
{
    public function index()
    {
        return view('barcode_scanner');
    }

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