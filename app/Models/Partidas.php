<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partidas extends Model
{
    protected $table = 'partidas';

    protected $fillable = [
        'no_partida',
        'nombre',
        'descripcion',
    ];

}
