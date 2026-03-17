<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add user_id as nullable first
        Schema::table('numero_telephones', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // Step 2: Backfill user_id from the parent contact's user_id
        DB::statement('UPDATE numero_telephones SET user_id = (SELECT contacts.user_id FROM contacts WHERE contacts.id = numero_telephones.contact_id)');

        // Step 3: Make NOT NULL and add FK constraint
        Schema::table('numero_telephones', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('numero_telephones', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
