<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'id_vendor';

    protected $fillable = ['nama_vendor', 'id_user'];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_vendor', 'id_vendor');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
