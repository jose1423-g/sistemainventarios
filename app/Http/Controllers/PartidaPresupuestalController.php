<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Partidas;
use PhpParser\Node\Stmt\TryCatch;

class PartidaPresupuestalController extends Controller
{
    public function Showview () {
        $partida = Partidas::all();
        return Inertia::render('PartidaPresupuestal', ['partida' => $partida]);

    }   
    
    public function Store (Request $request){
        // return $request;

        $request->validate([
            'no_partida' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required'
        ], [
            'no_partida.required' => 'El campo No. Partida es requerido',
            'nombre.required' => 'El campo Nombre es requerido',
            'descripcion.required' => 'El campo Descripcion es requerido'
        ]);

        try{

        $partidas = Partidas::updateOrCreate(
            ['id'=>$request->id],
            [
                'no_partida' =>$request->no_partida,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]

        );

        return response()->json(['result' =>1, 'msg'=>'Partida presupuestal creada con exito']);

    }catch(\Throwable $th){

        return response()->json(['result' =>0, 'msg'=>'Ups algo salio mal']);
    }
    }   

    public function Edit ($id) {

    }

    public function Delete ($id) {
        
    }

}
