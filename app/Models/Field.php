<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(Reservation::class);
    }


    // --- METODO PROTEGIDO --

    // protected function nombre(): Attribute
    // {
    //     return Attribute::make(
    //         set: function ($value){
    //             return strtolower($value); // converte todo en minuscula
    //         },
    //         get: function ($value) {
    //             return ucfirst($value); // Convierte la primera letra en mayuscula
    //         }
    //     );
    // }


    // protected function casts(): array
    // {
    //     return [
    //         'created_at' => 'datetime'
    //     ];
    // }

}
