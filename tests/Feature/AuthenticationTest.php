<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('MotoJet Servis');
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_authenticated_user_redirected_to_dashboard()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/dashboard');
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_admin_redirected_to_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin');
    }

    public function test_staff_redirected_to_staff_dashboard()
    {
        $user = User::factory()->create(['role' => 'personel']);
        
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertRedirect('/staff/dashboard');
    }
}
