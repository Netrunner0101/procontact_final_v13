<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use App\Models\RendezVous;
use App\Models\Activite;
use App\Models\Note;
use App\Models\Role;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $client;
    protected $status;
    protected $adminRole;
    protected $clientRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['nom' => Role::ADMIN], ['description' => 'Administrator']);
        $this->clientRole = Role::firstOrCreate(['nom' => Role::CLIENT], ['description' => 'Client']);

        $this->admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $this->status = Status::factory()->create(['status_client' => 'Prospect']);
    }

    // ==========================================
    // Public routes (guest)
    // ==========================================

    public function test_landing_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_page_is_accessible()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_forgot_password_page_is_accessible()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    // ==========================================
    // Auth POST routes
    // ==========================================

    public function test_login_post_with_valid_data()
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => 'password123',
            'role_id' => $this->adminRole->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_register_post_creates_user()
    {
        $response = $this->post('/register', [
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'newuser@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'newuser@test.com']);
        $this->assertAuthenticated();
    }

    public function test_logout_post_logs_out_user()
    {
        $response = $this->actingAs($this->admin)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    // ==========================================
    // Admin protected routes - GET
    // ==========================================

    public function test_dashboard_requires_admin_auth()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_dashboard_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_contacts_index_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/contacts');
        $response->assertStatus(200);
    }

    public function test_contacts_create_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/contacts/create');
        $response->assertStatus(200);
    }

    public function test_activites_index_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/activites');
        $response->assertStatus(200);
    }

    public function test_activites_create_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/activites/create');
        $response->assertStatus(200);
    }

    public function test_rendez_vous_index_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/rendez-vous');
        $response->assertStatus(200);
    }

    public function test_rendez_vous_create_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/rendez-vous/create');
        $response->assertStatus(200);
    }

    public function test_notes_index_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/notes');
        $response->assertStatus(200);
    }

    public function test_notes_create_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/notes/create');
        $response->assertStatus(200);
    }

    public function test_statistiques_accessible_for_admin()
    {
        $response = $this->actingAs($this->admin)->get('/statistiques');
        $response->assertStatus(200);
    }

    public function test_profile_accessible_for_authenticated_user()
    {
        $response = $this->actingAs($this->admin)->get('/profile');
        $response->assertStatus(200);
    }

    // ==========================================
    // Contacts CRUD (web)
    // ==========================================

    public function test_contact_show_route()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->admin->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->admin)->get("/contacts/{$contact->id}");
        $response->assertStatus(200);
    }

    public function test_contact_store_route()
    {
        $response = $this->actingAs($this->admin)->post('/contacts', [
            'nom' => 'Test',
            'prenom' => 'Contact',
            'emails' => ['test@contact.com'],
            'phones' => ['0123456789'],
            'status_id' => $this->status->id,
        ]);

        $response->assertRedirect(route('contacts.index'));
        $this->assertDatabaseHas('contacts', ['nom' => 'Test', 'prenom' => 'Contact']);
    }

    public function test_contact_update_route()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->admin->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->admin)->put("/contacts/{$contact->id}", [
            'nom' => 'Updated',
            'prenom' => 'Name',
            'status_id' => $this->status->id,
        ]);

        $response->assertRedirect(route('contacts.show', $contact));
        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'nom' => 'Updated']);
    }

    public function test_contact_delete_route()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->admin->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->admin)->delete("/contacts/{$contact->id}");

        $response->assertRedirect(route('contacts.index'));
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    // ==========================================
    // Activites CRUD (web)
    // ==========================================

    public function test_activite_store_route()
    {
        $response = $this->actingAs($this->admin)->post('/activites', [
            'nom' => 'New Activity',
            'description' => 'Activity description',
        ]);

        $response->assertRedirect(route('activites.index'));
        $this->assertDatabaseHas('activites', ['nom' => 'New Activity']);
    }

    public function test_activite_show_route()
    {
        $activite = Activite::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get("/activites/{$activite->id}");
        $response->assertStatus(200);
    }

    public function test_activite_update_route()
    {
        $activite = Activite::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->put("/activites/{$activite->id}", [
            'nom' => 'Updated Activity',
        ]);

        $response->assertRedirect(route('activites.show', $activite));
        $this->assertDatabaseHas('activites', ['id' => $activite->id, 'nom' => 'Updated Activity']);
    }

    public function test_activite_delete_route()
    {
        $activite = Activite::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->delete("/activites/{$activite->id}");

        $response->assertRedirect(route('activites.index'));
        $this->assertDatabaseMissing('activites', ['id' => $activite->id]);
    }

    // ==========================================
    // Rendez-vous CRUD (web)
    // ==========================================

    public function test_rendez_vous_store_route()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->admin->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->post('/rendez-vous', [
            'titre' => 'New Appointment',
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
            'date_debut' => now()->addDay()->format('Y-m-d'),
            'date_fin' => now()->addDay()->format('Y-m-d'),
            'heure_debut' => '09:00',
            'heure_fin' => '10:00',
        ]);

        $response->assertRedirect(route('rendez-vous.index'));
        $this->assertDatabaseHas('rendez_vous', ['titre' => 'New Appointment']);
    }

    public function test_rendez_vous_show_route()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->admin->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create(['user_id' => $this->admin->id]);
        $rdv = RendezVous::factory()->create([
            'user_id' => $this->admin->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
        ]);

        $response = $this->actingAs($this->admin)->get("/rendez-vous/{$rdv->id}");
        $response->assertStatus(200);
    }

    public function test_rendez_vous_delete_route()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->admin->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create(['user_id' => $this->admin->id]);
        $rdv = RendezVous::factory()->create([
            'user_id' => $this->admin->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
        ]);

        $response = $this->actingAs($this->admin)->delete("/rendez-vous/{$rdv->id}");

        $response->assertRedirect(route('rendez-vous.index'));
        $this->assertDatabaseMissing('rendez_vous', ['id' => $rdv->id]);
    }

    // ==========================================
    // Notes CRUD (web)
    // ==========================================

    public function test_note_store_route()
    {
        $response = $this->actingAs($this->admin)->post('/notes', [
            'titre' => 'Test Note',
            'commentaire' => 'Note content',
            'priorite' => 'Normale',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('notes', ['titre' => 'Test Note']);
    }

    public function test_note_show_route()
    {
        $note = Note::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get("/notes/{$note->id}");
        $response->assertStatus(200);
    }

    public function test_note_update_route()
    {
        $note = Note::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->put("/notes/{$note->id}", [
            'titre' => 'Updated Note',
            'commentaire' => 'Updated content',
            'priorite' => 'Haute',
        ]);

        $response->assertRedirect(route('notes.show', $note));
        $this->assertDatabaseHas('notes', ['id' => $note->id, 'titre' => 'Updated Note']);
    }

    public function test_note_delete_route()
    {
        $note = Note::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->delete("/notes/{$note->id}");

        $response->assertRedirect(route('notes.index'));
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    // ==========================================
    // Profile routes
    // ==========================================

    public function test_profile_update_route()
    {
        $response = $this->actingAs($this->admin)->put('/profile', [
            'nom' => 'Updated',
            'prenom' => 'Admin',
            'email' => $this->admin->email,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'nom' => 'Updated']);
    }

    public function test_profile_password_update_route()
    {
        $user = User::factory()->create([
            'password' => 'oldpassword123',
            'role_id' => $this->adminRole->id,
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    // ==========================================
    // Admin client management routes
    // ==========================================

    public function test_admin_clients_index_route()
    {
        $response = $this->actingAs($this->admin)->get('/admin/clients');
        $response->assertStatus(200);
    }

    public function test_admin_clients_create_route()
    {
        $response = $this->actingAs($this->admin)->get('/admin/clients/create');
        $response->assertStatus(200);
    }

    public function test_admin_clients_store_route()
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', [
            'nom' => 'Client',
            'prenom' => 'Test',
            'email' => 'client@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.clients.index'));
        $this->assertDatabaseHas('users', ['email' => 'client@test.com']);
    }

    // ==========================================
    // Guest access denied for protected routes
    // ==========================================

    public function test_guest_cannot_access_protected_routes()
    {
        $protectedGetRoutes = [
            '/dashboard',
            '/contacts',
            '/contacts/create',
            '/activites',
            '/activites/create',
            '/rendez-vous',
            '/rendez-vous/create',
            '/notes',
            '/notes/create',
            '/statistiques',
            '/admin/clients',
        ];

        foreach ($protectedGetRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    // ==========================================
    // API routes require auth
    // ==========================================

    public function test_api_routes_require_authentication()
    {
        $apiRoutes = [
            ['GET', '/api/contacts'],
            ['GET', '/api/rendez-vous'],
            ['GET', '/api/statistics'],
            ['GET', '/api/export/contacts'],
        ];

        foreach ($apiRoutes as [$method, $route]) {
            $response = $this->json($method, $route);
            $response->assertStatus(401);
        }
    }

    public function test_api_post_routes_require_authentication()
    {
        $response = $this->postJson('/api/contacts', ['nom' => 'Test', 'prenom' => 'User']);
        $response->assertStatus(401);

        $response = $this->postJson('/api/rendez-vous', ['titre' => 'Test']);
        $response->assertStatus(401);
    }
}
