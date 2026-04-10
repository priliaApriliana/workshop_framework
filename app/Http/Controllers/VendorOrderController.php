<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class VendorOrderController extends Controller
{
    /**
     * Ambil user login (helper biar rapi & aman)
     */
    private function getUser(): User
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }
        return $user;
    }

    /**
     * Helper: Ambil vendor milik user
     */
    private function getMyVendor()
    {
        $user = $this->getUser();

        if ($user->isAdmin()) {
            return null;
        }

        return $user->vendor;
    }

    /**
     * Helper: Ambil id_vendor array
     */
    private function getVendorIds(): array
    {
        $user = $this->getUser();

        if ($user->isAdmin()) {
            return Vendor::pluck('id_vendor')->toArray();
        }

        $vendor = $user->vendor;
        return $vendor ? [$vendor->id_vendor] : [];
    }

    // ================================
    // DASHBOARD
    // ================================
    public function dashboard()
    {
        $vendorIds = $this->getVendorIds();
        $myVendor = $this->getMyVendor();

        $menuIds = Menu::whereIn('id_vendor', $vendorIds)->pluck('id_menu');
        $pesananIds = DetailPesanan::whereIn('id_menu', $menuIds)
            ->pluck('id_pesanan')
            ->unique();

        $pendingOrders = Pesanan::whereIn('id_pesanan', $pesananIds)
            ->where('status_bayar', 0)
            ->count();

        $lunasPesanan = Pesanan::whereIn('id_pesanan', $pesananIds)
            ->where('status_bayar', 1)
            ->count();

        $totalRevenue = Pesanan::whereIn('id_pesanan', $pesananIds)
            ->where('status_bayar', 1)
            ->sum('total');

        return view('vendor.dashboard', compact(
            'pendingOrders',
            'lunasPesanan',
            'totalRevenue',
            'myVendor'
        ));
    }

    // ================================
    // SEMUA PESANAN
    // ================================
    public function semuaPesanan()
    {
        $vendorIds = $this->getVendorIds();

        $menuIds = Menu::whereIn('id_vendor', $vendorIds)->pluck('id_menu');
        $pesananIds = DetailPesanan::whereIn('id_menu', $menuIds)
            ->pluck('id_pesanan')
            ->unique();

        $pesanans = Pesanan::whereIn('id_pesanan', $pesananIds)
            ->with('detailPesanans.menu')
            ->latest()
            ->paginate(10);

        return view('vendor.semua-pesanan', [
            'pesanans' => $pesanans,
            'myVendor' => $this->getMyVendor()
        ]);
    }

    // ================================
    // PESANAN LUNAS
    // ================================
    public function lunasPesanan()
    {
        $vendorIds = $this->getVendorIds();

        $menuIds = Menu::whereIn('id_vendor', $vendorIds)->pluck('id_menu');
        $pesananIds = DetailPesanan::whereIn('id_menu', $menuIds)
            ->pluck('id_pesanan')
            ->unique();

        $pesanans = Pesanan::whereIn('id_pesanan', $pesananIds)
            ->where('status_bayar', 1)
            ->with('detailPesanans.menu')
            ->latest()
            ->paginate(10);

        return view('vendor.lunas-pesanan', [
            'pesanans' => $pesanans,
            'myVendor' => $this->getMyVendor()
        ]);
    }

    // ================================
    // DETAIL PESANAN
    // ================================
    public function detailPesanan($id_pesanan)
    {
        $pesanan = Pesanan::with('detailPesanans.menu', 'payments')
            ->findOrFail($id_pesanan);

        $user = $this->getUser();

        if ($user->isVendor()) {
            $vendor = $user->vendor;

            if ($vendor) {
                $menuIds = $vendor->menus()->pluck('id_menu');

                $hasAccess = $pesanan->detailPesanans
                    ->whereIn('id_menu', $menuIds)
                    ->count() > 0;

                if (!$hasAccess) {
                    abort(403, 'Tidak punya akses');
                }
            }
        }

        return view('vendor.detail-pesanan', compact('pesanan'));
    }

    // ================================
    // MENU INDEX
    // ================================
    public function menuIndex()
    {
        $user = $this->getUser();

        if ($user->isAdmin()) {
            $menus = Menu::with('vendor')->paginate(10);
        } else {
            $vendor = $user->vendor;

            if (!$vendor) {
                return back()->with('error', 'Belum terhubung vendor');
            }

            $menus = Menu::where('id_vendor', $vendor->id_vendor)->paginate(10);
        }

        return view('vendor.menu.index', [
            'menus' => $menus,
            'myVendor' => $this->getMyVendor()
        ]);
    }

    // ================================
    // STORE MENU
    // ================================
    public function menuStore(Request $request)
    {
        $user = $this->getUser();

        $rules = [
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|integer|min:1000',
            'gambar' => 'nullable|image|max:2048',
        ];

        if ($user->isAdmin()) {
            $rules['id_vendor'] = 'required|exists:vendor,id_vendor';
        }

        $request->validate($rules);

        $idVendor = $user->isAdmin()
            ? $request->id_vendor
            : optional($user->vendor)->id_vendor;

        if (!$idVendor) {
            return back()->with('error', 'Vendor tidak ditemukan');
        }

        $data = $request->only('nama_menu', 'harga');
        $data['id_vendor'] = $idVendor;

        if ($request->hasFile('gambar')) {
            $filename = 'menu_' . time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('/uploads/menu'), $filename);
            $data['gambar'] = $filename;
        }

        Menu::create($data);

        return redirect()->route('vendor.menu.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    // ================================
    // DELETE MENU
    // ================================
    public function menuDestroy($id_menu)
    {
        $menu = Menu::findOrFail($id_menu);
        $user = $this->getUser();

        if ($user->isVendor()) {
            if ($menu->id_vendor !== optional($user->vendor)->id_vendor) {
                abort(403);
            }
        }

        if ($menu->gambar && file_exists(public_path('/uploads/menu/' . $menu->gambar))) {
            unlink(public_path('/uploads/menu/' . $menu->gambar));
        }

        $menu->delete();

        return back()->with('success', 'Menu dihapus');
    }
}