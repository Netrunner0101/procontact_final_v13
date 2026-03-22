<?php

namespace Database\Seeders;

use App\Models\Activite;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactActiviteSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();
        $contacts = Contact::where('user_id', $admin->id)->get();
        $activites = Activite::where('user_id', $admin->id)->get();

        if ($activites->count() < 2) {
            return;
        }

        foreach ($contacts as $index => $contact) {
            // Each contact gets at least one activity; some get both
            $activiteId = $activites[$index % 2]->id;

            DB::table('contact_activite')->updateOrInsert(
                ['contact_id' => $contact->id, 'activite_id' => $activiteId],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // First 4 contacts get both activities
            if ($index < 4) {
                $otherActiviteId = $activites[($index + 1) % 2]->id;
                DB::table('contact_activite')->updateOrInsert(
                    ['contact_id' => $contact->id, 'activite_id' => $otherActiviteId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
