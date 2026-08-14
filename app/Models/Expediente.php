<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expediente extends Model
{
    protected $fillable = [
        'docente_id',
        'fecha_creacion',
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
    ];

    /**
     * Get the docente that owns the expediente.
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    /**
     * Get the trámites for the expediente.
     */
    public function tramites(): HasMany
    {
        return $this->hasMany(Tramite::class);
    }
}
