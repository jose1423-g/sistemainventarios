<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entradas extends Model
{
    protected $table = 'entradas';

    protected $fillable = [
        'no_orden',
        'proveedor',
        'fecha_compra',
        'fecha_entrada',
        'area_solicitante',
        'numero_requisicion',
        'cantidad_piezas',
        'precio_unitario',
        'IVA'
    ];
}
