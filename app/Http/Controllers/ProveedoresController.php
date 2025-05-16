<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedores;
use Inertia\Inertia;

class ProveedoresController extends Controller
{
    public function Index () {
        $proveedores = Proveedores::all();
        return Inertia::render('Proveedores',
            [
                'Proveedores' => $proveedores,
            ]
        );
    }


    public function Store (Request $request) {

        $request->validate([
            'nombre' => 'required',
            'razon_social' => 'required',
        ],[
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'razon_social.required' => 'El campo Razon social es obligatorio.',
        ]);

        try {

            Proveedores::updateOrCreate(
                ['id' => $request->id],
                [
                    'nombre' => $request->nombre, 
                    'razon_social' => $request->razon_social,
                    'rfc' => $request->rfc,
                    'telefono' => $request->telefono,
                ]
            );
            return response()->json(['result' => 1, 'msg' => 'Proveedor creado con exito']);

        } catch (\Throwable $th) {
            return $th;
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
    }

    public  function Edit ($id) {
        try {
            $data = Proveedores::find($id);
            return $data;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
    }

    public function Delete ($id) {
        try {            
            Proveedores::destroy($id);
            return response()->json(['result' => 1, 'msg' => 'Proveedor Eliminado con exito']);
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }

}
