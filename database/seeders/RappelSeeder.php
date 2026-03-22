<?php

namespace Database\Seeders;

use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RappelSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@procontact.test')->first();
        $rendezVous = RendezVous::where('user_id', $admin->id)->get();

        if ($rendezVous->count() < 2) {
            return;
        }

        $rappels = [
            [
                'rendez_vous_id' => $rendezVous[0]->id,
                'date_rappel' => '2026-04-06 09:00:00',
                'frequence' => '1 jour avant',
            ],
            [
                'rendez_vous_id' => $rendezVous[1]->id,
                'date_rappel' => '2026-04-09 08:00:00',
                'frequence' => 'Le jour même',
            ],
        ];

        foreach ($rappels as $data) {
            DB::table('rappels')->updateOrInsert(
                [
                    'user_id' => $admin->id,
                    'rendez_vous_id' => $data['rendez_vous_id'],
                    'frequence' => $data['frequence'],
                ],
                [
                    'user_id' => $admin->id,
                    'date_rappel' => $data['date_rappel'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
