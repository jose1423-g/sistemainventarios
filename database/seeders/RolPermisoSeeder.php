<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permisos;
use App\Models\Roles;

class RolPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permisos = [
            ['nombre_permiso' => 'visualizar'],
            ['nombre_permiso' => 'insertar'],
            ['nombre_permiso' => 'editar'],
            ['nombre_permiso' => 'eliminar'],
        ];

        Permisos::insert($permisos);

        $roles = [
            ['nombre_rol' => 'Admin'],
            ['nombre_rol' => 'Editor'],
            ['nombre_rol' => 'Insertor'],
        ];

        Roles::insert($roles);
        
        //rol_permiso

        $admin = Roles::where('nombre_rol', 'Admin')->first();
        $editor = Roles::where('nombre_rol', 'Editor')->first();
        $insertor = Roles::where('nombre_rol', 'Insertor')->first();

        $permisos = Permisos::all();

        $admin->permisos()->sync($permisos->pluck('id'));

        $editor->permisos()->sync($permisos->whereIn('nombre_permiso',['visualizar','insertar','editar'])->pluck('id'));

        $insertor->permisos()->sync($permisos->whereIn('nombre_permiso',['visualizar','insertar'])->pluck('id'));
    }
}
