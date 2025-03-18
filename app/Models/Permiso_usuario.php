<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso_usuario extends Model
{
    protected $table = 'permiso_usuario';

    protected $fillable = [
        'fk_permiso',
        'fk_usuario',
    ];
}
