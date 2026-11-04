<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_delete_account_from_profile(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->delete('/profile', ['password' => 'password'])->assertForbidden();
        $this->assertNotNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_before_account_can_be_deleted(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->from('/profile')->delete('/profile', ['password' => 'wrong-password'])
            ->assertRedirect('/profile')->assertSessionHasErrorsIn('userDeletion', 'password');

        $this->assertNotNull($user->fresh());
    }
}
