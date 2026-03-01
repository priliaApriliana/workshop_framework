<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Novel Laskar Pelangi', 'harga' => 85000],
            ['nama' => 'Novel Bumi Manusia', 'harga' => 95000],
            ['nama' => 'Komik Naruto Vol. 1', 'harga' => 45000],
            ['nama' => 'Komik One Piece Vol. 2', 'harga' => 50000],
            ['nama' => 'Buku Filsafat Dasar', 'harga' => 75000],
            ['nama' => 'Buku Pemrograman PHP', 'harga' => 120000],
            ['nama' => 'Ensiklopedia Anak', 'harga' => 150000],
            ['nama' => 'Buku Sejarah Indonesia', 'harga' => 90000],
            ['nama' => 'Novel Dilan 1990', 'harga' => 80000],
            ['nama' => 'Komik Detective Conan Vol. 5', 'harga' => 48000],
        ];

        foreach ($data as $item) {
            Barang::create($item);
        }
    }
}