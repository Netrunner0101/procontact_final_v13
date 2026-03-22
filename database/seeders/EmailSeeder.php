<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();
        $contacts = Contact::where('user_id', $admin->id)->get();

        foreach ($contacts as $contact) {
            $email = strtolower($contact->prenom . '.' . $contact->nom) . '@example.be';
            $email = str_replace([' ', 'é', 'è', 'ê', 'ë', 'à', 'â', 'ô', 'ù', 'û', 'ç', 'î', 'ï'],
                                 ['',  'e', 'e', 'e', 'e', 'a', 'a', 'o', 'u', 'u', 'c', 'i', 'i'], $email);

            DB::table('emails')->updateOrInsert(
                ['contact_id' => $contact->id, 'email' => $email],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
