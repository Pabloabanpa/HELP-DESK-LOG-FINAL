<?php

namespace Database\Factories;

use App\Models\Atencion;
use App\Models\Solicitud;
use Illuminate\Database\Eloquent\Factories\Factory;

class AtencionFactory extends Factory
{
    protected $model = Atencion::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeThisMonth();
        $end = (clone $start)->modify('+'.rand(1, 5).' hours');

        return [
            'solicitud_id' => Solicitud::all()->random()->id,
            'descripcion' => $this->faker->sentence(),
            'estado' => $this->faker->randomElement(['en proceso', 'finalizada']),
            'fecha_inicio' => $start,
            'fecha_fin' => $end,
        ];
    }
}
