<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'reservacion_id',
        'monto',
        'metodo_pago',
        'notas_cobro',
        'fecha_pago',
    ];

    public function reservacion()
    {
        return $this->belongsTo(Reservacion::class);
    }
}
