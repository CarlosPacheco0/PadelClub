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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->foreignId('court_id')->constrained()->onDelete('cascade');

            // Puede ser nulo si el admin crea la reserva manualmente por teléfono
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name')->nullable(); // Nombre de quien llamó
            $table->string('guest_phone')->nullable();

            // Tiempos
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            // Estados
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->decimal('total_price', 10, 2);

            $table->timestamps();
            $table->softDeletes();

            // Índice único para evitar el overbooking (doble reserva) en la misma cancha y hora
            $table->unique(['court_id', 'start_time', 'end_time', 'status'], 'prevent_double_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
