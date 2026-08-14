<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    protected $fillable = ['tramite_id', 'nombre_archivo', 'ruta', 'fecha_carga'];

    protected $casts = ['fecha_carga' => 'date'];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }
}
