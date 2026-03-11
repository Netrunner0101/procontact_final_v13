<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_acces_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->string('token', 255)->unique();
            $table->timestamp('date_creation')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->index('token');
            $table->index('contact_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_acces_tokens');
    }
};
