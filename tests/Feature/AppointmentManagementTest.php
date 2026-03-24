<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use App\Models\RendezVous;
use App\Models\Activite;
use App\Models\Role;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class AppointmentManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $contact;
    protected $activite;
    protected $status;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['nom' => Role::ADMIN], ['description' => 'Administrator']);
        Role::firstOrCreate(['nom' => Role::CLIENT], ['description' => 'Client']);

        $this->user = User::factory()->create(['role_id' => $adminRole->id]);
        $this->status = Status::factory()->create(['status_client' => 'Prospect']);

        $this->contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $this->status->id,
        ]);

        $this->activite = Activite::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_authenticated_user_can_view_appointments_index()
    {
        $response = $this->actingAs($this->user)->get('/rendez-vous');
        $response->assertStatus(200);
        $response->assertViewIs('rendez-vous.index');
    }

    public function test_authenticated_user_can_view_appointment_creation_form()
    {
        $response = $this->actingAs($this->user)->get('/rendez-vous/create');
        $response->assertStatus(200);
        $response->assertViewIs('rendez-vous.create');
    }

    public function test_authenticated_user_can_create_appointment()
    {
        $tomorrow = Carbon::tomorrow();

        $appointmentData = [
            'titre' => 'Consultation médicale',
            'description' => 'Consultation de routine',
            'date_debut' => $tomorrow->format('Y-m-d'),
            'date_fin' => $tomorrow->format('Y-m-d'),
            'heure_debut' => '10:00',
            'heure_fin' => '11:00',
            'contact_id' => $this->contact->id,
            'activite_id' => $this->activite->id,
        ];

        $response = $this->actingAs($this->user)->post('/rendez-vous', $appointmentData);

        $response->assertRedirect(route('rendez-vous.index'));
        $this->assertDatabaseHas('rendez_vous', [
            'titre' => 'Consultation médicale',
            'contact_id' => $this->contact->id,
            'activite_id' => $this->activite->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_appointment_creation_requires_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/rendez-vous', []);

        $response->assertSessionHasErrors([
            'titre', 'date_debut', 'date_fin', 'heure_debut', 'heure_fin', 'contact_id', 'activite_id'
        ]);
    }

    public function test_authenticated_user_can_view_appointment_details()
    {
        $appointment = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $this->contact->id,
            'activite_id' => $this->activite->id,
        ]);

        $response = $this->actingAs($this->user)->get("/rendez-vous/{$appointment->id}");
        $response->assertStatus(200);
        $response->assertViewIs('rendez-vous.show');
    }

    public function test_authenticated_user_can_edit_own_appointment()
    {
        $appointment = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $this->contact->id,
            'activite_id' => $this->activite->id,
        ]);

        $response = $this->actingAs($this->user)->get("/rendez-vous/{$appointment->id}/edit");
        $response->assertStatus(200);
        $response->assertViewIs('rendez-vous.edit');
    }

    public function test_authenticated_user_can_update_own_appointment()
    {
        $appointment = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $this->contact->id,
            'activite_id' => $this->activite->id,
        ]);

        $tomorrow = Carbon::tomorrow()->addDay();

        $updateData = [
            'titre' => 'Consultation mise à jour',
            'description' => 'Description mise à jour',
            'date_debut' => $tomorrow->format('Y-m-d'),
            'date_fin' => $tomorrow->format('Y-m-d'),
            'heure_debut' => '14:00',
            'heure_fin' => '15:00',
            'contact_id' => $this->contact->id,
            'activite_id' => $this->activite->id,
        ];

        $response = $this->actingAs($this->user)->put("/rendez-vous/{$appointment->id}", $updateData);

        $response->assertRedirect(route('rendez-vous.show', $appointment));
        $this->assertDatabaseHas('rendez_vous', [
            'id' => $appointment->id,
            'titre' => 'Consultation mise à jour',
        ]);
    }

    public function test_authenticated_user_can_delete_own_appointment()
    {
        $appointment = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $this->contact->id,
            'activite_id' => $this->activite->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/rendez-vous/{$appointment->id}");

        $response->assertRedirect(route('rendez-vous.index'));
        $this->assertDatabaseMissing('rendez_vous', ['id' => $appointment->id]);
    }

    public function test_user_cannot_view_other_users_appointments()
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

        $response = $this->actingAs($this->user)->get("/rendez-vous/{$appointment->id}");
        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_other_users_appointments()
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

        $response = $this->actingAs($this->user)->delete("/rendez-vous/{$appointment->id}");
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_appointments()
    {
        $response = $this->get('/rendez-vous');
        $response->assertRedirect('/login');
    }
}
