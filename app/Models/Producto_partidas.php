<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto_partidas extends Model
{
    protected $table = 'producto_partidas';

    protected $fillable = [
        'fk_producto',
        'fk_partida',
    ];
}
