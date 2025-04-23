<?php

namespace App\Http\Controllers;
use App\Models\Personal;
use App\Models\Personal_area;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonalController extends Controller
{
    public function Showview () {

        $personal = Personal::from('personal as t1')
        ->leftJoin('personal_area as t2', 't1.id', '=', 't2.fk_personal')
        ->leftJoin('areas as t3', 't2.fk_area', '=', 't3.id')
        ->select(
            't1.id', 
            't1.nombre', 
            't3.area',
            't1.activo',
        )
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
            'fk_area' => 'required',
        ],[
            'nombre.required' => 'El campo nombre es obligatorio.',
            'fk_area.required' => 'El campo area es obligatorio.',
        ]);

        try {

            $personal = Personal::updateOrCreate(
                ['id' => $request->id],
                [
                    'nombre' => $request->nombre,
                    'activo' => $request->activo
                ]
            );

            $personal_area = Personal_area::where('fk_area', $request->fk_area)->first();
            if ($personal_area) {
                return response()->json(['result' => 0, 'msg' => 'El area ya esta asignada']);
            }

            Personal_area::updateOrCreate(
                [
                    'fk_personal' => $personal->id,
                    'fk_area' => $request->fk_area,
                ],
                [
                    'fk_personal' => $personal->id,
                    'fk_area' => $request->fk_area,
                ]
            );

            return response()->json(['result' => 1, 'msg' => 'Personal creado con exito']);

        } catch (\Throwable $th) {
            return $th;
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }        
    }
    
    public function Edit ($id) {
        try {
            $personal = Personal::from('personal as t1')
            ->leftJoin('personal_area as t2', 't1.id', '=', 't2.fk_personal')
            ->leftJoin('areas as t3', 't2.fk_area', '=', 't3.id')
            ->select(
                't1.id',
                't1.nombre',
                't3.id as area_id',
                't1.activo',
                't3.area'
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
            
            Personal_area::where('fk_personal', $id)->delete();
            Personal::destroy($id);
            return response()->json(['result' => 1, 'msg' => 'Personal Eliminado con exito']);
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }

}