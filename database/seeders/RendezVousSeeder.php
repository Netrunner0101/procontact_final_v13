<?php

namespace Database\Seeders;

use App\Models\Activite;
use App\Models\Contact;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Database\Seeder;

class RendezVousSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();
        $contacts = Contact::where('user_id', $admin->id)->get();
        $activites = Activite::where('user_id', $admin->id)->get();

        if ($contacts->count() < 4 || $activites->count() < 2) {
            return;
        }

        $appointments = [
            [
                'titre' => 'Consultation initiale — Dupont',
                'description' => 'Première consultation pour analyse du dossier juridique.',
                'contact_index' => 0,
                'activite_index' => 0,
                'date_debut' => '2026-04-07',
                'date_fin' => '2026-04-07',
                'heure_debut' => '09:00',
                'heure_fin' => '10:00',
            ],
            [
                'titre' => 'Séance coaching — Janssens',
                'description' => 'Session de coaching sur le leadership et la gestion d\'équipe.',
                'contact_index' => 1,
                'activite_index' => 1,
                'date_debut' => '2026-04-09',
                'date_fin' => '2026-04-09',
                'heure_debut' => '14:00',
                'heure_fin' => '15:30',
            ],
            [
                'titre' => 'Suivi dossier — Peeters',
                'description' => 'Point sur l\'avancement du contrat commercial.',
                'contact_index' => 2,
                'activite_index' => 0,
                'date_debut' => '2026-04-14',
                'date_fin' => '2026-04-14',
                'heure_debut' => '11:00',
                'heure_fin' => '11:45',
            ],
            [
                'titre' => 'Bilan coaching — Maes',
                'description' => 'Bilan trimestriel et définition des prochains objectifs.',
                'contact_index' => 3,
                'activite_index' => 1,
                'date_debut' => '2026-04-16',
                'date_fin' => '2026-04-16',
                'heure_debut' => '16:00',
                'heure_fin' => '17:00',
            ],
        ];

        foreach ($appointments as $data) {
            RendezVous::firstOrCreate(
                ['titre' => $data['titre'], 'user_id' => $admin->id],
                [
                    'user_id' => $admin->id,
                    'contact_id' => $contacts[$data['contact_index']]->id,
                    'activite_id' => $activites[$data['activite_index']]->id,
                    'description' => $data['description'],
                    'date_debut' => $data['date_debut'],
                    'date_fin' => $data['date_fin'],
                    'heure_debut' => $data['heure_debut'],
                    'heure_fin' => $data['heure_fin'],
                ]
            );
        }
    }
}
