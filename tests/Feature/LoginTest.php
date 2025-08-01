<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials_and_role()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'role' => 'student',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password',
            'role' => 'student',
        ]);

        $response->assertRedirect(route('redirect.by.role'));
        $this->assertAuthenticatedAs($user);

        $this->assertTrue(session()->has('FLEET'));
        $this->assertEquals('student', session('FLEET.user_type'));
    }

    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword',
            'role' => 'admin',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_role()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'username' => 'testuser',
            'password' => 'password',
            'role' => 'supervisor', // wrong role
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }
}
