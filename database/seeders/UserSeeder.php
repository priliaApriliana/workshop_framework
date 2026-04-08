<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN — bisa akses semua fitur
        User::updateOrCreate(
            ['email' => 'putri@mail.com'],
            [
                'name' => 'Putri',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]
        );

        // VENDOR 1 — Warung Nusantara
        User::updateOrCreate(
            ['email' => 'nusantara@mail.com'],
            [
                'name' => 'Warung Nusantara',
                'password' => Hash::make('123456'),
                'role' => 'vendor',
            ]
        );

        // VENDOR 2 — Kopi Senja
        User::updateOrCreate(
            ['email' => 'senja@mail.com'],
            [
                'name' => 'Kopi Senja',
                'password' => Hash::make('123456'),
                'role' => 'vendor',
            ]
        );

        // CUSTOMER
        User::updateOrCreate(
            ['email' => 'customer@mail.com'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('123456'),
                'role' => 'customer',
            ]
        );
    }
}