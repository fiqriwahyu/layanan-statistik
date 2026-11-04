<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_named_registration_route_is_unavailable(): void
    {
        $this->assertSame('/register', route('register', absolute: false));

        $this->get(route('register'))->assertNotFound();
    }

    public function test_authenticated_users_cannot_open_registration(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('register'))
            ->assertNotFound();
    }

    public function test_public_registration_post_is_unavailable(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertMethodNotAllowed();

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }
}
