<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Areas;

class AreaController extends Controller
{
    public function Showview () {
        $areas  = Areas::all();
        return Inertia::render('Area', 
            [
                'Areas' => $areas,
            ]
        );
    }

    public function Store (Request $request) {        
        
        $request->validate([
            'area' => 'required',
        ],[
            'area.required' => 'El campo Area es obligatorio.',
        ]);

        try {

            Areas::updateOrCreate(
                ['id' => $request->id],
                [
                    'area' => $request->area, 
                    'activa' => $request->activa
                ]
            );
            return response()->json(['result' => 1, 'msg' => 'Area creada con exito']);

        } catch (\Throwable $th) {
            
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }   
    
    public function Edit ($id) {
        try {
            $data = Areas::find($id);
            return $data;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }

    public function Delete ($id) {
        try {            
            Areas::destroy($id);
            return response()->json(['result' => 1, 'msg' => 'Area Eliminada con exito']);
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }
}
