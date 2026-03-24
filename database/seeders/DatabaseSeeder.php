<?php

namespace Database\Seeders;

use App\Models\Activite;
use App\Models\Contact;
use App\Models\Note;
use App\Models\RendezVous;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Statuses ─────────────────────────────────────
        $statusNames = [
            'Prospect', 'Client actif', 'Client inactif', 'Lead qualifié',
            'Lead non qualifié', 'En négociation', 'Fermé gagné', 'Fermé perdu',
        ];
        foreach ($statusNames as $name) {
            Status::firstOrCreate(['status_client' => $name]);
        }

        // ── Admin user ───────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@procontact.test'],
            [
                'nom' => 'ProContact',
                'prenom' => 'Admin',
                'telephone' => '+32 2 555 00 00',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'provider' => 'email',
            ]
        );

        // ── 2 Activities ────────────────────────────────
        $juridique = Activite::firstOrCreate(
            ['nom' => 'Consultation juridique', 'user_id' => $admin->id],
            [
                'description' => 'Consultations et suivis juridiques pour les clients.',
                'numero_telephone' => '+32 2 555 01 01',
                'email' => 'juridique@procontact.test',
            ]
        );

        $coaching = Activite::firstOrCreate(
            ['nom' => 'Coaching professionnel', 'user_id' => $admin->id],
            [
                'description' => 'Séances de coaching et développement professionnel.',
                'numero_telephone' => '+32 2 555 02 02',
                'email' => 'coaching@procontact.test',
            ]
        );

        $activities = [$juridique, $coaching];

        // ── 2 Contacts ──────────────────────────────────
        $contactData = [
            [
                'nom' => 'Dupont', 'prenom' => 'Marie',
                'rue' => 'Rue de la Loi', 'numero' => '42',
                'ville' => 'Bruxelles', 'code_postal' => '1000', 'pays' => 'Belgique',
                'state_client' => 'Actif', 'status_id' => 1,
                'phone' => '+32 470 12 34 56',
            ],
            [
                'nom' => 'Janssens', 'prenom' => 'Pierre',
                'rue' => 'Avenue Louise', 'numero' => '15',
                'ville' => 'Bruxelles', 'code_postal' => '1050', 'pays' => 'Belgique',
                'state_client' => 'Prospect', 'status_id' => 2,
                'phone' => '+32 471 23 45 67',
            ],
        ];

        $contacts = [];
        foreach ($contactData as $data) {
            $phone = $data['phone'];
            unset($data['phone']);

            $contact = Contact::firstOrCreate(
                ['nom' => $data['nom'], 'prenom' => $data['prenom'], 'user_id' => $admin->id],
                $data
            );
            $contacts[] = $contact;

            // Email
            $cleanPrenom = strtolower(str_replace(
                ['é', 'è', 'ê', 'ë', 'à', 'â', 'ô', 'î', 'ï', 'ù', 'û', 'ü', 'ç'],
                ['e', 'e', 'e', 'e', 'a', 'a', 'o', 'i', 'i', 'u', 'u', 'u', 'c'],
                $data['prenom']
            ));
            $cleanNom = strtolower(str_replace(
                ['é', 'è', 'ê', 'ë', 'à', 'â', 'ô', 'î', 'ï', 'ù', 'û', 'ü', 'ç'],
                ['e', 'e', 'e', 'e', 'a', 'a', 'o', 'i', 'i', 'u', 'u', 'u', 'c'],
                $data['nom']
            ));
            DB::table('emails')->updateOrInsert(
                ['contact_id' => $contact->id, 'email' => "{$cleanPrenom}.{$cleanNom}@example.be"],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // Phone
            DB::table('numero_telephones')->updateOrInsert(
                ['contact_id' => $contact->id, 'numero_telephone' => $phone],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // Contact-Activity pivots (link to both activities)
            foreach ($activities as $activity) {
                DB::table('contact_activite')->updateOrInsert(
                    ['contact_id' => $contact->id, 'activite_id' => $activity->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // ── 4 Meetings (2 per contact) ──────────────────
        $meetings = [
            [
                'contact' => $contacts[0], 'activite' => $juridique,
                'titre' => 'Consultation initiale — Dupont',
                'description' => 'Première consultation juridique avec Marie Dupont.',
                'date_debut' => '2026-04-07', 'date_fin' => '2026-04-07',
                'heure_debut' => '09:00', 'heure_fin' => '10:00',
            ],
            [
                'contact' => $contacts[0], 'activite' => $coaching,
                'titre' => 'Séance coaching — Dupont',
                'description' => 'Session de coaching professionnel avec Marie Dupont.',
                'date_debut' => '2026-04-09', 'date_fin' => '2026-04-09',
                'heure_debut' => '14:00', 'heure_fin' => '15:30',
            ],
            [
                'contact' => $contacts[1], 'activite' => $juridique,
                'titre' => 'Analyse dossier — Janssens',
                'description' => 'Analyse du dossier juridique de Pierre Janssens.',
                'date_debut' => '2026-04-14', 'date_fin' => '2026-04-14',
                'heure_debut' => '11:00', 'heure_fin' => '11:45',
            ],
            [
                'contact' => $contacts[1], 'activite' => $coaching,
                'titre' => 'Séance coaching — Janssens',
                'description' => 'Session de coaching professionnel avec Pierre Janssens.',
                'date_debut' => '2026-04-16', 'date_fin' => '2026-04-16',
                'heure_debut' => '16:00', 'heure_fin' => '17:00',
            ],
        ];

        $rendezVous = [];
        foreach ($meetings as $i => $m) {
            $rdv = RendezVous::firstOrCreate(
                ['titre' => $m['titre'], 'user_id' => $admin->id],
                [
                    'contact_id' => $m['contact']->id,
                    'activite_id' => $m['activite']->id,
                    'description' => $m['description'],
                    'date_debut' => $m['date_debut'],
                    'date_fin' => $m['date_fin'],
                    'heure_debut' => $m['heure_debut'],
                    'heure_fin' => $m['heure_fin'],
                ]
            );
            $rendezVous[] = $rdv;

            // Note for each meeting
            Note::firstOrCreate(
                ['titre' => "Notes — {$m['titre']}", 'user_id' => $admin->id],
                [
                    'rendez_vous_id' => $rdv->id,
                    'activite_id' => $m['activite']->id,
                    'commentaire' => "Compte-rendu de la réunion : {$m['titre']}.",
                    'is_shared_with_client' => $i % 2 === 0,
                    'date_create' => now(),
                    'date_update' => now(),
                ]
            );

            // Statistique for each meeting
            DB::table('statistiques')->updateOrInsert(
                [
                    'activite_id' => $m['activite']->id,
                    'rendez_vous_id' => $rdv->id,
                    'contact_id' => $m['contact']->id,
                ],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // ── Client user linked to admin + first contact ─
        $client = User::firstOrCreate(
            ['email' => 'client@procontact.test'],
            [
                'nom' => 'Test',
                'prenom' => 'Client',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'provider' => 'email',
            ]
        );
        $client->update([
            'admin_user_id' => $admin->id,
            'contact_id' => $contacts[0]->id,
        ]);
    }
}
