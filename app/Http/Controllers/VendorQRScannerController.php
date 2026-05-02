<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorQRScannerController extends Controller
{
    public function index()
    {
        return view('vendor_qr_scanner');
    }

    public function scanQR(Request $request)
    {
        $idPesanan = $request->input('id_pesanan');

        if (!$idPesanan) {
            return response()->json([
                'success' => false,
                'message' => 'ID Pesanan tidak ditemukan',
            ], 400);
        }

        $pesanan = Pesanan::find($idPesanan);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        $detailPesanans = $pesanan->detailPesanans()->get();
        $userVendor = Auth::user()?->vendor;

        if (!$userVendor) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan vendor',
            ], 403);
        }

        $menuList = [];

        foreach ($detailPesanans as $detail) {
            $menu = Menu::find($detail->id_menu);

            if ($menu && $menu->id_vendor == $userVendor->id_vendor) {
                $menuList[] = [
                    'id_menu' => $menu->id_menu,
                    'nama_menu' => $menu->nama_menu,
                    'jumlah' => $detail->jumlah,
                    'harga' => $menu->harga,
                    'subtotal' => $detail->subtotal,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'nama_customer' => $pesanan->nama_customer,
                'status_bayar' => ($pesanan->status_bayar ? 'paid' : 'unpaid'),
                'total' => $pesanan->total,
                'menus' => $menuList,
            ],
        ]);
    }
}
