<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = ['nama_customer', 'total', 'metode_bayar', 'status_bayar'];

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_pesanan', 'id_pesanan');
    }
}
