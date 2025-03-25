<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol_usuario extends Model
{
    private $table = 'rol_usuario';
    private $fillable = [
        'fk_rol',
        'fk_usuario',
    ];
}
