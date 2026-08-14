<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Escuela extends Model
{
    protected $fillable = [
        'nombre',
        'clave',
        'localidad',
        'director',
    ];

    /**
     * Get the trámites for the escuela.
     */
    public function tramites(): HasMany
    {
        return $this->hasMany(Tramite::class);
    }
}
