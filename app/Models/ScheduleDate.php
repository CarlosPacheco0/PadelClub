<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDate extends Model
{
    use HasFactory;

    protected $table = 'schedule_dates';


    protected function casts()
    {
        return [
            'schedule_id' => 'integer',
            'date'   => 'date:Y-m-d'
        ];
    }

    // No se cuenta con la columna de fecha de actualización
    public $timestamps = false;


    protected $fillable = [
        'schedule_id',
        'date'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
