<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEquipamiento extends Model
{
    protected $fillable = [
        'reservacion_id',
        'equipamiento_id',
        'cantidad',
        'precio_unitario',
        'es_cortesia',
    ];

    public function reservacion()
    {
        return $this->belongsTo(Reservacion::class);
    }

    public function equipamiento()
    {
        return $this->belongsTo(Equipamiento::class);
    }
}
