<?php

namespace Database\Factories;

use App\Models\Anotacion;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnotacionFactory extends Factory
{
    protected $model = Anotacion::class;

    public function definition(): array
    {
        return [
            'solicitud_id' => Solicitud::all()->random()->id,
            'tecnico_id' => User::all()->random()->id,
            'descripcion' => $this->faker->paragraph(),
            'material_usado' => $this->faker->words(3, true), // por ejemplo: "cable conector tornillo"
        ];
    }
}

