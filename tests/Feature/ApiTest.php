<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use App\Models\RendezVous;
use App\Models\Role;
use App\Models\Status;
use App\Models\Activite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $status;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['nom' => Role::ADMIN], ['description' => 'Administrator']);
        Role::firstOrCreate(['nom' => Role::CLIENT], ['description' => 'Client']);

        $this->user = User::factory()->create(['role_id' => $adminRole->id]);
        $this->status = Status::factory()->create(['status_client' => 'Prospect']);
    }

    // ==========================================
    // Authentication
    // ==========================================

    public function test_api_requires_authentication()
    {
        $response = $this->getJson('/api/contacts');
        $response->assertStatus(401);
    }

    // ==========================================
    // Contacts API - CRUD
    // ==========================================

    public function test_api_contacts_index_returns_user_contacts()
    {
        Contact::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $otherUser = User::factory()->create();
        Contact::factory()->count(2)->create([
            'user_id' => $otherUser->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/contacts');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_api_can_create_contact()
    {
        $contactData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'telephone' => '0123456789',
            'status_id' => $this->status->id,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/contacts', $contactData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'nom' => 'Dupont',
                'prenom' => 'Jean',
            ]);

        $this->assertDatabaseHas('contacts', [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_api_create_contact_validates_required_fields()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/contacts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nom', 'prenom']);
    }

    public function test_api_can_show_own_contact()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['nom' => $contact->nom]);
    }

    public function test_api_can_update_contact()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $updateData = [
            'nom' => 'Martin',
            'prenom' => 'Marie',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/contacts/{$contact->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'nom' => 'Martin',
                'prenom' => 'Marie',
            ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'nom' => 'Martin',
            'prenom' => 'Marie',
        ]);
    }

    public function test_api_can_delete_contact()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_api_cannot_access_other_user_contact()
    {
        $otherUser = User::factory()->create();
        $otherContact = Contact::factory()->create([
            'user_id' => $otherUser->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/contacts/{$otherContact->id}");

        $response->assertStatus(403);
    }

    public function test_api_cannot_update_other_user_contact()
    {
        $otherUser = User::factory()->create();
        $otherContact = Contact::factory()->create([
            'user_id' => $otherUser->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/contacts/{$otherContact->id}", ['nom' => 'Hacked']);

        $response->assertStatus(403);
    }

    public function test_api_cannot_delete_other_user_contact()
    {
        $otherUser = User::factory()->create();
        $otherContact = Contact::factory()->create([
            'user_id' => $otherUser->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/contacts/{$otherContact->id}");

        $response->assertStatus(403);
    }

    // ==========================================
    // Rendez-vous API - CRUD
    // ==========================================

    public function test_api_appointments_index_returns_user_appointments()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create([
            'user_id' => $this->user->id,
        ]);

        RendezVous::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/rendez-vous');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_api_can_create_appointment()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $appointmentData = [
            'titre' => 'Consultation API',
            'description' => 'Test via API',
            'date_debut' => now()->addDay()->format('Y-m-d'),
            'date_fin' => now()->addDay()->format('Y-m-d'),
            'heure_debut' => '10:00',
            'heure_fin' => '11:00',
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/rendez-vous', $appointmentData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'titre' => 'Consultation API',
                'description' => 'Test via API',
            ]);

        $this->assertDatabaseHas('rendez_vous', [
            'titre' => 'Consultation API',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_api_validates_appointment_data()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/rendez-vous', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'titre', 'date_debut', 'contact_id', 'activite_id'
            ]);
    }

    public function test_api_can_show_own_appointment()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $appointment = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/rendez-vous/{$appointment->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['titre' => $appointment->titre]);
    }

    public function test_api_can_update_appointment()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $appointment = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/rendez-vous/{$appointment->id}", [
                'titre' => 'Updated Title',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['titre' => 'Updated Title']);

        $this->assertDatabaseHas('rendez_vous', [
            'id' => $appointment->id,
            'titre' => 'Updated Title',
        ]);
    }

    public function test_api_can_delete_appointment()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $appointment = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/rendez-vous/{$appointment->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('rendez_vous', ['id' => $appointment->id]);
    }

    public function test_api_cannot_access_other_user_appointment()
    {
        $otherUser = User::factory()->create();
        $otherContact = Contact::factory()->create([
            'user_id' => $otherUser->id,
            'status_id' => $this->status->id,
        ]);
        $otherActivite = Activite::factory()->create([
            'user_id' => $otherUser->id,
        ]);
        $appointment = RendezVous::factory()->create([
            'user_id' => $otherUser->id,
            'contact_id' => $otherContact->id,
            'activite_id' => $otherActivite->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/rendez-vous/{$appointment->id}");

        $response->assertStatus(403);
    }

    // ==========================================
    // Statistics API
    // ==========================================

    public function test_api_statistics_endpoint()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);
        $activite = Activite::factory()->create([
            'user_id' => $this->user->id,
        ]);
        RendezVous::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'contacts_count',
                'appointments_count',
                'activities_count',
                'monthly_stats',
            ]);
    }

    // ==========================================
    // Export API
    // ==========================================

    public function test_api_export_contacts()
    {
        Contact::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/export/contacts');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
