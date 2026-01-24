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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('field_id')
                ->constrained('fields');

            $table->foreignId('schedule_id')
                ->constrained('schedules');

            $table->foreignId('user_id')
                ->constrained('users');

            $table->date('date');

            $table->enum('status_reservation', ['pendiente', 'confirmada', 'cancelada', 'completada'])
                ->default('pendiente');

            $table->timestamps();

            $table->unique(['field_id', 'schedule_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
