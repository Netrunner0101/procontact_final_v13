<?php

namespace Database\Factories;

use App\Models\Activite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActiviteFactory extends Factory
{
    protected $model = Activite::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nom' => $this->faker->words(2, true),
            'description' => $this->faker->paragraph(),
            'duree_defaut' => $this->faker->numberBetween(30, 120),
            'prix' => $this->faker->randomFloat(2, 50, 500),
            'couleur' => $this->faker->hexColor(),
        ];
    }
}
