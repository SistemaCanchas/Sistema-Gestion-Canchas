<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalizacion extends Model
{
    protected $table = 'penalizaciones';

    protected $fillable = [
        'reservacion_id',
        'tipo_balon',
        'monto_multa',
        'descripcion',
        'pagada',
    ];

    public function reservacion()
    {
        return $this->belongsTo(Reservacion::class);
    }
}
