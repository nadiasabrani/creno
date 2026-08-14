<?php

namespace Database\Factories;

use App\Models\Creneau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Creneau>
 */
class CreneauFactory extends Factory
{
    protected $model = Creneau::class;

    public function definition(): array
    {
        return [
            'date' => fake()->date('Y-m-d', '+30 days'),
            'heure_debut' => fake()->time('H:i:s'),
            'duree_minutes' => fake()->randomElement([30, 60, 90, 120]),
        ];
    }
}
