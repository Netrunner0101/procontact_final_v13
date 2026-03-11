<?php

namespace App\Services;

use App\Models\Note;
use App\Models\RendezVous;
use Illuminate\Support\Collection;

class ClientPortalService
{
    public function getDernierRendezVous(int $contactId): ?RendezVous
    {
        return RendezVous::where('contact_id', $contactId)
                         ->orderBy('date_debut', 'desc')
                         ->with(['notesPartagees', 'activite', 'contact'])
                         ->first();
    }

    public function getRendezVous(int $rdvId, int $contactId): ?RendezVous
    {
        return RendezVous::where('id', $rdvId)
                         ->where('contact_id', $contactId)
                         ->with(['notesPartagees', 'activite', 'contact'])
                         ->first();
    }

    public function getToutesNotesPartagees(int $contactId): Collection
    {
        return Note::whereHas('rendezVous', function ($q) use ($contactId) {
                        $q->where('contact_id', $contactId);
                    })
                   ->where('type_note', 'partagee')
                   ->with(['rendezVous.activite'])
                   ->orderBy('created_at', 'desc')
                   ->get()
                   ->groupBy('rendez_vous_id');
    }

    public function ajouterNote(int $rdvId, int $contactId, string $contenu): Note
    {
        $rdv = RendezVous::where('id', $rdvId)
                         ->where('contact_id', $contactId)
                         ->firstOrFail();

        return Note::create([
            'rendez_vous_id' => $rdv->id,
            'titre' => 'Note client',
            'commentaire' => strip_tags(trim($contenu)),
            'type_note' => 'partagee',
            'auteur' => 'client',
            'date_create' => now(),
            'date_update' => now(),
        ]);
    }
}
