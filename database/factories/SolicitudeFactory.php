<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Solicitud;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudFactory extends Factory
{
    protected $model = Solicitud::class;

    public function definition(): array
    {
        return [
            'solicitante' => User::all()->random()->id,
            'tecnico' => User::all()->random()->id,
            'descripcion' => $this->faker->paragraph(),
            'archivo' => 'solicitudes/' . $this->faker->uuid . '.' . $this->faker->randomElement(['pdf', 'jpg', 'png']),
            'estado' => $this->faker->randomElement(['pendiente', 'en proceso', 'completada', 'cancelada']),
        ];
    }
}


