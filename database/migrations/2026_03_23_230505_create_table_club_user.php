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
        Schema::create('club_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('club_id')->constrained()->onDelete('cascade');

            // Nivel de acceso dentro de ese club (Por si luego hay 'recepcionistas' y 'dueños')
            $table->string('access_level')->default('owner');

            $table->timestamps();

            // Un usuario no puede ser dueño del mismo club dos veces
            $table->unique(['user_id', 'club_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_user');
    }
};
