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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            // 1 = Lunes, 7 = Domingo (ISO-8601)
            $table->tinyInteger('day_of_week');

            // Formato H:i:s (Ej: 08:00:00)
            $table->time('start_time');
            $table->time('end_time');

            // Precio con 2 decimales
            $table->decimal('price', 10, 2);

            // Opcional: Si quieres tarifas distintas por tipo de cancha (Techada/Aire libre)
            // $table->foreignId('court_type_id')->nullable(); 

            $table->timestamps();

            // IMPORTANTE: Índice para búsquedas rápidas al reservar
            $table->index(['day_of_week', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
