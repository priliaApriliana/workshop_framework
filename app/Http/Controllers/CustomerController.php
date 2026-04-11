<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // Submenu 1: Data Customer (tabel)
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    // Submenu 2: Form Tambah Customer 1 (blob)
    public function createBlob()
    {
        return view('customer.tambah-blob');
    }

    // Submenu 2: Simpan Customer 1 (blob ke database - PostgreSQL safe)
    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'foto_base64' => 'required|string',
        ]);

        $base64   = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto_base64);
        $blobData = base64_decode($base64);

        // PostgreSQL bytea: format \x + hex
        $hexData = '\\x' . bin2hex($blobData);

        Customer::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'telepon'   => $request->telepon,
            'alamat'    => $request->alamat,
            'provinsi'  => $request->provinsi,
            'kota'      => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos'   => $request->kodepos,
            'foto_blob' => $hexData,
        ]);

        return redirect()->route('customer.index')
                         ->with('success', 'Customer berhasil ditambahkan (blob).');
    }

    // Submenu 3: Form Tambah Customer 2 (file path)
    public function createPath()
    {
        return view('customer.tambah-path');
    }

    // Submenu 3: Simpan Customer 2 (file ke storage)
    public function storePath(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'foto_base64' => 'required|string',
        ]);

        $base64    = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto_base64);
        $imageData = base64_decode($base64);

        $fileName = 'customer_' . time() . '.png';
        Storage::disk('public')->put('customers/' . $fileName, $imageData);

        Customer::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'telepon'   => $request->telepon,
            'alamat'    => $request->alamat,
            'provinsi'  => $request->provinsi,
            'kota'      => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos'   => $request->kodepos,
            'foto_path' => 'customers/' . $fileName,
        ]);

        return redirect()->route('customer.index')
                         ->with('success', 'Customer berhasil ditambahkan (file path).');
    }

    // Helper: tampilkan foto blob
    public function fotoBlob($id)
    {
        $customer = Customer::findOrFail($id);

        $binaryData = $customer->foto_blob; // sudah di-handle accessor di Model

        if (empty($binaryData)) {
            abort(404);
        }

        // Membersihkan output buffer (termasuk karakter BOM ghaib) agar gambar tidak korup
        if (ob_get_length()) {
            ob_clean();
        }

        return response($binaryData)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        // Hapus file foto jika ada
        if ($customer->foto_path) {
            Storage::disk('public')->delete($customer->foto_path);
        }

        $customer->delete();

        return redirect()->route('customer.index')
                        ->with('success', 'Customer berhasil dihapus.');
    }
}