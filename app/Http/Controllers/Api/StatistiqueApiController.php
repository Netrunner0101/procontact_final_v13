<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Activite;
use App\Models\RendezVous;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatistiqueApiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'contacts' => Contact::where('user_id', $userId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'rendez_vous' => RendezVous::where('user_id', $userId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        return response()->json([
            'contacts_count' => Contact::where('user_id', $userId)->count(),
            'appointments_count' => RendezVous::where('user_id', $userId)->count(),
            'activities_count' => Activite::where('user_id', $userId)->count(),
            'monthly_stats' => $monthlyStats,
        ]);
    }
}
