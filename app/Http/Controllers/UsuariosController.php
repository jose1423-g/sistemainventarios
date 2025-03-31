<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use App\Models\Rol_usuario;
use App\Models\Roles;

class UsuariosController extends Controller
{
    public function Showview () {
        $roles = Roles::All();
        $users = User::leftJoin('rol_usuario', 'users.id' , '=', 'rol_usuario.fk_usuario')
        ->leftJoin('roles', 'rol_usuario.fk_rol', '=', 'roles.id')
        ->select('users.id', 'users.name', 'users.email','nombre_rol')
        ->get();
        return Inertia::render('Usuarios', ['users' => $users, 'roles' => $roles]);
    }

    public function Store (Request $request) {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,'. $request->id,
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => [
                $request->id ? 'nullable' : 'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
            'fk_rol' => 'required|exists:roles,id',
        ],[
            'name.required' => 'El campo nombre es requerido',
            'email.required' => 'El campo email es requerido',
            'password.required' => 'El campo contraseña es requerido',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'fk_rol.required' => 'El campo rol es requerido',
            'fk_rol.exists' => 'El rol seleccionado no es válido',
        ]);
        try{
        // $userid = Auth::user()->id; 
        $data = [
            'name' => $request->name,
            'email'=> $request->email,
        ];

        //evaluar si se llena el campo passwor, ya que si no se evalua, la contraseña se actualiza aunque no se inserte nada en el campo
        //si el campo password no se llena, no se actualiza la contraseña
        if($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        }

        $user = User::updateOrCreate(
            ['id'=>$request->id],
            $data
        );

        //si el usuario no existe se crea un registro en la tabla rol_usuario
        //si el usuario existe se actualiza el registro en la tabla rol_usuario
        $rol = Rol_usuario::updateOrCreate(
            ['fk_usuario' => $user->id],
            ['fk_rol' => $request->fk_rol]
        );
        return response()->json(['result' =>1, 'msg'=>'Usuario creado correctamente']);
    } catch(\Throwable $th){
        return response()->json(['result' =>0, 'msg'=>'Ups algo salio mal']);
    }  
}
    
    public function Edit ($id) {
        try{
            $users = User::from('rol_usuario as t1')
            ->leftJoin('users as t2', 't1.fk_usuario', '=', 't2.id')
            ->select(
                't1.id',
                't1.fk_rol',
                't1.fk_usuario',
                't2.name',
            )
            ->where('t1.id', $id)
            ->first();
            return $users;
        }catch(\Throwable $th){
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
    }

    public function Delete ($id) {
        try{
            $data = User::find($id);
            $data->delete();
            return response()->json(['result' => 1, 'msg' => 'Usuario eliminado correctamente']);
        }catch(\Throwable $th){
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal, no puedes eliminar este usuario porque esta logueado']);
        }
    }
}
