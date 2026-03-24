<?php

use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\RendezVousApiController;
use App\Http\Controllers\Api\StatistiqueApiController;
use App\Http\Controllers\Api\ExportApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Contacts CRUD
    Route::apiResource('contacts', ContactApiController::class);

    // Rendez-vous CRUD
    Route::apiResource('rendez-vous', RendezVousApiController::class)->parameters(['rendez-vous' => 'rendezVous']);

    // Statistics
    Route::get('statistics', [StatistiqueApiController::class, 'index']);

    // Export
    Route::get('export/contacts', [ExportApiController::class, 'contacts']);
});
