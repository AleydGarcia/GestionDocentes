<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Evidencia;

class Tramite extends Model
{
    protected $fillable = [
        'expediente_id',
        'escuela_id',
        'firmantes',
        'tipo_tramite',
        'fecha_inicio',
        'fecha_fin',
        'fecha_documento',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_documento' => 'date',
        'firmantes' => 'array',
    ];

    /**
     * Get the expediente associated with the trámite.
     */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    /**
     * Get the escuela associated with the trámite.
     */
    public function escuela(): BelongsTo
    {
        return $this->belongsTo(Escuela::class);
    }

    public function evidencias(): HasMany
    {
        return $this->hasMany(Evidencia::class);
    }
}
