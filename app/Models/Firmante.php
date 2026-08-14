<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firmante extends Model
{
    protected $fillable = [
        'honorifico',
        'nombre',
        'apellido',
        'cargo',
    ];
}
