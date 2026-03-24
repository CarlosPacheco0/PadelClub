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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // Para URLs limpias: misitio.com/padel-norte
            $table->string('address');
            $table->string('city')->default('Ocaña');
            $table->string('contact_phone', 20);

            // Configuración dinámica en JSON (Horarios de apertura, reglas de cancelación, etc.)
            $table->json('settings')->nullable();

            // Control administrativo
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
