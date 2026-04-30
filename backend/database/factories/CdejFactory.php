<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CdejFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => 'CDEJ ' . fake()->city(),
            'ville' => fake()->city(),
            'region' => fake()->randomElement(['Maritime', 'Plateaux', 'Cent.-Plat.', 'Kara-Sav.']),
        ];
    }
}
