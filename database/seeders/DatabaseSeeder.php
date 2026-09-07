<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Regular user
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);

        // Sample available slots for upcoming days
        $dates = [
            now()->addDays(1)->format('Y-m-d'),
            now()->addDays(2)->format('Y-m-d'),
            now()->addDays(3)->format('Y-m-d'),
        ];

        $heures = ['09:00:00', '10:00:00', '11:30:00', '14:00:00', '15:30:00', '17:00:00'];

        foreach ($dates as $date) {
            foreach ($heures as $heure) {
                \App\Models\Creneau::firstOrCreate([
                    'date' => $date,
                    'heure_debut' => $heure,
                ], [
                    'duree_minutes' => 30,
                ]);
            }
        }
    }
}
