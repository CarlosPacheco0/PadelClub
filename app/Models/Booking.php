<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importante para las reservas

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'club_id',
        'court_id',
        'user_id',
        'guest_name',
        'guest_phone',
        'start_time',
        'end_time',
        'status',
        'total_price',
    ];

    /**
     * Conversión automática de fechas y decimales.
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    /* =========================================================================
       RELACIONES
       ========================================================================= */

    /**
     * Toda reserva pertenece obligatoriamente a un Club.
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Toda reserva ocupa una Cancha específica.
     */
    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * La reserva puede pertenecer a un Usuario registrado (Opcional, puede ser nulo si es manual)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una reserva puede tener uno o varios pagos (ej: Adelanto del 50% y luego el resto)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}