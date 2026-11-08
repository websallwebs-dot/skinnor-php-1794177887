<?php

namespace Database\Factories;

use App\Models\Consulta;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'mensaje' => fake()->sentence(),
            'respuesta' => null,
            'leida' => false,
            'respondida_at' => null,
        ];
    }

    public function respondida(): static
    {
        return $this->state(fn (array $attributes) => [
            'respuesta' => fake()->sentence(),
            'leida' => true,
            'respondida_at' => now(),
        ]);
    }

    public function no_respondida(): static
    {
        return $this->state(fn (array $attributes) => [
            'respuesta' => null,
            'leida' => false,
            'respondida_at' => null,
        ]);
    }
}
