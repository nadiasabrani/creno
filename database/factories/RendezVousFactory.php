<?php

namespace Database\Factories;

use App\Models\Creneau;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RendezVous>
 */
class RendezVousFactory extends Factory
{
    protected $model = RendezVous::class;

    public function definition(): array
    {
        return [
            'creneau_id' => Creneau::factory(),
            'user_id' => User::factory(),
        ];
    }
}
