<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_are_redirected_to_their_own_dashboard(): void
    {
        foreach (['admin', 'seller', 'buyer'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->actingAs($user)->get('/dashboard');

            $response->assertRedirect(route($role.'.dashboard'));
        }
    }

    public function test_users_cannot_access_a_dashboard_for_another_role(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);

        $this->actingAs($buyer)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($buyer)->get('/seller/dashboard')->assertForbidden();
        $this->actingAs($buyer)->get('/buyer/dashboard')->assertOk();
    }
}
