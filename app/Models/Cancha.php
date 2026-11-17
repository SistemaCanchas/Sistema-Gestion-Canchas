<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    protected $fillable = [
        'nombre',
        'tipo',
        'precio_hora',
        'incluye_balon_ac',
        'descripcion',
        'activa',
    ];

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class);
    }
}
