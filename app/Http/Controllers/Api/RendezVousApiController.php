<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\Contact;
use App\Models\Activite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RendezVousApiController extends Controller
{
    public function index()
    {
        $rendezVous = RendezVous::with(['contact', 'activite'])
            ->where('user_id', Auth::id())
            ->orderBy('date_debut', 'desc')
            ->get();

        return response()->json(['data' => $rendezVous]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'activite_id' => 'required|exists:activites,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i',
            'statut' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
        ]);

        // Verify contact and activity belong to user
        $contact = Contact::where('user_id', Auth::id())->findOrFail($validated['contact_id']);
        $activite = Activite::where('user_id', Auth::id())->findOrFail($validated['activite_id']);

        $validated['user_id'] = Auth::id();
        $rendezVous = RendezVous::create($validated);
        $rendezVous->load(['contact', 'activite']);

        return response()->json($rendezVous, 201);
    }

    public function show(RendezVous $rendezVous)
    {
        if ($rendezVous->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $rendezVous->load(['contact', 'activite', 'notes', 'rappels']);

        return response()->json($rendezVous);
    }

    public function update(Request $request, RendezVous $rendezVous)
    {
        if ($rendezVous->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'contact_id' => 'sometimes|required|exists:contacts,id',
            'activite_id' => 'sometimes|required|exists:activites,id',
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i',
            'statut' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
        ]);

        $rendezVous->update($validated);
        $rendezVous->load(['contact', 'activite']);

        return response()->json($rendezVous);
    }

    public function destroy(RendezVous $rendezVous)
    {
        if ($rendezVous->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $rendezVous->delete();

        return response()->json(null, 204);
    }
}
