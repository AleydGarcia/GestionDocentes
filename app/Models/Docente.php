<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docente extends Model
{
    protected $fillable = [
        'nombre',
        'especialidad',
        'domicilio',
        'localidad',
        'celular',
        'estado_civil',
        'rfc',
        'curp',
        'ultimo_grado_estudios',
        'numero_pensiones',
        'clave_presupuestal',
    ];

    /**
     * Get the expedientes that belong to the docente.
     */
    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }
}
