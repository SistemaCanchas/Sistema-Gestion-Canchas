<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'reservacions';

    protected $fillable = [
        'user_id',
        'cancha_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'subtotal_cancha',
        'subtotal_equipo',
        'total_penalizaciones',
        'total_final',
        'estado',
        'notas',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cancha()
    {
        return $this->belongsTo(Cancha::class);
    }

    public function equipamientos()
    {
        return $this->hasMany(DetalleEquipamiento::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    public function penalizaciones()
    {
        return $this->hasMany(Penalizacion::class);
    }
}
