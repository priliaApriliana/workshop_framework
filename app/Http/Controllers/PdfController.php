<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Halaman untuk sertifikat dengan tombol download
     */
    public function sertifikatForm()
    {
        return view('pdf.sertifikat-form');
    }

    /**
     * Generate PDF Sertifikat (Landscape A4) - Static
     */
    public function generateSertifikat()
    {
        $pdf = Pdf::loadView('pdf.sertifikat')
                  ->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat.pdf');
    }

    /**
     * Halaman untuk undangan dengan tombol download
     */
    public function undanganForm()
    {
        return view('pdf.undangan-form');
    }

    /**
     * Generate PDF Undangan (Portrait A4) - Static
     */
    public function generateUndangan()
    {
        $pdf = Pdf::loadView('pdf.undangan')
                  ->setPaper('a4', 'portrait');

        return $pdf->download('undangan.pdf');
    }
}