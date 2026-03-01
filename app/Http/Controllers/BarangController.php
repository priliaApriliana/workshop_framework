<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────────────────────
    public function index()
    {
        $barang = Barang::orderBy('timestamp', 'desc')->get();
        return view('barang.index', compact('barang'));
    }

    // ── CREATE ───────────────────────────────────────────────────────────────
    public function create()
    {
        return view('barang.create');
    }

    // ── STORE ────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|max:50',
            'harga' => 'required|integer|min:0',
        ]);

        Barang::create($request->only(['nama', 'harga']));

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan!');
    }

    // ── EDIT ─────────────────────────────────────────────────────────────────
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    // ── UPDATE ───────────────────────────────────────────────────────────────
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama'  => 'required|max:50',
            'harga' => 'required|integer|min:0',
        ]);

        $barang->update($request->only(['nama', 'harga']));

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diperbarui!');
    }

    // ── DESTROY ──────────────────────────────────────────────────────────────
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus!');
    }

    // ── PRINT LABEL PDF ──────────────────────────────────────────────────────
    public function printLabel(Request $request)
    {
        $request->validate([
            'ids'     => 'required|array|min:1',
            'ids.*'   => 'string',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:8',
        ]);

        $barang = Barang::whereIn('id_barang', $request->ids)->get();

        if ($barang->isEmpty()) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        $startX        = (int) $request->start_x;
        $startY        = (int) $request->start_y;
        $startPosition = (($startY - 1) * 5) + ($startX - 1);
        $sisaSlot      = 40 - $startPosition;

        if (count($barang) > $sisaSlot) {
            return response()->json([
                'message' => "Posisi mulai X={$startX}, Y={$startY} hanya tersisa {$sisaSlot} slot, tapi kamu memilih " . count($barang) . " barang."
            ], 422);
        }

        $pdf = Pdf::loadView('barang.print-label', compact('barang', 'startX', 'startY'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('label-harga.pdf');
    }
}