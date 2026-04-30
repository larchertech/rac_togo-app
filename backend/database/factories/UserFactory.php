<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'phone_whatsapp' => '+2289' . fake()->unique()->numberBetween(1000000, 9999999),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'alumni',
            'statut' => 'actif',
            'canal_prefere' => 'whatsapp',
            'derniere_connexion' => now(),
        ];
    }

    public function alumni(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'alumni',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
