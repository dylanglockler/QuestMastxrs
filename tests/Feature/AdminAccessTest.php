<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_host_can_access_the_admin_panel(): void
    {
        Role::firstOrCreate(['name' => 'host', 'guard_name' => 'web']);
        $host = User::factory()->create();
        $host->assignRole('host');

        $response = $this->actingAs($host)->get('/admin');

        $response->assertOk();
    }

    public function test_a_non_host_cannot_access_the_admin_panel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }

    public function test_a_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }
}
