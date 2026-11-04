<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_denied_from_both_admin_areas(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/superadmin/admin')->assertRedirect(route('login'));
    }

    public function test_admin_can_access_admin_area_but_not_superadmin_crud(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
        $this->actingAs($admin)->get('/superadmin/admin')->assertForbidden();

        $this->actingAs($admin)->post('/superadmin/admin', [
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'password' => 'password123',
        ])->assertForbidden();

        $this->actingAs($admin)->put("/superadmin/admin/{$target->id}", [
            'name' => 'Changed Admin',
            'email' => 'changed-admin@example.com',
        ])->assertForbidden();

        $this->actingAs($admin)
            ->delete("/superadmin/admin/{$target->id}")
            ->assertForbidden();
    }

    public function test_superadmin_can_access_admin_and_manage_admin_accounts(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $target = User::factory()->create(['role' => 'admin']);

        $this->actingAs($superadmin)->get('/admin/dashboard')->assertOk();
        $this->actingAs($superadmin)->get('/superadmin/admin')->assertOk();

        $this->actingAs($superadmin)->post('/superadmin/admin', [
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('superadmin.admin.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'new-admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($superadmin)->put("/superadmin/admin/{$target->id}", [
            'name' => 'Changed Admin',
            'email' => 'changed-admin@example.com',
        ])->assertRedirect(route('superadmin.admin.index'));

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'email' => 'changed-admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($superadmin)
            ->delete("/superadmin/admin/{$target->id}")
            ->assertRedirect(route('superadmin.admin.index'));

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }
}
