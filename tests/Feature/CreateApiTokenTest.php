<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_create_an_admin_through_the_registered_route(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $this->actingAs($superadmin)->post('/superadmin/admin', [
            'name' => 'Admin Baru', 'email' => 'admin-baru@example.com', 'password' => 'a-secure-password',
        ])->assertRedirect(route('superadmin.admin.index'));

        $this->assertDatabaseHas('users', ['email' => 'admin-baru@example.com', 'role' => 'admin']);
    }
}
