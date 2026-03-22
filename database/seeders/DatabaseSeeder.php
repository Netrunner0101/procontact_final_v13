<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StatusSeeder::class,
            UserSeeder::class,
            ActiviteSeeder::class,
            ContactSeeder::class,
            EmailSeeder::class,
            NumeroTelephoneSeeder::class,
            ContactActiviteSeeder::class,
            RendezVousSeeder::class,
            NoteSeeder::class,
            RappelSeeder::class,
            StatistiqueSeeder::class,
        ]);

        // Link client user to admin and first contact
        $admin = User::where('email', 'admin@procontact.test')->first();
        $client = User::where('email', 'client@procontact.test')->first();
        $firstContact = Contact::where('user_id', $admin->id)->first();

        if ($admin && $client && $firstContact) {
            $client->update([
                'admin_user_id' => $admin->id,
                'contact_id' => $firstContact->id,
            ]);
        }
    }
}
