<?php

namespace Tests\Unit;

use App\Models\Creneau;
use App\Models\RendezVous;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreneauTest extends TestCase
{
    use RefreshDatabase;

    public function test_chevauche_est_faux_pour_des_dates_differentes(): void
    {
        $premier = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);
        $second = new Creneau([
            'date' => '2026-09-02',
            'heure_debut' => '10:30:00',
            'duree_minutes' => 60,
        ]);

        $this->assertFalse($premier->chevauche($second));
    }

    public function test_chevauche_est_faux_pour_des_creneaux_contigus(): void
    {
        $premier = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);
        $second = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '11:00:00',
            'duree_minutes' => 60,
        ]);

        $this->assertFalse($premier->chevauche($second));
        $this->assertFalse($second->chevauche($premier));
    }

    public function test_chevauche_est_vrai_pour_un_chevauchement_partiel(): void
    {
        $premier = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);
        $second = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '10:30:00',
            'duree_minutes' => 60,
        ]);

        $this->assertTrue($premier->chevauche($second));
        $this->assertTrue($second->chevauche($premier));
    }

    public function test_chevauche_est_vrai_pour_une_inclusion_totale(): void
    {
        $premier = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '09:00:00',
            'duree_minutes' => 120,
        ]);
        $second = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '09:30:00',
            'duree_minutes' => 30,
        ]);

        $this->assertTrue($premier->chevauche($second));
        $this->assertTrue($second->chevauche($premier));
    }

    public function test_chevauche_est_vrai_pour_deux_creneaux_identiques(): void
    {
        $premier = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'duree_minutes' => 30,
        ]);
        $second = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'duree_minutes' => 30,
        ]);

        $this->assertTrue($premier->chevauche($second));
    }

    public function test_fin_calcule_heure_debut_plus_duree_minutes(): void
    {
        $creneau = new Creneau([
            'date' => '2026-09-01',
            'heure_debut' => '10:00:00',
            'duree_minutes' => 45,
        ]);

        $this->assertSame('10:45', $creneau->fin()->format('H:i'));
    }

    public function test_scope_passes_exclut_les_creneaux_futurs(): void
    {
        Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        $this->assertSame(0, Creneau::passes()->count());
    }

    public function test_scope_passes_inclut_un_creneau_dont_la_date_est_passee(): void
    {
        Creneau::factory()->create([
            'date' => now()->subDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        $this->assertSame(1, Creneau::passes()->count());
    }

    public function test_scope_passes_inclut_un_creneau_du_jour_dont_la_fin_est_passee(): void
    {
        Creneau::factory()->create([
            'date' => now()->toDateString(),
            'heure_debut' => now()->subMinutes(60)->format('H:i:s'),
            'duree_minutes' => 30,
        ]);

        $this->assertSame(1, Creneau::passes()->count());
    }

    public function test_scope_passes_inclut_un_creneau_dont_la_fin_est_exactement_maintenant(): void
    {
        Creneau::factory()->create([
            'date' => now()->toDateString(),
            'heure_debut' => now()->subMinutes(30)->format('H:i:s'),
            'duree_minutes' => 30,
        ]);

        $this->assertSame(1, Creneau::passes()->count());
    }

    public function test_scope_passes_exclut_un_creneau_du_jour_encore_en_cours(): void
    {
        Creneau::factory()->create([
            'date' => now()->toDateString(),
            'heure_debut' => now()->addMinutes(30)->format('H:i:s'),
            'duree_minutes' => 60,
        ]);

        $this->assertSame(0, Creneau::passes()->count());
    }

    public function test_scope_disponibles_inclut_un_creneau_futur_libre(): void
    {
        Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        $this->assertSame(1, Creneau::disponibles()->count());
    }

    public function test_scope_disponibles_exclut_un_creneau_reserve(): void
    {
        $creneau = Creneau::factory()->create([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        RendezVous::factory()->create(['creneau_id' => $creneau->id]);

        $this->assertSame(0, Creneau::disponibles()->count());
    }

    public function test_scope_disponibles_exclut_un_creneau_passe(): void
    {
        Creneau::factory()->create([
            'date' => now()->subDay()->toDateString(),
            'heure_debut' => '10:00:00',
            'duree_minutes' => 60,
        ]);

        $this->assertSame(0, Creneau::disponibles()->count());
    }

    public function test_scope_disponibles_exclut_un_creneau_du_jour_deja_termine(): void
    {
        Creneau::factory()->create([
            'date' => now()->toDateString(),
            'heure_debut' => now()->subMinutes(60)->format('H:i:s'),
            'duree_minutes' => 30,
        ]);

        $this->assertSame(0, Creneau::disponibles()->count());
    }
}
