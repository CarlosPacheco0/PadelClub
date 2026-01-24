<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';


    protected function casts()
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time'   => 'datetime:H:i'
        ];
    }


    protected $fillable = [
        'start_time',
        'end_time',
        'status',
    ];

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(Reservation::class);
    }
}
