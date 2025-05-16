<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    protected $table = 'proveedores';
    
        protected $fillable = [
            'nombre',
            'razon_social',
            'rfc',
            'telefono'
        ];
}
