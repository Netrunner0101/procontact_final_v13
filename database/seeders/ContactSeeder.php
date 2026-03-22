<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();

        $contacts = [
            ['nom' => 'Dupont',    'prenom' => 'Marie',     'rue' => 'Rue de la Loi',        'numero' => '16',  'ville' => 'Bruxelles',    'code_postal' => '1000', 'pays' => 'Belgique', 'state_client' => 'Actif',    'status_id' => 2],
            ['nom' => 'Janssens',  'prenom' => 'Pierre',    'rue' => 'Avenue Louise',         'numero' => '54',  'ville' => 'Bruxelles',    'code_postal' => '1050', 'pays' => 'Belgique', 'state_client' => 'Actif',    'status_id' => 2],
            ['nom' => 'Peeters',   'prenom' => 'Sophie',    'rue' => 'Chaussée de Waterloo',  'numero' => '102', 'ville' => 'Ixelles',      'code_postal' => '1060', 'pays' => 'Belgique', 'state_client' => 'Prospect', 'status_id' => 1],
            ['nom' => 'Maes',      'prenom' => 'Luc',       'rue' => 'Rue Haute',             'numero' => '78',  'ville' => 'Bruxelles',    'code_postal' => '1000', 'pays' => 'Belgique', 'state_client' => 'Actif',    'status_id' => 2],
            ['nom' => 'Claes',     'prenom' => 'Isabelle',  'rue' => 'Boulevard Anspach',     'numero' => '33',  'ville' => 'Bruxelles',    'code_postal' => '1000', 'pays' => 'Belgique', 'state_client' => 'Inactif',  'status_id' => 3],
            ['nom' => 'Wouters',   'prenom' => 'Thomas',    'rue' => 'Rue du Marché',         'numero' => '12',  'ville' => 'Namur',        'code_postal' => '5000', 'pays' => 'Belgique', 'state_client' => 'Prospect', 'status_id' => 4],
            ['nom' => 'Jacobs',    'prenom' => 'Charlotte', 'rue' => 'Place Saint-Lambert',   'numero' => '5',   'ville' => 'Liège',        'code_postal' => '4000', 'pays' => 'Belgique', 'state_client' => 'Actif',    'status_id' => 6],
            ['nom' => 'Mertens',   'prenom' => 'Antoine',   'rue' => 'Rue de Fer',            'numero' => '45',  'ville' => 'Namur',        'code_postal' => '5000', 'pays' => 'Belgique', 'state_client' => 'Prospect', 'status_id' => 5],
            ['nom' => 'Willems',   'prenom' => 'Émilie',    'rue' => 'Grand-Place',           'numero' => '1',   'ville' => 'Mons',         'code_postal' => '7000', 'pays' => 'Belgique', 'state_client' => 'Actif',    'status_id' => 7],
            ['nom' => 'Lambert',   'prenom' => 'Nicolas',   'rue' => 'Rue de Bruxelles',      'numero' => '88',  'ville' => 'Wavre',        'code_postal' => '1300', 'pays' => 'Belgique', 'state_client' => 'Inactif',  'status_id' => 8],
        ];

        foreach ($contacts as $data) {
            Contact::firstOrCreate(
                ['nom' => $data['nom'], 'prenom' => $data['prenom'], 'user_id' => $admin->id],
                $data + ['user_id' => $admin->id]
            );
        }
    }
}
