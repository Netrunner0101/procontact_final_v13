<?php

namespace Database\Seeders;

use App\Models\Activite;
use App\Models\Contact;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatistiqueSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();
        $rendezVous = RendezVous::where('user_id', $admin->id)->get();

        if ($rendezVous->count() < 4) {
            return;
        }

        foreach ($rendezVous as $rdv) {
            DB::table('statistiques')->updateOrInsert(
                [
                    'activite_id' => $rdv->activite_id,
                    'rendez_vous_id' => $rdv->id,
                    'contact_id' => $rdv->contact_id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
