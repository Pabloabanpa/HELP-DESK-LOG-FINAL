<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador por defecto
        User::create([
            'name' => 'admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('admin'),

        ]);

        // Otros usuarios aleatorios
        User::factory(10)->create();
    }
}

