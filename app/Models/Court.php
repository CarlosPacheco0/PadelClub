<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importante para no perder el historial

class Court extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'club_id',
        'name',
        'sport', // Ej: 'padel', 'futbol5', 'tenis'
        'price_per_hour',
        'features', // JSON con características adicionales
        'is_active',
    ];

    /**
     * Conversión automática de tipos de datos.
     * Convierte el JSON de la base de datos a un Array de PHP automáticamente.
     */
    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /* =========================================================================
       RELACIONES
       ========================================================================= */

    /**
     * Una cancha pertenece a un único Club.
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Una cancha tiene muchas reservas asociadas en el tiempo.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}