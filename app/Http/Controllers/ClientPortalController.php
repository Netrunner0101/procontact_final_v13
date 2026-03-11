<?php

namespace App\Http\Controllers;

use App\Services\TokenService;
use App\Services\ClientPortalService;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function __construct(
        private TokenService $tokenService,
        private ClientPortalService $portalService
    ) {}

    public function index(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return view('portal.invalid');
        }

        $contact = $this->tokenService->valider($token);

        if (!$contact) {
            return view('portal.invalid');
        }

        $rdv = $this->portalService->getDernierRendezVous($contact->id);

        return view('portal.rdv', compact('contact', 'rdv', 'token'));
    }

    public function showRdv(Request $request, int $id)
    {
        $token = $request->query('token');

        if (!$token) {
            return view('portal.invalid');
        }

        $contact = $this->tokenService->valider($token);

        if (!$contact) {
            return view('portal.invalid');
        }

        $rdv = $this->portalService->getRendezVous($id, $contact->id);

        if (!$rdv) {
            abort(403, 'Acces refuse.');
        }

        return view('portal.rdv', compact('contact', 'rdv', 'token'));
    }

    public function allNotes(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return view('portal.invalid');
        }

        $contact = $this->tokenService->valider($token);

        if (!$contact) {
            return view('portal.invalid');
        }

        $notes = $this->portalService->getToutesNotesPartagees($contact->id);

        return view('portal.notes', compact('contact', 'notes', 'token'));
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'rendez_vous_id' => 'required|integer',
            'contenu' => 'required|string|min:1|max:2000',
        ]);

        $contact = $this->tokenService->valider($request->token);

        if (!$contact) {
            return response()->json(['error' => 'Token invalide'], 403);
        }

        $this->portalService->ajouterNote(
            $request->rendez_vous_id,
            $contact->id,
            $request->contenu
        );

        return back()->with('success', 'Votre note a ete ajoutee.');
    }
}
