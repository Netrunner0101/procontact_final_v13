<?php

namespace Database\Seeders;

use App\Models\Activite;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActiviteSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();

        Activite::firstOrCreate(
            ['nom' => 'Consultation juridique', 'user_id' => $admin->id],
            [
                'description' => 'Consultations en droit des affaires, contrats et litiges commerciaux.',
                'numero_telephone' => '+32 2 555 01 01',
                'email' => 'juridique@procontact.test',
            ]
        );

        Activite::firstOrCreate(
            ['nom' => 'Coaching professionnel', 'user_id' => $admin->id],
            [
                'description' => 'Accompagnement individuel pour le développement de carrière et leadership.',
                'numero_telephone' => '+32 2 555 02 02',
                'email' => 'coaching@procontact.test',
            ]
        );
    }
}
