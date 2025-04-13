<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'no_entrada',
        'nombre',
        'no_partida',
        'descripcion',
        'stock',
        'unidad',
        'precio',
        'img',
    ];
}
