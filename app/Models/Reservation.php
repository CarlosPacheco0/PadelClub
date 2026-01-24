<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected function casts()
    {
        return [
            'date' => 'date',
        ];
    }


    protected $fillable = [
        'field_id',
        'schedule_id',
        'user_id',
        'date',
        'status_reservation'
    ];

    // Relaciones
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
