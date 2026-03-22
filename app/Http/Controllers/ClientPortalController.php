<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientPortalController extends Controller
{
    /**
     * Display the client portal dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $upcomingAppointments = $user->visibleRendezVous()
            ->with(['contact', 'activite'])
            ->where('date_debut', '>=', now()->toDateString())
            ->orderBy('date_debut', 'asc')
            ->orderBy('heure_debut', 'asc')
            ->limit(5)
            ->get();

        $pastAppointmentsCount = $user->visibleRendezVous()
            ->where('date_debut', '<', now()->toDateString())
            ->count();

        $totalAppointmentsCount = $user->visibleRendezVous()->count();

        return view('portal.dashboard', compact(
            'upcomingAppointments',
            'pastAppointmentsCount',
            'totalAppointmentsCount'
        ));
    }

    /**
     * Display all appointments for the client.
     */
    public function appointments(Request $request)
    {
        $user = Auth::user();

        $query = $user->visibleRendezVous()->with(['contact', 'activite', 'notes']);

        if ($request->has('status')) {
            $now = now();
            switch ($request->status) {
                case 'upcoming':
                    $query->where('date_debut', '>=', $now->toDateString());
                    break;
                case 'past':
                    $query->where('date_debut', '<', $now->toDateString());
                    break;
            }
        }

        $appointments = $query->orderBy('date_debut', 'desc')
            ->orderBy('heure_debut', 'desc')
            ->paginate(15);

        return view('portal.appointments', compact('appointments'));
    }

    /**
     * Display the specified appointment with notes.
     */
    public function showAppointment(RendezVous $rendezVous)
    {
        $user = Auth::user();

        $canAccess = $user->visibleRendezVous()
            ->where('id', $rendezVous->id)
            ->exists();

        if (!$canAccess) {
            abort(403, 'You do not have access to this appointment.');
        }

        $rendezVous->load(['contact', 'activite', 'rappels', 'notes']);

        return view('portal.appointment', compact('rendezVous'));
    }

    /**
     * Store a new note for an appointment.
     */
    public function storeNote(Request $request, RendezVous $rendezVous)
    {
        $user = Auth::user();

        // Verify access to appointment
        $canAccess = $user->visibleRendezVous()
            ->where('id', $rendezVous->id)
            ->exists();

        if (!$canAccess) {
            abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'commentaire' => 'required|string',
        ]);

        Note::create([
            'user_id' => $user->id,
            'rendez_vous_id' => $rendezVous->id,
            'titre' => $validated['titre'],
            'commentaire' => $validated['commentaire'],
            'is_shared_with_client' => true,
            'date_create' => now(),
        ]);

        return redirect()
            ->route('client.portal.appointment', $rendezVous)
            ->with('success', 'Note added successfully.');
    }

    /**
     * Update a note (only own notes).
     */
    public function updateNote(Request $request, RendezVous $rendezVous, Note $note)
    {
        $user = Auth::user();

        // Verify ownership
        if ($note->user_id !== $user->id) {
            abort(403, 'You can only edit your own notes.');
        }

        // Verify note belongs to this appointment
        if ($note->rendez_vous_id !== $rendezVous->id) {
            abort(404);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'commentaire' => 'required|string',
        ]);

        $note->update([
            'titre' => $validated['titre'],
            'commentaire' => $validated['commentaire'],
            'date_update' => now(),
        ]);

        return redirect()
            ->route('client.portal.appointment', $rendezVous)
            ->with('success', 'Note updated successfully.');
    }

    /**
     * Delete a note (only own notes).
     */
    public function destroyNote(RendezVous $rendezVous, Note $note)
    {
        $user = Auth::user();

        // Verify ownership
        if ($note->user_id !== $user->id) {
            abort(403, 'You can only delete your own notes.');
        }

        // Verify note belongs to this appointment
        if ($note->rendez_vous_id !== $rendezVous->id) {
            abort(404);
        }

        $note->delete();

        return redirect()
            ->route('client.portal.appointment', $rendezVous)
            ->with('success', 'Note deleted successfully.');
    }
}
