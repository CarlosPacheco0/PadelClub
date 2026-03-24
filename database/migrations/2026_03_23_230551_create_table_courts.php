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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('sport'); // 'padel', 'futbol5', etc.
            $table->decimal('price_per_hour', 10, 2);

            $table->json('features')->nullable(); // Ej: {"techada": true, "iluminacion": true}

            $table->boolean('is_active')->default(true);
            $table->softDeletes(); // Nunca borramos canchas reales para no romper el historial
            $table->timestamps();

            // Índice para acelerar las búsquedas en el marketplace
            $table->index(['club_id', 'sport', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
