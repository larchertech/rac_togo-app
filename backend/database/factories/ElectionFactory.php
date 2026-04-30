<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ElectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['bla', 'bca', 'be']),
            'niveau' => '1',
            'statut' => 'preparation',
            'ouverture_candidatures' => now()->addDays(10),
            'cloture_candidatures' => now()->addDays(40),
            'date_vote' => now()->addDays(60),
            'heure_ouverture_vote' => '07:00',
            'heure_cloture_vote' => '18:00',
            'mode_scrutin' => 'majoritaire_simple',
            'postes' => ['president', 'vice_president', 'sg', 'tresorier'],
            'config' => ['exemption_cotisation' => false],
        ];
    }

    public function ouvert(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'vote',
            'date_vote' => now()->format('Y-m-d'),
            'heure_ouverture_vote' => now()->subHour()->format('H:i'),
            'heure_cloture_vote' => now()->addHour()->format('H:i'),
        ]);
    }
}
