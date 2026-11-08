<?php

namespace Database\Factories;

use App\Models\Lesion;
use Illuminate\Database\Eloquent\Factories\Factory;

class LesionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'parte_del_cuerpo' => fake()->randomElement([
                'cara', 'cuello', 'pecho', 'espalda', 'abdomen', 'brazo', 'pierna', 'otro'
            ]),
            'descripcion' => fake()->sentence(),
            'fecha_inicio' => fake()->date(),
            'estado' => 'activa',
        ];
    }

    public function activa(): static
    {
        return $this->state(fn (array $attributes) => ['estado' => 'activa']);
    }

    public function en_seguimiento(): static
    {
        return $this->state(fn (array $attributes) => ['estado' => 'en_seguimiento']);
    }

    public function resuelta(): static
    {
        return $this->state(fn (array $attributes) => ['estado' => 'resuelta']);
    }
}
