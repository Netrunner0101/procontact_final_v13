<?php

namespace Database\Factories;

use App\Models\Rappel;
use App\Models\RendezVous;
use Illuminate\Database\Eloquent\Factories\Factory;

class RappelFactory extends Factory
{
    protected $model = Rappel::class;

    public function definition(): array
    {
        return [
            'rendez_vous_id' => RendezVous::factory(),
            'date_rappel' => $this->faker->dateTimeBetween('now', '+1 month'),
            'frequence' => $this->faker->randomElement(['quotidien', 'hebdomadaire', 'mensuel']),
        ];
    }
}
