<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamiento extends Model
{
    protected $fillable = [
        'nombre',
        'categoria',
        'talla',
        'precio_alquiler',
        'stock_total',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleEquipamiento::class);
    }
}
