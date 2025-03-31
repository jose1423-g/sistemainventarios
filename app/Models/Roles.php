<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

    protected $fillable = ['nombre_rol'];

    public function permisos(){
        return $this->belongsToMany(Permisos::class, 'rol_permiso', 'fk_rol', 'fk_permiso');
    }
}
