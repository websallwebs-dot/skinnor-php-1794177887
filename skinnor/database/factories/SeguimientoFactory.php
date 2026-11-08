<?php

namespace Database\Factories;

use App\Models\Seguimiento;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeguimientoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fecha' => fake()->date(),
            'hora' => fake()->time(),
            'nota' => fake()->sentence(),
            'subido_por' => 'paciente',
        ];
    }

    public function subido_por_profesional(): static
    {
        return $this->state(fn (array $attributes) => ['subido_por' => 'profesional']);
    }

    public function subido_por_paciente(): static
    {
        return $this->state(fn (array $attributes) => ['subido_por' => 'paciente']);
    }
}
