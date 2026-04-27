<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id',
    ];

    /**
     * Obtener el departamento al que pertenece la ciudad.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Obtener los clubes que están en esta ciudad.
     */
    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class);
    }

    /**
     * Obtener los usuarios que viven en esta ciudad (opcional).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
