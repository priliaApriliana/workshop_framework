<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bukus = [
            [
                'kode' => 'NV-01',
                'judul' => 'Home Sweet Loan',
                'pengarang' => 'Almira Bastari',
                'idkategori' => 1, // Novel
            ],
            [
                'kode' => 'BO-01',
                'judul' => 'Muhammad Hatta, Untuk Negeriku',
                'pengarang' => 'Taufik Abdullah',
                'idkategori' => 2, // Biografi
            ],
            [
                'kode' => 'NV-02',
                'judul' => 'keajaiban Toko Kelontong Namiya',
                'pengarang' => 'keigo Higashino',
                'idkategori' => 1,
            ],
        ];

        foreach ($bukus as $buku) {
            Buku::create($buku);
        }
    }
}
