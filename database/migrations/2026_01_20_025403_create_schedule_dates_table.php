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
        Schema::create('schedule_dates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('schedule_id');
            $table->date('date');

            // $table->timestamps();
            $table->timestamp('created_at')->useCurrent();

            // Evitar duplicados
            $table->unique(['schedule_id', 'date']);

            // Relación
            $table->foreign('schedule_id')
                ->references('id')
                ->on('schedules')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_dates');
    }
};
