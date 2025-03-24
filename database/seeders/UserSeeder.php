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
            'name' => 'Pablo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'cargo' => 'Administrador',
            'oficina' => 'Sistemas',
            'ci' => '12345678',
            'celular' => '77777777',
            'fecha_nacimiento' => '1990-01-01',
        ]);

        // Otros usuarios aleatorios
        User::factory(10)->create();
    }
}

