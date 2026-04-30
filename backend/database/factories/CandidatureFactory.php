<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'poste' => fake()->randomElement(['president', 'vice_president', 'sg', 'tresorier', 'conseiller']),
            'statut' => 'soumis',
            'lettre_motivation' => fake()->paragraphs(3, true),
            'numero_dossier' => 'CDC-' . date('Y') . '-' . strtoupper(fake()->bothify('??????')),
        ];
    }
}
