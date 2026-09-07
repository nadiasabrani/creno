<?php

namespace Tests\Feature;

use App\Models\Creneau;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendezVousStoreRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejette_un_creneau_id_inexistant(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/rendez-vous', ['creneau_id' => 9999])
            ->assertSessionHasErrors('creneau_id');

        $this->assertDatabaseCount('rendez_vous', 0);
    }

    public function test_rejette_un_creneau_deja_reserve(): void
    {
        $user = User::factory()->create();

        $creneau = Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        RendezVous::factory()->create([
            'creneau_id' => $creneau->id,
            'statut' => 'en_attente',
        ]);

        $this->actingAs($user)
            ->post('/rendez-vous', ['creneau_id' => $creneau->id])
            ->assertSessionHasErrors('creneau_id');

        // Only the original rdv should exist
        $this->assertDatabaseCount('rendez_vous', 1);
    }

    public function test_rejette_un_creneau_passe(): void
    {
        $user = User::factory()->create();

        $creneau = Creneau::factory()->create([
            'date' => now()->subDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        $this->actingAs($user)
            ->post('/rendez-vous', ['creneau_id' => $creneau->id])
            ->assertSessionHasErrors('creneau_id');

        $this->assertDatabaseCount('rendez_vous', 0);
    }
}
