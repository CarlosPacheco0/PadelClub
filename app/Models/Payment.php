<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method', // 'cash', 'transfer', 'credit_card', 'platform'
        'status',         // 'pending', 'completed', 'failed', 'refunded'
        'transaction_reference',
    ];

    /**
     * Conversión automática de tipos de datos.
     * Asegura que el monto siempre se trate como un número decimal en PHP.
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /* =========================================================================
       RELACIONES
       ========================================================================= */

    /**
     * Un pago pertenece obligatoriamente a una Reserva específica.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}