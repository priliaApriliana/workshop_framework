<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class VendorMenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menu')->delete();
        DB::table('vendor')->delete();

        // Ambil user id vendor untuk relasi
        $nusantaraUserId = User::where('email', 'nusantara@mail.com')->first()->id;
        $senjaUserId = User::where('email', 'senja@mail.com')->first()->id;

        // Vendor 1: Warung Nusantara → milik akun nusantara@mail.com
        // Vendor 2: Kopi Senja → milik akun senja@mail.com
        DB::table('vendor')->insert([
            ['id_vendor' => 1, 'nama_vendor' => 'Warung Nusantara', 'id_user' => $nusantaraUserId, 'created_at' => now(), 'updated_at' => now()],
            ['id_vendor' => 2, 'nama_vendor' => 'Kopi Senja', 'id_user' => $senjaUserId, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Menu untuk Warung Nusantara
        DB::table('menu')->insert([
            ['id_vendor' => 1, 'nama_menu' => 'Nasi Goreng', 'harga' => 25000, 'gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id_vendor' => 1, 'nama_menu' => 'Mie Ayam', 'harga' => 20000, 'gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id_vendor' => 1, 'nama_menu' => 'Ayam Geprek', 'harga' => 28000, 'gambar' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Menu untuk Kopi Senja
        DB::table('menu')->insert([
            ['id_vendor' => 2, 'nama_menu' => 'Kopi Hitam', 'harga' => 15000, 'gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id_vendor' => 2, 'nama_menu' => 'Kopi Susu', 'harga' => 18000, 'gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id_vendor' => 2, 'nama_menu' => 'Croissant', 'harga' => 22000, 'gambar' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}