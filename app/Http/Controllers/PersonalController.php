<?php

namespace App\Http\Controllers;
use App\Models\Personal;
use App\Models\Personal_area;
use App\Models\Areas;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonalController extends Controller
{
    public function Showview () {

        $areas = Areas::All();
        $personal = Personal::leftjoin('personal_area', 'personal.id', '=', 'personal_area.fk_personal')
        ->leftJoin('areas', 'personal_area.fk_area', '=', 'areas.id')
        ->select('personal.id','personal.nombre', 'personal.activo', 'area')
        ->get();
        return Inertia::render('Personal', ['personal' => $personal, 'areas' => $areas]);
    }

    public function Store (Request $request) {        
        
        $request->validate([
            'nombre' => 'required',
            'fk_area' => 'required|exists:areas,id',
        ],[
            'nombre.required' => 'El campo nombre es obligatorio.',
            'fk_area.required' => 'El campo area es obligatorio.',
        ]);

        try {

            $areaAsignada = Personal_area::where('fk_area', $request->fk_area)
            ->when($request->id, function($query) use ($request) {
                // excluye al usuario actual al editar
                return $query->where('fk_personal', '!=', $request->id);
            })
            ->first();

        if ($areaAsignada) {
            // Obtener nombre de usuario que ya tiene esta area
            $usuarioConArea = Personal::find($areaAsignada->fk_personal);
            $nombreArea = Areas::find($request->fk_area)->area;
            
            return response()->json([
                'result' => 0, 
                'msg' => "El área '{$nombreArea}' ya está asignada a {$usuarioConArea->nombre}. Por favor seleccione otra área."
            ]);
        }
            $user = Personal::updateOrCreate(
                ['id' => $request->id],
                [
                    'nombre' => $request->nombre, 
                    'activo' => $request->activo
                ]
            );

            $personal_area = Personal_area::updateOrCreate(
                ['fk_personal' => $user->id],
                ['fk_area' => $request->fk_area,]
            );
            return response()->json(['result' => 1, 'msg' => 'Personal creado con exito']);

        } catch (\Throwable $th) {
            
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }        
    }
    
    public function Edit ($id) {
        try {
            // $data = Personal::find($id);
            $personal = Personal::from('personal_area as t1')
            ->leftJoin('personal as t2', 't1.fk_personal', '=', 't2.id')
            ->leftjoin('areas as t3', 't1.fk_area', '=', 't3.id')
            ->select(
                't1.id',
                't2.id as personal_id',
                't2.nombre',
                't2.activo',
                't1.fk_area',
                't3.area'
            )
            ->where('t2.id', $id)
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
