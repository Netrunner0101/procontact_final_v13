<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure admin role exists for registration
        Role::firstOrCreate(['nom' => Role::ADMIN], ['description' => 'Administrator']);
        Role::firstOrCreate(['nom' => Role::CLIENT], ['description' => 'Client']);
    }

    public function test_login_page_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_register_page_can_be_rendered()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_user_can_register_with_valid_data()
    {
        $userData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
        ]);
        $this->assertAuthenticated();
    }

    public function test_user_cannot_register_with_invalid_email()
    {
        $userData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_cannot_register_with_mismatched_passwords()
    {
        $userData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ];

        $response = $this->post('/register', $userData);
        $response->assertSessionHasErrors('password');
    }

    public function test_user_cannot_register_with_short_password()
    {
        $userData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->post('/register', $userData);
        $response->assertSessionHasErrors('password');
    }

    public function test_user_cannot_register_with_duplicate_email()
    {
        $adminRole = Role::where('nom', Role::ADMIN)->first();
        User::factory()->create([
            'email' => 'existing@example.com',
            'role_id' => $adminRole->id,
        ]);

        $userData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);
        $response->assertSessionHasErrors('email');
    }

    public function test_user_cannot_register_without_required_fields()
    {
        $response = $this->post('/register', []);
        $response->assertSessionHasErrors(['nom', 'prenom', 'email', 'password']);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $adminRole = Role::where('nom', Role::ADMIN)->first();
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
            'role_id' => $adminRole->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $adminRole = Role::where('nom', Role::ADMIN)->first();
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
            'role_id' => $adminRole->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout()
    {
        $adminRole = Role::where('nom', Role::ADMIN)->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_forgot_password_page_can_be_rendered()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_password_reset_email_can_be_requested()
    {
        $adminRole = Role::where('nom', Role::ADMIN)->first();
        User::factory()->create([
            'email' => 'test@example.com',
            'role_id' => $adminRole->id,
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_access_dashboard()
    {
        $adminRole = Role::where('nom', Role::ADMIN)->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_from_protected_routes()
    {
        $protectedRoutes = ['/dashboard', '/contacts', '/activites', '/rendez-vous', '/notes', '/profile'];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    public function test_authenticated_user_cannot_access_login_page()
    {
        $adminRole = Role::where('nom', Role::ADMIN)->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect();
    }

    public function test_authenticated_user_cannot_access_register_page()
    {
        $adminRole = Role::where('nom', Role::ADMIN)->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect();
    }
}
