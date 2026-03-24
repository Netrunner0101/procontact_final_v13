<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->string('statut')->default('Programmé')->after('heure_fin');
            $table->string('lieu')->nullable()->after('statut');
            $table->text('notes')->nullable()->after('lieu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropColumn(['statut', 'lieu', 'notes']);
        });
    }
};
