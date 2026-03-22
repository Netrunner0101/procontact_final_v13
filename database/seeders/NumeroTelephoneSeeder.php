<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NumeroTelephoneSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();
        $contacts = Contact::where('user_id', $admin->id)->get();

        $phones = [
            '+32 470 12 34 56',
            '+32 471 23 45 67',
            '+32 472 34 56 78',
            '+32 473 45 67 89',
            '+32 474 56 78 90',
            '+32 475 67 89 01',
            '+32 476 78 90 12',
            '+32 477 89 01 23',
            '+32 478 90 12 34',
            '+32 479 01 23 45',
        ];

        foreach ($contacts as $index => $contact) {
            DB::table('numero_telephones')->updateOrInsert(
                ['contact_id' => $contact->id, 'numero_telephone' => $phones[$index] ?? $phones[0]],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
