<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Putri',
            'email' => 'putri@mail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}