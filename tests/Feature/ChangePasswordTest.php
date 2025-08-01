<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_change_password_form_loads_successfully()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/change-password');

        $response->assertStatus(200);
        $response->assertViewIs('auth.change-password');
    }

    public function test_user_can_change_password_with_correct_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->post('/change-password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newsecurepassword',
            'new_password_confirmation' => 'newsecurepassword',
        ]);

        $response->assertRedirect(route('password.update'));
        $response->assertSessionHas('status', 'Password changed successfully');

        $this->assertTrue(Hash::check('newsecurepassword', $user->fresh()->password));
    }

    public function test_password_change_fails_with_incorrect_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->actingAs($user)->post('/change-password', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('correctpassword', $user->fresh()->password));
    }

    public function test_password_change_fails_if_new_password_does_not_match_confirmation()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->post('/change-password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors('new_password');
        $this->assertTrue(Hash::check('oldpassword', $user->fresh()->password));
    }
}
