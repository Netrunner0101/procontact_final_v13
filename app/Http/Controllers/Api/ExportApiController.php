<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ExportApiController extends Controller
{
    public function contacts()
    {
        $userId = Auth::id();
        $contacts = Contact::where('user_id', $userId)
            ->with(['emails', 'numeroTelephones'])
            ->get();

        $output = fopen('php://temp', 'r+');
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, ['Nom', 'Prénom', 'Email', 'Téléphone', 'Ville', 'Date création'], ';');
        foreach ($contacts as $contact) {
            fputcsv($output, [
                $contact->nom,
                $contact->prenom,
                $contact->emails->first()->email ?? '',
                $contact->numeroTelephones->first()->numero_telephone ?? '',
                $contact->ville ?? '',
                $contact->created_at->format('d/m/Y'),
            ], ';');
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="contacts_export.csv"');
    }
}
