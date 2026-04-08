<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'id_payment';

    protected $fillable = ['id_pesanan', 'payment_method', 'payment_reference', 'amount', 'status', 'paid_at'];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}