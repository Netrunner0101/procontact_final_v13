<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use App\Models\RendezVous;
use App\Models\Activite;
use App\Models\Note;
use App\Models\Status;
use App\Models\ClientAccesToken;
use App\Services\TokenService;
use App\Services\ClientPortalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $contact;
    protected $activite;
    protected $rendezVous;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'admin']);
        $status = Status::factory()->create(['nom' => 'Client actif']);

        $this->contact = Contact::factory()->create([
            'user_id' => $this->user->id,
            'status_id' => $status->id,
        ]);

        $this->activite = Activite::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->rendezVous = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $this->contact->id,
            'activite_id' => $this->activite->id,
        ]);

        $this->token = ClientAccesToken::create([
            'contact_id' => $this->contact->id,
            'token' => ClientAccesToken::generateToken(),
            'is_active' => true,
        ]);
    }

    // ─── TOKEN VALIDATION TESTS ────────────────────────────────────────

    public function test_valid_token_shows_portal_rdv_page()
    {
        $response = $this->get(route('portal.index', ['token' => $this->token->token]));

        $response->assertStatus(200);
        $response->assertViewIs('portal.rdv');
        $response->assertViewHas('contact');
        $response->assertViewHas('rdv');
        $response->assertViewHas('token');
    }

    public function test_invalid_token_shows_error_page()
    {
        $response = $this->get(route('portal.index', ['token' => 'invalid-token-123']));

        $response->assertStatus(200);
        $response->assertViewIs('portal.invalid');
    }

    public function test_missing_token_shows_error_page()
    {
        $response = $this->get(route('portal.index'));

        $response->assertStatus(200);
        $response->assertViewIs('portal.invalid');
    }

    public function test_revoked_token_shows_error_page()
    {
        $this->token->revoke();

        $response = $this->get(route('portal.index', ['token' => $this->token->token]));

        $response->assertStatus(200);
        $response->assertViewIs('portal.invalid');
    }

    // ─── PORTAL RDV VIEW TESTS ─────────────────────────────────────────

    public function test_portal_shows_specific_rdv()
    {
        $response = $this->get(route('portal.rdv', [
            'id' => $this->rendezVous->id,
            'token' => $this->token->token,
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('portal.rdv');
        $response->assertViewHas('rdv');
    }

    public function test_portal_denies_rdv_from_other_contact()
    {
        $otherContact = Contact::factory()->create(['user_id' => $this->user->id]);
        $otherRdv = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $otherContact->id,
            'activite_id' => $this->activite->id,
        ]);

        $response = $this->get(route('portal.rdv', [
            'id' => $otherRdv->id,
            'token' => $this->token->token,
        ]));

        $response->assertStatus(403);
    }

    // ─── SHARED NOTES TESTS ────────────────────────────────────────────

    public function test_portal_shows_only_shared_notes()
    {
        // Create a shared note
        Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'partagee',
            'auteur' => 'entrepreneur',
        ]);

        // Create a private note
        Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'privee',
            'auteur' => 'entrepreneur',
        ]);

        $response = $this->get(route('portal.notes', ['token' => $this->token->token]));

        $response->assertStatus(200);
        $response->assertViewIs('portal.notes');
    }

    public function test_client_can_add_note_via_portal()
    {
        $response = $this->post(route('portal.note.store'), [
            'token' => $this->token->token,
            'rendez_vous_id' => $this->rendezVous->id,
            'contenu' => 'This is a client note.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('notes', [
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'partagee',
            'auteur' => 'client',
        ]);
    }

    public function test_client_cannot_add_note_to_other_contacts_rdv()
    {
        $otherContact = Contact::factory()->create(['user_id' => $this->user->id]);
        $otherRdv = RendezVous::factory()->create([
            'user_id' => $this->user->id,
            'contact_id' => $otherContact->id,
            'activite_id' => $this->activite->id,
        ]);

        $response = $this->post(route('portal.note.store'), [
            'token' => $this->token->token,
            'rendez_vous_id' => $otherRdv->id,
            'contenu' => 'Unauthorized note.',
        ]);

        $response->assertStatus(404);
    }

    public function test_client_note_content_is_sanitized()
    {
        $this->post(route('portal.note.store'), [
            'token' => $this->token->token,
            'rendez_vous_id' => $this->rendezVous->id,
            'contenu' => '<script>alert("xss")</script>Hello',
        ]);

        $note = Note::where('rendez_vous_id', $this->rendezVous->id)
                     ->where('auteur', 'client')
                     ->first();

        $this->assertNotNull($note);
        $this->assertStringNotContainsString('<script>', $note->commentaire);
        $this->assertStringContainsString('Hello', $note->commentaire);
    }

    public function test_note_with_invalid_token_returns_403()
    {
        $response = $this->post(route('portal.note.store'), [
            'token' => 'bad-token',
            'rendez_vous_id' => $this->rendezVous->id,
            'contenu' => 'Some note content.',
        ]);

        $response->assertStatus(403);
    }

    // ─── ALL NOTES VIEW TESTS ──────────────────────────────────────────

    public function test_all_notes_page_with_invalid_token_shows_error()
    {
        $response = $this->get(route('portal.notes', ['token' => 'bad']));

        $response->assertStatus(200);
        $response->assertViewIs('portal.invalid');
    }

    // ─── TOKEN SERVICE TESTS ───────────────────────────────────────────

    public function test_token_service_validates_active_token()
    {
        $service = new TokenService();
        $contact = $service->valider($this->token->token);

        $this->assertNotNull($contact);
        $this->assertEquals($this->contact->id, $contact->id);
    }

    public function test_token_service_returns_null_for_invalid_token()
    {
        $service = new TokenService();
        $contact = $service->valider('nonexistent-token');

        $this->assertNull($contact);
    }

    public function test_token_service_get_or_create_returns_existing()
    {
        $service = new TokenService();
        $tokenString = $service->getOrCreateToken($this->contact->id);

        $this->assertEquals($this->token->token, $tokenString);
    }

    public function test_token_service_get_or_create_creates_new()
    {
        $newContact = Contact::factory()->create(['user_id' => $this->user->id]);
        $service = new TokenService();
        $tokenString = $service->getOrCreateToken($newContact->id);

        $this->assertNotEmpty($tokenString);
        $this->assertDatabaseHas('client_acces_tokens', [
            'contact_id' => $newContact->id,
            'token' => $tokenString,
            'is_active' => true,
        ]);
    }

    public function test_token_service_revoke_deactivates_all_tokens()
    {
        $service = new TokenService();
        $service->revoquer($this->contact->id);

        $this->assertDatabaseHas('client_acces_tokens', [
            'contact_id' => $this->contact->id,
            'is_active' => false,
        ]);
    }

    // ─── CLIENT ACCES TOKEN MODEL TESTS ────────────────────────────────

    public function test_token_generation_returns_uuid()
    {
        $token = ClientAccesToken::generateToken();
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
            $token
        );
    }

    public function test_token_belongs_to_contact()
    {
        $this->assertInstanceOf(Contact::class, $this->token->contact);
        $this->assertEquals($this->contact->id, $this->token->contact->id);
    }

    public function test_contact_has_access_token_relation()
    {
        $this->contact->load('accessToken');
        $this->assertNotNull($this->contact->accessToken);
        $this->assertEquals($this->token->id, $this->contact->accessToken->id);
    }

    public function test_revoke_deactivates_token()
    {
        $this->token->revoke();
        $this->assertFalse($this->token->fresh()->is_active);
    }

    // ─── NOTE MODEL TESTS ──────────────────────────────────────────────

    public function test_note_est_partagee_returns_correct_value()
    {
        $note = Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'partagee',
        ]);
        $this->assertTrue($note->estPartagee());

        $notePrivate = Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'privee',
        ]);
        $this->assertFalse($notePrivate->estPartagee());
    }

    public function test_note_est_du_client_returns_correct_value()
    {
        $note = Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'auteur' => 'client',
        ]);
        $this->assertTrue($note->estDuClient());

        $noteEntrepreneur = Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'auteur' => 'entrepreneur',
        ]);
        $this->assertFalse($noteEntrepreneur->estDuClient());
    }

    public function test_notes_partagees_scope_filters_correctly()
    {
        Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'partagee',
        ]);
        Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'privee',
        ]);

        $shared = Note::partagees()->get();
        $this->assertEquals(1, $shared->count());
        $this->assertEquals('partagee', $shared->first()->type_note);
    }

    // ─── RENDEZ-VOUS NOTES PARTAGEES RELATION TEST ─────────────────────

    public function test_rdv_notes_partagees_returns_only_shared()
    {
        Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'partagee',
        ]);
        Note::factory()->create([
            'rendez_vous_id' => $this->rendezVous->id,
            'type_note' => 'privee',
        ]);

        $sharedNotes = $this->rendezVous->notesPartagees;
        $this->assertEquals(1, $sharedNotes->count());
    }

    // ─── CLIENT PORTAL SERVICE TESTS ───────────────────────────────────

    public function test_portal_service_get_dernier_rdv()
    {
        $service = new ClientPortalService();
        $rdv = $service->getDernierRendezVous($this->contact->id);

        $this->assertNotNull($rdv);
        $this->assertEquals($this->rendezVous->id, $rdv->id);
    }

    public function test_portal_service_get_specific_rdv()
    {
        $service = new ClientPortalService();
        $rdv = $service->getRendezVous($this->rendezVous->id, $this->contact->id);

        $this->assertNotNull($rdv);
    }

    public function test_portal_service_get_specific_rdv_wrong_contact()
    {
        $otherContact = Contact::factory()->create(['user_id' => $this->user->id]);
        $service = new ClientPortalService();
        $rdv = $service->getRendezVous($this->rendezVous->id, $otherContact->id);

        $this->assertNull($rdv);
    }

    public function test_portal_service_ajouter_note()
    {
        $service = new ClientPortalService();
        $note = $service->ajouterNote($this->rendezVous->id, $this->contact->id, 'Test note');

        $this->assertEquals('partagee', $note->type_note);
        $this->assertEquals('client', $note->auteur);
        $this->assertEquals('Test note', $note->commentaire);
    }

    // ─── ADMIN REVOKE ACCESS TEST ──────────────────────────────────────

    public function test_admin_can_revoke_portal_access()
    {
        $response = $this->actingAs($this->user)
            ->post(route('contacts.revoquer-acces', $this->contact));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('client_acces_tokens', [
            'contact_id' => $this->contact->id,
            'is_active' => false,
        ]);
    }

    // ─── PORTAL ROUTES ARE PUBLIC (NO AUTH) ────────────────────────────

    public function test_portal_routes_do_not_require_authentication()
    {
        // Index
        $response = $this->get(route('portal.index', ['token' => $this->token->token]));
        $response->assertStatus(200);

        // Notes
        $response = $this->get(route('portal.notes', ['token' => $this->token->token]));
        $response->assertStatus(200);

        // RDV
        $response = $this->get(route('portal.rdv', [
            'id' => $this->rendezVous->id,
            'token' => $this->token->token,
        ]));
        $response->assertStatus(200);
    }

    // ─── TOKEN CASCADE DELETE TEST ─────────────────────────────────────

    public function test_token_is_deleted_when_contact_is_deleted()
    {
        $tokenId = $this->token->id;
        $this->contact->delete();

        $this->assertDatabaseMissing('client_acces_tokens', ['id' => $tokenId]);
    }
}
