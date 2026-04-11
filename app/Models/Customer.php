<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
    'nama', 'email', 'telepon',
    'alamat', 'provinsi', 'kota', 'kecamatan', 'kodepos',
    'foto_blob', 'foto_path'
];

    // Tambahkan ini — biar foto_blob tidak di-cast otomatis
    protected $casts = [];

    // Accessor: otomatis konversi resource → binary saat diakses
    public function getFotoBlobAttribute($value)
    {
        if (is_resource($value)) {
            $content = stream_get_contents($value);
            // Simpan kembali ke attributes agar tidak habis (stream EOF) saat dipanggil kedua kalinya
            $this->attributes['foto_blob'] = $content;
            return $content;
        }
        return $value;
    }
}