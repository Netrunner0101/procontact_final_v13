<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactApiController extends Controller
{
    public function index()
    {
        $contacts = Contact::with(['status', 'emails', 'numeroTelephones'])
            ->where('user_id', Auth::id())
            ->get();

        return response()->json(['data' => $contacts]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'rue' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:50',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|max:255',
            'state_client' => 'nullable|string|max:255',
            'status_id' => 'nullable|exists:statuses,id',
        ]);

        $validated['user_id'] = Auth::id();
        $contact = Contact::create($validated);

        // Create email record if provided
        if (!empty($validated['email'])) {
            $contact->emails()->create(['email' => $validated['email']]);
        }

        // Create phone record if provided
        if (!empty($validated['telephone'])) {
            $contact->numeroTelephones()->create(['numero_telephone' => $validated['telephone']]);
        }

        $contact->load(['status', 'emails', 'numeroTelephones']);

        return response()->json($contact, 201);
    }

    public function show(Contact $contact)
    {
        if ($contact->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $contact->load(['status', 'emails', 'numeroTelephones']);

        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        if ($contact->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'rue' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:50',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|max:255',
            'state_client' => 'nullable|string|max:255',
            'status_id' => 'nullable|exists:statuses,id',
        ]);

        $contact->update($validated);
        $contact->load(['status', 'emails', 'numeroTelephones']);

        return response()->json($contact);
    }

    public function destroy(Contact $contact)
    {
        if ($contact->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $contact->delete();

        return response()->json(null, 204);
    }
}
