<?php

namespace Tests\Feature;

use App\Models\Creneau;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendezVousControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_peut_reserver_un_creneau_disponible(): void
    {
        $user = User::factory()->create();

        $creneau = Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        $this->actingAs($user)
            ->post('/rendez-vous', ['creneau_id' => $creneau->id])
            ->assertRedirect(route('rendez-vous.mine'));

        $this->assertDatabaseHas('rendez_vous', [
            'creneau_id' => $creneau->id,
            'user_id' => $user->id,
            'statut' => 'en_attente',
        ]);
    }

    public function test_client_ne_peut_pas_reserver_un_creneau_deja_reserve(): void
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

        $this->assertDatabaseCount('rendez_vous', 1);
    }

    public function test_client_ne_peut_pas_reserver_un_creneau_passe(): void
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

    public function test_client_ne_peut_pas_reserver_deux_creneaux_qui_se_chevauchent(): void
    {
        $user = User::factory()->create();

        $creneau1 = Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        $creneau2 = Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:30:00',
            'duree_minutes' => 60,
        ]);

        // Premier rendez-vous réussi
        RendezVous::factory()->create([
            'creneau_id' => $creneau1->id,
            'user_id' => $user->id,
            'statut' => 'en_attente',
        ]);

        // Deuxième rendez-vous chevauchant refusé
        $this->actingAs($user)
            ->post('/rendez-vous', ['creneau_id' => $creneau2->id])
            ->assertSessionHasErrors('creneau_id');

        $this->assertDatabaseCount('rendez_vous', 1);
    }

    public function test_client_peut_annuler_son_propre_rendez_vous(): void
    {
        $user = User::factory()->create();

        $rdv = RendezVous::factory()->create([
            'user_id' => $user->id,
            'creneau_id' => Creneau::factory()->create([
                'date' => now()->addDay()->toDateString(),
                'heure_debut' => '10:00:00',
                'duree_minutes' => 60,
            ])->id,
            'statut' => 'en_attente',
        ]);

        $this->actingAs($user)
            ->delete("/rendez-vous/{$rdv->id}")
            ->assertRedirect(route('rendez-vous.mine'));

        $this->assertDatabaseHas('rendez_vous', [
            'id' => $rdv->id,
            'statut' => 'annule',
        ]);
    }

    public function test_client_ne_peut_pas_annuler_le_rendez_vous_d_un_autre_client(): void
    {
        $user = User::factory()->create();
        $autreUser = User::factory()->create();

        $rdv = RendezVous::factory()->create([
            'user_id' => $autreUser->id,
            'creneau_id' => Creneau::factory()->create([
                'date' => now()->addDay()->toDateString(),
                'heure_debut' => '10:00:00',
                'duree_minutes' => 60,
            ])->id,
            'statut' => 'en_attente',
        ]);

        $this->actingAs($user)
            ->delete("/rendez-vous/{$rdv->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('rendez_vous', [
            'id' => $rdv->id,
            'statut' => 'en_attente',
        ]);
    }

    public function test_invite_est_redirige_vers_login(): void
    {
        $this->get('/creneaux')
            ->assertRedirect('/login');

        $this->post('/rendez-vous', ['creneau_id' => 1])
            ->assertRedirect('/login');

        $this->get('/mes-rendez-vous')
            ->assertRedirect('/login');

        $this->delete('/rendez-vous/1')
            ->assertRedirect('/login');
    }

    public function test_creneau_a_la_frontiere_fin_exacte_maintenant(): void
    {
        $user = User::factory()->create();

        // Créneau dont la fin est exactement maintenant → considéré comme passé
        $creneau = Creneau::factory()->create([
            'date' => now()->toDateString(),
            'heure_debut' => now()->subMinutes(30)->format('H:i:s'),
            'duree_minutes' => 30,
        ]);

        $this->actingAs($user)
            ->post('/rendez-vous', ['creneau_id' => $creneau->id])
            ->assertSessionHasErrors('creneau_id');

        $this->assertDatabaseCount('rendez_vous', 0);
    }

    public function test_double_reservation_simultanee_un_seul_rdv_cree(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $creneau = Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        // Première réservation réussit
        $response1 = $this->actingAs($user1)
            ->post('/rendez-vous', ['creneau_id' => $creneau->id]);

        $response1->assertRedirect(route('rendez-vous.mine'));

        // Deuxième réservation échoue (créneau déjà réservé)
        $response2 = $this->actingAs($user2)
            ->post('/rendez-vous', ['creneau_id' => $creneau->id]);

        $response2->assertSessionHasErrors('creneau_id');

        // Un seul rendez-vous doit exister
        $this->assertDatabaseCount('rendez_vous', 1);
        $this->assertDatabaseHas('rendez_vous', [
            'creneau_id' => $creneau->id,
            'user_id' => $user1->id,
        ]);
    }

    public function test_client_peut_voir_les_creneaux_disponibles(): void
    {
        $user = User::factory()->create();

        Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '14:00:00',
            'duree_minutes' => 60,
        ]);

        $this->actingAs($user)
            ->get('/creneaux')
            ->assertOk()
            ->assertSee('14:00');
    }

    public function test_client_peut_voir_ses_rendez_vous(): void
    {
        $user = User::factory()->create();

        $creneau = Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        RendezVous::factory()->create([
            'creneau_id' => $creneau->id,
            'user_id' => $user->id,
            'statut' => 'en_attente',
        ]);

        $this->actingAs($user)
            ->get('/mes-rendez-vous')
            ->assertOk()
            ->assertSee('En attente');
    }
}
