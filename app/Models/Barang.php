<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // karena kolom timestamp dihandle trigger/DB

    protected $fillable = ['nama', 'harga'];

    public function getRouteKeyName()
    {
        return 'id_barang';
    }
}