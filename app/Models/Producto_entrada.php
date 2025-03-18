<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto_entrada extends Model
{
    protected $table = 'producto_entrada';

    protected $fillable = [
        'fk_producto',
        'fk_entrada'
    ];
}
