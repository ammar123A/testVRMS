<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SystemAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_list_page_loads_with_filters()
    {
        User::factory()->create([
            'username' => 'adminuser',
            'em_id' => 'EM001',
            'role' => 'admin',
            'faculty' => 'UTMJB',
        ]);

        $response = $this->get('/system-admin/user');

        $response->assertStatus(200);
        $response->assertViewIs('system-admin.user');
        $response->assertViewHas('users');
    }

    public function test_register_form_loads()
    {
        $response = $this->get('/system-admin/user/register');

        $response->assertStatus(200);
        $response->assertViewIs('system-admin.user-register');
        $response->assertViewHas('users');
        $response->assertViewHas('roles');
    }

    public function test_user_can_be_registered()
    {
        $data = [
            'username' => 'newuser',
            'name' => 'Ali New',
            'em_id' => 'EM888',
            'email' => 'ali@example.com',
            'faculty' => 'Engineering',
            'role' => 'staff',
        ];

        $response = $this->post('/system-admin/user/store', $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'em_id' => 'EM888',
            'role' => 'staff',
        ]);

        $this->assertTrue(Hash::check('default123', User::where('username', 'newuser')->first()->password));
    }

    public function test_user_registration_fails_with_missing_fields()
    {
        $response = $this->post('/system-admin/user/store', []);

        $response->assertSessionHasErrors(['username', 'name', 'em_id', 'faculty', 'role']);
    }

    public function test_fetch_user_data_partial_view()
    {
        User::factory()->create([
            'username' => 'ajaxuser',
            'em_id' => 'EM007',
            'role' => 'student',
            'faculty' => 'KL',
        ]);

        $response = $this->get('/system-admin/user');

        $response->assertStatus(200);
        $response->assertViewIs('system-admin.user');
        $response->assertViewHas('users');
    }
}
