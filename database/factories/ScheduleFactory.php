<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        static $hora = 8; // empieza a las 08:00

        $horaInicio = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00:00';
        $horaFin    = str_pad($hora + 1, 2, '0', STR_PAD_LEFT) . ':00:00';

        $hora++;

        if ($hora >= 18) {
            $hora = 8; // reinicia
        }

        return [
            'start_time' => $horaInicio,
            'end_time' => $horaFin,
            'status' => $this->faker->boolean()
        ];

    }
}
