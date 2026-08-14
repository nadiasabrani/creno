<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_invite_est_redirige_vers_le_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/creneaux')->assertRedirect('/login');
    }

    public function test_login_redirige_un_admin_vers_le_dashboard_admin(): void
    {
        $admin = User::factory()->asAdmin()->create();

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_redirige_un_non_admin_vers_laccueil(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_connecte_accede_a_admin(): void
    {
        $admin = User::factory()->asAdmin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_non_admin_naccede_pas_a_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_register_cree_un_utilisateur_avec_role_user(): void
    {
        $this->post('/register', [
            'name' => 'Nouvel Utilisateur',
            'email' => 'nouveau@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'nouveau@example.com',
            'role' => 'user',
        ]);
    }

    public function test_logout_est_fonctionnel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }
}