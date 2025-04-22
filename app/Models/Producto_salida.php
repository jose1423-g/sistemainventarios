<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto_salida extends Model
{
    protected $table = 'producto_salida';

    protected $fillable = [
        'fk_producto',
        'fk_salida',
        'cantidad',
    ];
}
