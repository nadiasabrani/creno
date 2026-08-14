<?php

namespace Tests\Feature\Admin;

use App\Models\Creneau;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_est_accessible_par_un_admin(): void
    {
        $admin = User::factory()->asAdmin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Tableau de bord');
    }

    public function test_dashboard_refuse_un_utilisateur_non_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_dashboard_affiche_tous_les_rendez_vous(): void
    {
        $admin = User::factory()->asAdmin()->create();
        $client = User::factory()->create();
        $creneau = Creneau::factory()->create();

        RendezVous::factory()->create([
            'creneau_id' => $creneau->id,
            'user_id' => $client->id,
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee($client->email)
            ->assertSee($creneau->date);
    }
}
