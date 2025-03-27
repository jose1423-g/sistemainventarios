<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Roles;

class UsuariosController extends Controller
{
    public function Showview () {
        $user = User::select()
        ->join('roles', 'users.id', '=', 'roles.id')
        ->select('users.id', 'users.name', 'users.email', 'roles.nombre_rol as nombre_rol')
        ->get();
        
        return Inertia::render('Usuarios', ['users' => $user]);
    }

    public function Store (Request $request) {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],[
            'name.required' => 'El campo nombre es requerido',
            'email.required' => 'El campo email es requerido',
            'password.required' => 'El campo contraseña es requerido',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ],[
            'id' => 'required|exists:roles,id',
        ]);
        try{
        $user = User::updateOrCreate(
            ['id'=>$request->id],
            [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            ]
        );
        $roles = Roles::updateOrCreate(
            ['id'=>$request->id],
            [
            'nombre_rol' => $request->nombre_rol,
            ]
        );
        return response()->json(['result' =>1, 'msg'=>'Usuario creado correctamente']);
    } catch(\Throwable $th){
        return response()->json(['result' =>0, 'msg'=>'Ups algo salio mal']);
    }  
}
    
    public function Edit ($id) {
        try{
            $data = User::find($id);
            return $data;
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
