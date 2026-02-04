<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $table = 'rate_management';


    protected function casts()
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time'   => 'datetime:H:i'
        ];
    }


    protected $fillable = [
        'day_week',
        'start_time',
        'end_time',
        'price',
    ];
}
