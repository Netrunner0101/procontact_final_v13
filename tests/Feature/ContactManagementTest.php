<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use App\Models\Role;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactManagementTest extends TestCase
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

    public function test_authenticated_user_can_view_contacts_index()
    {
        $response = $this->actingAs($this->user)->get('/contacts');
        $response->assertStatus(200);
        $response->assertViewIs('contacts.index');
    }

    public function test_authenticated_user_can_view_contact_creation_form()
    {
        $response = $this->actingAs($this->user)->get('/contacts/create');
        $response->assertStatus(200);
        $response->assertViewIs('contacts.create');
    }

    public function test_authenticated_user_can_create_contact()
    {
        $contactData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'emails' => ['jean.dupont@example.com'],
            'phones' => ['0123456789'],
            'rue' => '123 Rue de la Paix',
            'ville' => 'Paris',
            'code_postal' => '75001',
            'pays' => 'France',
            'status_id' => $this->status->id,
        ];

        $response = $this->actingAs($this->user)->post('/contacts', $contactData);

        $response->assertRedirect(route('contacts.index'));
        $this->assertDatabaseHas('contacts', [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_contact_creation_requires_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/contacts', []);

        $response->assertSessionHasErrors(['nom', 'prenom', 'emails', 'phones']);
    }

    public function test_contact_creation_validates_email_format()
    {
        $contactData = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'emails' => ['not-an-email'],
            'phones' => ['0123456789'],
        ];

        $response = $this->actingAs($this->user)->post('/contacts', $contactData);
        $response->assertSessionHasErrors('emails.0');
    }

    public function test_authenticated_user_can_view_contact_details()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user)->get("/contacts/{$contact->id}");
        $response->assertStatus(200);
        $response->assertViewIs('contacts.show');
    }

    public function test_authenticated_user_can_edit_own_contact()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user)->get("/contacts/{$contact->id}/edit");
        $response->assertStatus(200);
        $response->assertViewIs('contacts.edit');
    }

    public function test_authenticated_user_can_update_own_contact()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $updateData = [
            'nom' => 'Martin',
            'prenom' => 'Marie',
            'rue' => '456 Avenue des Champs',
            'ville' => 'Lyon',
            'code_postal' => '69001',
            'pays' => 'France',
            'status_id' => $this->status->id,
        ];

        $response = $this->actingAs($this->user)->put("/contacts/{$contact->id}", $updateData);

        $response->assertRedirect(route('contacts.show', $contact));
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'nom' => 'Martin',
            'prenom' => 'Marie',
        ]);
    }

    public function test_authenticated_user_can_delete_own_contact()
    {
        $contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/contacts/{$contact->id}");

        $response->assertRedirect(route('contacts.index'));
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_user_cannot_view_other_users_contacts()
    {
        $otherUser = User::factory()->create();
        $contact = Contact::factory()->create([
            'user_id' => $otherUser->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user)->get("/contacts/{$contact->id}");
        $response->assertStatus(403);
    }

    public function test_user_cannot_edit_other_users_contacts()
    {
        $otherUser = User::factory()->create();
        $contact = Contact::factory()->create([
            'user_id' => $otherUser->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user)->get("/contacts/{$contact->id}/edit");
        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_other_users_contacts()
    {
        $otherUser = User::factory()->create();
        $contact = Contact::factory()->create([
            'user_id' => $otherUser->id,
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/contacts/{$contact->id}");
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_contacts()
    {
        $response = $this->get('/contacts');
        $response->assertRedirect('/login');
    }
}
