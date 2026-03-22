<?php

namespace Database\Seeders;

use App\Models\Activite;
use App\Models\Note;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();
        $rendezVous = RendezVous::where('user_id', $admin->id)->get();
        $activites = Activite::where('user_id', $admin->id)->get();

        if ($rendezVous->count() < 4 || $activites->count() < 2) {
            return;
        }

        $notes = [
            [
                'titre' => 'Points clés consultation Dupont',
                'commentaire' => 'Le client souhaite revoir les clauses du contrat de bail. Préparer un projet de modification pour la prochaine séance.',
                'rdv_index' => 0,
                'activite_index' => 0,
                'is_shared_with_client' => true,
            ],
            [
                'titre' => 'Objectifs coaching Janssens',
                'commentaire' => 'Travailler sur la communication assertive et la délégation. Le client progresse bien sur la gestion du stress.',
                'rdv_index' => 1,
                'activite_index' => 1,
                'is_shared_with_client' => false,
            ],
            [
                'titre' => 'Avancement dossier Peeters',
                'commentaire' => 'Contrat commercial en cours de rédaction. Attente de retour du partenaire avant validation finale.',
                'rdv_index' => 2,
                'activite_index' => 0,
                'is_shared_with_client' => true,
            ],
            [
                'titre' => 'Bilan trimestriel Maes',
                'commentaire' => 'Bons résultats sur les objectifs fixés. Proposer un plan d\'action pour le prochain trimestre.',
                'rdv_index' => 3,
                'activite_index' => 1,
                'is_shared_with_client' => false,
            ],
        ];

        foreach ($notes as $data) {
            Note::firstOrCreate(
                ['titre' => $data['titre'], 'user_id' => $admin->id],
                [
                    'user_id' => $admin->id,
                    'rendez_vous_id' => $rendezVous[$data['rdv_index']]->id,
                    'activite_id' => $activites[$data['activite_index']]->id,
                    'commentaire' => $data['commentaire'],
                    'is_shared_with_client' => $data['is_shared_with_client'],
                    'date_create' => now(),
                    'date_update' => now(),
                ]
            );
        }
    }
}
