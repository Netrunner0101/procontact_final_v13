<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    protected $model = Status::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->randomElement(['Prospect', 'Client actif', 'Client inactif', 'Lead qualifié']),
            'description' => $this->faker->sentence(),
            'couleur' => $this->faker->hexColor(),
        ];
    }
}
