<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AlumniFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'date_naissance' => fake()->date('Y-m-d', '-18 years'),
            'niveau_diplome' => fake()->randomElement(['cepe', 'bepc', 'bac', 'bts', 'licence', 'master', 'formation_pro']),
            'statut_compte' => 'en_attente',
            'documents' => null,
        ];
    }
}
