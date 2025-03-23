<?php

namespace App\Http\Controllers;
use App\Models\Personal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonalController extends Controller
{
    public function Showview () {

        $personal = Personal::from('personal as t1')
        ->leftJoin('areas as t2', 't1.area', '=', 't2.id')
        ->get();

        return Inertia::render('Personal', 
            [
                'Personal' => $personal
            ]
        );
    }

    public function Store (Request $request) {        
        
        $request->validate([
            'nombre' => 'required',
            'area' => 'required',
        ],[
            'nombre.required' => 'El campo nombre es obligatorio.',
            'area.required' => 'El campo area es obligatorio.',
        ]);

        try {

            Personal::updateOrCreate(
                ['id' => $request->id],
                [
                    'nombre' => $request->nombre, 
                    'area' => $request->area,
                    'activo' => $request->activo
                ]
            );
            return response()->json(['result' => 1, 'msg' => 'Personal creado con exito']);

        } catch (\Throwable $th) {
            
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }        
    }
    
    public function Edit ($id) {
        try {
            // $data = Personal::find($id);
            $personal = Personal::from('personal as t1')
            ->leftJoin('areas as t2', 't1.area', '=', 't2.id')
            ->select(
                't1.id',
                't1.nombre',
                't1.area as area_id',
                't1.activo',
                't2.area'
            )
            ->where('t1.id', $id)
            ->first();
            return $personal;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }

    public function Delete ($id) {
        try {            
            Personal::destroy($id);
            return response()->json(['result' => 1, 'msg' => 'Personal Eliminado con exito']);
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }

}
