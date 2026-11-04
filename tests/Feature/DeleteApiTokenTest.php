<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_delete_an_admin_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->delete("/superadmin/admin/{$target->id}")->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'admin']);
    }
}
