<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'phone',
        'email',
    ];

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(Reservation::class);
    }
}
