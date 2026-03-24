<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'city',
        'contact_phone',
        'settings',
        'is_active',
    ];

    /**
     * Conversión automática de tipos de datos.
     * Esto hace que 'settings' se guarde como JSON en MySQL, 
     * pero en PHP lo manejemos como un Array normal.
     */
    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /* =========================================================================
       RELACIONES
       ========================================================================= */

    /**
     * Relación con los Administradores (Tabla pivote 'club_user')
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('access_level')
                    ->withTimestamps();
    }

    /**
     * Un club tiene muchas canchas.
     */
    public function courts()
    {
        return $this->hasMany(Court::class);
    }

    /**
     * Un club tiene muchas reservas en su historial.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}