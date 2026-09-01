<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSeederLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_admin_can_login_and_reach_the_admin_dashboard(): void
    {
        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', 'melysaputrifebriani.82@gmail.com')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('Admin12345!', $admin->password));

        $response = $this->post('/login', [
            'email' => 'melysaputrifebriani.82@gmail.com',
            'password' => 'Admin12345!',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertRedirect('/admin/dashboard');
        $this->get('/admin/dashboard')->assertOk();
    }
}
