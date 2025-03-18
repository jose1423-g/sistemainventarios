<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol_permiso extends Model
{
    protected $table = 'rol_permiso';

    protected $fillable = [
        'fk_rol',
        'fk_permiso',
    ];
}
