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
        Schema::create('rate_management', function (Blueprint $table) {
            $table->id();
            
            // 1 = Lunes, 7 = Domingo (Estándar ISO-8601)
            $table->unsignedTinyInteger('day_week'); 
            
            $table->time('start_time');
            $table->time('end_time');
            
            // Usamos decimal para precisión en precios de moneda
            $table->decimal('price', 8, 2)->default(0.00);
            
            // Para saber si el horario está disponible o ya reservado
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            // Índice para mejorar la velocidad de búsqueda por rangos
            $table->index(['day_week', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_management');
    }
};
