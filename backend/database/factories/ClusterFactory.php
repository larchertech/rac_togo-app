<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClusterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->city(),
            'region' => fake()->randomElement(['Maritime', 'Plateaux', 'Cent.-Plat.', 'Kara-Sav.']),
        ];
    }
}
