<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class VendorOrderController extends Controller
{
    /**
     * Helper: Ambil vendor milik user yang login
     * - Admin: return null (artinya lihat semua)
     * - Vendor: return Vendor miliknya
     */
    private function getMyVendor()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return null; // Admin lihat semua
        }
        return $user->vendor; // Vendor: hanya lihat miliknya sendiri
    }

    /**
     * Helper: Ambil id_vendor array (untuk filter pesanan)
     * Admin: semua vendor id
     * Vendor: hanya vendor id miliknya
     */
    private function getVendorIds()
    {
        $user = Auth::user();
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

        // Filter pesanan yang mengandung menu dari vendor ini
        $menuIds = Menu::whereIn('id_vendor', $vendorIds)->pluck('id_menu');
        $pesananIds = DetailPesanan::whereIn('id_menu', $menuIds)->pluck('id_pesanan')->unique();

        $pendingOrders = Pesanan::whereIn('id_pesanan', $pesananIds)->where('status_bayar', 0)->count();
        $lunasPesanan = Pesanan::whereIn('id_pesanan', $pesananIds)->where('status_bayar', 1)->count();
        $totalRevenue = Pesanan::whereIn('id_pesanan', $pesananIds)->where('status_bayar', 1)->sum('total');

        return view('vendor.dashboard', compact('pendingOrders', 'lunasPesanan', 'totalRevenue', 'myVendor'));
    }

    // ================================
    // SEMUA PESANAN (DAFTAR)
    // ================================
    public function semuaPesanan()
    {
        $vendorIds = $this->getVendorIds();
        $menuIds = Menu::whereIn('id_vendor', $vendorIds)->pluck('id_menu');
        $pesananIds = DetailPesanan::whereIn('id_menu', $menuIds)->pluck('id_pesanan')->unique();
        
        $pesanans = Pesanan::whereIn('id_pesanan', $pesananIds)
            ->with('detailPesanans.menu')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        $myVendor = $this->getMyVendor();

        return view('vendor.semua-pesanan', compact('pesanans', 'myVendor'));
    }

    // ================================
    // PESANAN LUNAS
    // ================================
    public function lunasPesanan()
    {
        $vendorIds = $this->getVendorIds();
        $menuIds = Menu::whereIn('id_vendor', $vendorIds)->pluck('id_menu');
        $pesananIds = DetailPesanan::whereIn('id_menu', $menuIds)->pluck('id_pesanan')->unique();

        $pesanans = Pesanan::whereIn('id_pesanan', $pesananIds)
            ->where('status_bayar', 1)
            ->with('detailPesanans.menu')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        $myVendor = $this->getMyVendor();

        return view('vendor.lunas-pesanan', compact('pesanans', 'myVendor'));
    }

    // Detail pesanan
    public function detailPesanan($id_pesanan)
    {
        $pesanan = Pesanan::with('detailPesanans.menu', 'payments')->findOrFail($id_pesanan);

        // Jika vendor, cek apakah pesanan ini mengandung menu miliknya
        $user = Auth::user();
        if ($user->isVendor()) {
            $vendor = $user->vendor;
            if ($vendor) {
                $menuIds = $vendor->menus()->pluck('id_menu');
                $hasAccess = $pesanan->detailPesanans->whereIn('id_menu', $menuIds)->count() > 0;
                if (!$hasAccess) {
                    abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
                }
            }
        }

        return view('vendor.detail-pesanan', compact('pesanan'));
    }

    // ================================
    // KELOLA MENU
    // ================================

    // List menu
    public function menuIndex()
    {
        $user = Auth::user();
        $myVendor = $this->getMyVendor();

        if ($user->isAdmin()) {
            // Admin: lihat semua menu + nama vendor
            $menus = Menu::with('vendor')->paginate(10);
        } else {
            // Vendor: hanya menu miliknya
            $vendor = $user->vendor;
            if (!$vendor) {
                return back()->with('error', 'Akun Anda belum terhubung ke vendor manapun.');
            }
            $menus = Menu::where('id_vendor', $vendor->id_vendor)->paginate(10);
        }

        return view('vendor.menu.index', compact('menus', 'myVendor'));
    }

    // Create Form
    public function menuCreate()
    {
        $user = Auth::user();
        $myVendor = $this->getMyVendor();

        if ($user->isAdmin()) {
            $vendors = Vendor::all(); // Admin bisa pilih vendor
        } else {
            $vendors = collect(); // Vendor tidak perlu pilih
        }

        return view('vendor.menu.create', compact('vendors', 'myVendor'));
    }

    // Store
    public function menuStore(Request $request)
    {
        $user = Auth::user();

        // Tentukan id_vendor
        if ($user->isAdmin()) {
            $request->validate([
                'id_vendor' => 'required|exists:vendor,id_vendor',
                'nama_menu' => 'required|string|max:100',
                'harga' => 'required|integer|min:1000',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $idVendor = $request->id_vendor;
        } else {
            $request->validate([
                'nama_menu' => 'required|string|max:100',
                'harga' => 'required|integer|min:1000',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $vendor = $user->vendor;
            if (!$vendor) {
                return back()->with('error', 'Akun Anda belum terhubung ke vendor.');
            }
            $idVendor = $vendor->id_vendor;
        }

        try {
            $data = [
                'id_vendor' => $idVendor,
                'nama_menu' => $request->nama_menu,
                'harga' => $request->harga,
            ];

            if ($request->hasFile('gambar')) {
                $filename = 'menu_' . time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('/uploads/menu'), $filename);
                $data['gambar'] = $filename;
            }

            Menu::create($data);
            return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan menu: ' . $e->getMessage());
        }
    }

    // Edit Form
    public function menuEdit($id_menu)
    {
        $menu = Menu::findOrFail($id_menu);
        $user = Auth::user();
        $myVendor = $this->getMyVendor();

        // Vendor hanya bisa edit menu miliknya
        if ($user->isVendor()) {
            $vendor = $user->vendor;
            if (!$vendor || $menu->id_vendor !== $vendor->id_vendor) {
                abort(403, 'Anda tidak memiliki akses ke menu ini.');
            }
            $vendors = collect();
        } else {
            $vendors = Vendor::all();
        }

        return view('vendor.menu.edit', compact('menu', 'vendors', 'myVendor'));
    }

    // Update
    public function menuUpdate(Request $request, $id_menu)
    {
        $user = Auth::user();
        $menu = Menu::findOrFail($id_menu);

        // Vendor hanya bisa update menu miliknya
        if ($user->isVendor()) {
            $vendor = $user->vendor;
            if (!$vendor || $menu->id_vendor !== $vendor->id_vendor) {
                abort(403, 'Anda tidak memiliki akses ke menu ini.');
            }
        }

        if ($user->isAdmin()) {
            $request->validate([
                'id_vendor' => 'required|exists:vendor,id_vendor',
                'nama_menu' => 'required|string|max:100',
                'harga' => 'required|integer|min:1000',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $idVendor = $request->id_vendor;
        } else {
            $request->validate([
                'nama_menu' => 'required|string|max:100',
                'harga' => 'required|integer|min:1000',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $idVendor = $menu->id_vendor; // tetap vendor lama
        }

        try {
            $data = [
                'id_vendor' => $idVendor,
                'nama_menu' => $request->nama_menu,
                'harga' => $request->harga,
            ];

            if ($request->hasFile('gambar')) {
                if ($menu->gambar && file_exists(public_path('/uploads/menu/' . $menu->gambar))) {
                    unlink(public_path('/uploads/menu/' . $menu->gambar));
                }
                $filename = 'menu_' . time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('/uploads/menu'), $filename);
                $data['gambar'] = $filename;
            }

            $menu->update($data);
            return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui menu: ' . $e->getMessage());
        }
    }

    // Delete
    public function menuDestroy($id_menu)
    {
        try {
            $menu = Menu::findOrFail($id_menu);
            $user = Auth::user();

            // Vendor hanya bisa hapus menu miliknya
            if ($user->isVendor()) {
                $vendor = $user->vendor;
                if (!$vendor || $menu->id_vendor !== $vendor->id_vendor) {
                    abort(403, 'Anda tidak memiliki akses ke menu ini.');
                }
            }

            if ($menu->gambar && file_exists(public_path('/uploads/menu/' . $menu->gambar))) {
                unlink(public_path('/uploads/menu/' . $menu->gambar));
            }

            $menu->delete();
            return back()->with('success', 'Menu berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus menu: ' . $e->getMessage());
        }
    }
}
