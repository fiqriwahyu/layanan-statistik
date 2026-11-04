<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_denied_access_to_superadmin_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/superadmin/admin')->assertForbidden();
    }
}
