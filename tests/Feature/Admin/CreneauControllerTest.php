<?php

namespace Tests\Feature\Admin;

use App\Models\Creneau;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreneauControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_est_redirige_avec_403(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/creneaux')
            ->assertForbidden();
    }

    public function test_invite_est_redirige_vers_la_connexion(): void
    {
        $this->get('/admin/creneaux')
            ->assertRedirect('/login');
    }

    public function test_admin_peut_voir_la_liste_des_creneaux(): void
    {
        $admin = User::factory()->asAdmin()->create();
        Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        $this->actingAs($admin)
            ->get('/admin/creneaux')
            ->assertOk()
            ->assertSee('10:00');
    }

    public function test_admin_peut_creer_un_creneau(): void
    {
        $admin = User::factory()->asAdmin()->create();

        $this->actingAs($admin)
            ->post('/admin/creneaux', [
                'date' => now()->addDay()->toDateString(),
                'heure_debut' => '09:00',
                'duree_minutes' => 30,
            ])
            ->assertRedirect(route('admin.creneaux.index'));

        $this->assertDatabaseHas('creneaux', [
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '09:00:00',
            'duree_minutes' => 30,
        ]);
    }

    public function test_creation_refusee_en_cas_de_chevauchement(): void
    {
        $admin = User::factory()->asAdmin()->create();

        Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '09:00:00',
            'duree_minutes' => 60,
        ]);

        $this->actingAs($admin)
            ->post('/admin/creneaux', [
                'date' => now()->addDay()->toDateString(),
                'heure_debut' => '09:30',
                'duree_minutes' => 30,
            ])
            ->assertSessionHasErrors('date');
    }

    public function test_admin_peut_modifier_un_creneau(): void
    {
        $admin = User::factory()->asAdmin()->create();

        $creneau = Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '09:00:00',
            'duree_minutes' => 30,
        ]);

        $this->actingAs($admin)
            ->put("/admin/creneaux/{$creneau->id}", [
                'date' => now()->addDay()->toDateString(),
                'heure_debut' => '10:00',
                'duree_minutes' => 45,
            ])
            ->assertRedirect(route('admin.creneaux.index'));

        $this->assertDatabaseHas('creneaux', [
            'id' => $creneau->id,
            'heure_debut' => '10:00:00',
            'duree_minutes' => 45,
        ]);
    }

    public function test_admin_peut_supprimer_un_creneau(): void
    {
        $admin = User::factory()->asAdmin()->create();

        $creneau = Creneau::factory()->create();

        $this->actingAs($admin)
            ->delete("/admin/creneaux/{$creneau->id}")
            ->assertRedirect(route('admin.creneaux.index'));

        $this->assertDatabaseMissing('creneaux', ['id' => $creneau->id]);
    }
}
