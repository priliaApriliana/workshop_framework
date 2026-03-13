<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class WilayahController extends Controller
{
    // Halaman utama
    public function index()
    {
        return view('wilayah.index');
    }

    // AJAX: Get Provinsi
    public function getProvinsi()
    {
        $provinsi = Provinsi::query()->orderBy('nama')->get();
        return response()->json($provinsi);
    }

    // AJAX: Get Kota berdasarkan Provinsi
    public function getKota($provinsi_id)
    {
        $kota = Kota::query()
            ->where('provinsi_id', $provinsi_id)
            ->orderBy('nama')
            ->get();

        return response()->json($kota);
    }

    // AJAX: Get Kecamatan berdasarkan Kota
    public function getKecamatan($kota_id)
    {
        $kecamatan = Kecamatan::query()
            ->where('kota_id', $kota_id)
            ->orderBy('nama')
            ->get();

        return response()->json($kecamatan);
    }

    // AJAX: Get Kelurahan berdasarkan Kecamatan
    public function getKelurahan($kecamatan_id)
    {
        $kelurahan = Kelurahan::query()
            ->where('kecamatan_id', $kecamatan_id)
            ->orderBy('nama')
            ->get();

        return response()->json($kelurahan);
    }
}
