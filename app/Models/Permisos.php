<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    protected $table = 'permisos';

    protected $fillable = ['nombre_permiso',];

    public function roles(){
        return $this->belongsToMany(Roles::class, 'rol_permiso', 'fk_permiso','fk_rol');
    }
}
