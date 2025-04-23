<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salidas extends Model
{
    protected $table = 'salidas';

    protected $fillable = [
        'no_salida',
        'fk_no_compra',
        'fecha_salida',
        'fk_area',
        'personal',                
    ];
}
