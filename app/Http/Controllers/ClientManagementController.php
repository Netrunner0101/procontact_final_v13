<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ClientManagementController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index()
    {
        $clients = Auth::user()->clients()
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $contacts = Auth::user()->contacts()
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        
        return view('admin.clients.create', compact('contacts'));
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'nullable|exists:contacts,id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'telephone' => 'nullable|string|max:20',
        ]);

        // If contact is selected, use contact information
        if ($validated['contact_id']) {
            $contact = Contact::findOrFail($validated['contact_id']);
            $validated['nom'] = $contact->nom;
            $validated['prenom'] = $contact->prenom;
            $validated['email'] = $contact->email;
            $validated['telephone'] = $contact->numerosTelephone->first()?->numero;
        }

        $client = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telephone' => $validated['telephone'],
            'role' => 'client',
            'admin_user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Display the specified client.
     */
    public function show(User $client)
    {
        // Ensure the client belongs to the current admin
        if ($client->admin_user_id !== Auth::id()) {
            abort(403);
        }

        $client->load('admin');
        
        // Get client's appointments
        $appointments = $client->visibleRendezVous()
            ->with(['contact', 'activite'])
            ->orderBy('date_heure', 'desc')
            ->paginate(10);

        return view('admin.clients.show', compact('client', 'appointments'));
    }

    /**
     * Show the form for editing the client.
     */
    public function edit(User $client)
    {
        // Ensure the client belongs to the current admin
        if ($client->admin_user_id !== Auth::id()) {
            abort(403);
        }

        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, User $client)
    {
        // Ensure the client belongs to the current admin
        if ($client->admin_user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'telephone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $updateData = [
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
        ];

        if ($validated['password']) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $client->update($updateData);

        return redirect()->route('admin.clients.show', $client)
            ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Remove the specified client.
     */
    public function destroy(User $client)
    {
        // Ensure the client belongs to the current admin
        if ($client->admin_user_id !== Auth::id()) {
            abort(403);
        }

        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
