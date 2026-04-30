<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CotisationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'annee' => (int) date('Y'),
            'montant' => 5000,
            'statut' => fake()->randomElement(['paye', 'en_attente', 'en_retard']),
            'canal_paiement' => fake()->randomElement(['flooz', 'tmoney', 'cash']),
        ];
    }

    public function paye(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'paye',
            'paid_at' => now(),
        ]);
    }
}
