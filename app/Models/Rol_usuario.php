<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol_usuario extends Model
{
    protected $table = 'rol_usuario';
    protected $fillable = [
        'fk_rol',
        'fk_usuario',
    ];
}
