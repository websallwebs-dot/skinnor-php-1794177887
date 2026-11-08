<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'nombre' => fake()->company(),
            'plan' => 'enterprise',
            'activa' => true,
        ];
    }
}
