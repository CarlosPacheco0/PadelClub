<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar de forma masiva.
     * Aquí añadimos 'phone' y 'role' que vienen de nuestra migración.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    /**
     * Los atributos que deben ocultarse (por seguridad).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /* =========================================================================
       RELACIONES (Base de Datos)
       ========================================================================= */

    /**
     * Relación Admin-Club: Un usuario (admin_club) puede gestionar uno o varios clubes.
     * Se usa belongsToMany porque pasan por la tabla pivote 'club_user'.
     */
    public function clubs()
    {
        return $this->belongsToMany(Club::class)
                    ->withPivot('access_level') // Trae el rol específico dentro del club
                    ->withTimestamps();
    }

    /**
     * Relación Deportista-Reserva: Un usuario final tiene muchas reservas en su historial.
     */
    public function bookings()
    {
        return $this->hasMany(booking::class);
    }


    /* =========================================================================
       MÉTODOS AUXILIARES (Helpers para Seguridad y Vistas)
       ========================================================================= */

    /**
     * ¿Es el dueño absoluto de la plataforma?
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * ¿Es el dueño/administrador de un escenario deportivo?
     */
    public function isAdminClub(): bool
    {
        return $this->role === 'admin_club';
    }

    /**
     * ¿Es un deportista estándar que busca reservar?
     */
    public function isUsuario(): bool
    {
        return $this->role === 'usuario';
    }
}
