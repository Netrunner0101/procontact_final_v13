<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Dashboard;
use App\Models\User;
use App\Models\Contact;
use App\Models\RendezVous;
use App\Models\Activite;
use App\Models\Note;
use App\Models\Rappel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'role_id' => 1
        ]);
    }

    /** @test */
    public function dashboard_component_can_be_rendered()
    {
        $this->actingAs($this->user);

        Livewire::test(Dashboard::class)
            ->assertStatus(200)
            ->assertSee('Tableau de Bord');
    }

    /** @test */
    public function dashboard_loads_statistics_correctly()
    {
        $this->actingAs($this->user);

        // Create test data
        $activite = Activite::factory()->create(['user_id' => $this->user->id]);
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        
        RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
            'date_debut' => now()->addDay(),
            'heure_debut' => '09:00'
        ]);

        Note::factory()->create([
            'user_id' => $this->user->id,
            'activite_id' => $activite->id
        ]);

        Livewire::test(Dashboard::class)
            ->assertSet('totalContacts', 1)
            ->assertSet('totalAppointments', 1)
            ->assertSet('totalNotes', 1);
    }

    /** @test */
    public function dashboard_loads_upcoming_appointments()
    {
        $this->actingAs($this->user);

        $activite = Activite::factory()->create(['user_id' => $this->user->id]);
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        
        $appointment = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $contact->id,
            'activite_id' => $activite->id,
            'date_debut' => now()->addDay(),
            'heure_debut' => '09:00',
            'titre' => 'Test Appointment'
        ]);

        Livewire::test(Dashboard::class)
            ->call('loadUpcomingAppointments')
            ->assertSee('Test Appointment');
    }

    /** @test */
    public function dashboard_refresh_functionality_works()
    {
        $this->actingAs($this->user);

        Livewire::test(Dashboard::class)
            ->call('refreshData')
            ->assertEmitted('refreshed');
    }
}
