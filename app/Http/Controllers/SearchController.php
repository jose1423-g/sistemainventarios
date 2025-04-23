<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Areas;
use App\Models\Productos;
use App\Models\Entradas;
use App\Models\Partidas;

class SearchController extends Controller
{
    public function SearchArea ($name) {
        try {
            $areas = Areas::from('areas as t1')
            ->leftJoin('personal_area as t2', 't1.id', '=', 't2.fk_area')
            ->leftJoin('personal as t3', 't2.fk_personal', '=', 't3.id')
            ->select(
                't1.id',
                't1.area',
                't3.nombre',
            )
            ->where('t1.area', 'LIKE',  "%$name%")->get();

            if ($areas->isEmpty()) {
                return response()->json([['id' => '', 'area' => 'No se encontraron resultados..']], 404); // O lo que desees hacer si no se encuentra nada
            }

            return $areas;    

        } catch (\Throwable $th) {
            return $th;
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }    

    public function SearchPartidas ($name) {
        try {
            $partidas = Partidas::where('no_partida', 'LIKE',  "%$name%")->get();   
            return $partidas;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }        
    }

    public function SearchEntradas ($name) {
        try {
            $entradas = Entradas::where('no_orden', 'LIKE',  "%$name%")->get();   
            return $entradas;    
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }

    public function SearchProductos ($name) {
        try {

            $productos = Productos::from('productos as t1')
            ->leftJoin('producto_entrada as t2', 't1.id', '=', 't2.fk_producto')
            ->leftJoin('entradas as t3', 't3.id', '=', 't2.fk_entrada')
            ->leftJoin('producto_partidas as t4', 't1.id', '=', 't4.fk_producto')
            ->leftJoin('partidas as t5', 't5.id', '=', 't4.fk_partida')
            ->select(
                't1.id',
                't1.nombre',
                't1.descripcion',
                't1.stock',
                't1.unidad',
                't1.precio',
                't1.img',
                't2.fk_entrada', 
                't3.no_orden',
                't5.no_partida',
                't4.fk_partida',
            )
            ->where('t1.nombre', 'LIKE',  "%$name%")
            ->get();

            // $productos = Productos::where('nombre', 'LIKE',  "%$name%")->get();
            
            if ($productos->isEmpty()) {
                return response()->json([['id' => '', 'nombre' => 'Este producto no existe presiona para agregar..']], 404); // O lo que desees hacer si no se encuentra nada
            }
            return $productos;    
            
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }
}