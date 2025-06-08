<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Areas;
use App\Models\Salidas;
use App\Models\Productos;
use App\Models\Entradas;
use App\Models\Partidas;
use App\Models\Proveedores;

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
                return response()->json(['name' => 'No se encontraron resultados...'], 404); // O lo que desees hacer si no se encuentra nada
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
            if ($partidas->isEmpty()) {
                return response()->json(['name' => 'No se encontraron resultados...'], 404); // O lo que desees hacer si no se encuentra nada
            }
            return $partidas;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }        
    }

    public function SearchEntradas ($name) {
        try {
            $entradas = Entradas::where('no_orden', 'LIKE',  "%$name%")->get();   

            if ($entradas->isEmpty()) {
                return response()->json(['name' => 'No se encontraron resultados...'], 404); // O lo que desees hacer si no se encuentra nada
            }

            return $entradas;    
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }

    public function SearchProductos ($name) {
        try {

            $productos = Productos::where('nombre', 'LIKE', "%$name%")->get();
            
            if ($productos->isEmpty()) {
                return response()->json(['name' => 'No se encontraron resultados presiona para agregar el nuevo producto.'], 404); // O lo que desees hacer si no se encuentra nada
            }
            return $productos;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }

    public function SearchProveedor ($name) {
        try {

            $proveedores = Proveedores::where(function ($query) use ($name) {
                $query->where('nombre', 'LIKE', "%$name%")
                      ->orWhere('razon_social', 'LIKE', "%$name%")
                      ->orWhere('rfc', 'LIKE', "%$name%");
            })->get(); 
            
            if ($proveedores->isEmpty()) {
                return response()->json(['name' => 'No se encontraron resultados...'], 404); // O lo que desees hacer si no se encuentra nada
            }

            return $proveedores;    
            
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }

    public function SearchDashboardEntradas ($name) {
        try {
            $entrada = Entradas::where('no_orden', 'LIKE', '%'.$name.'%')->get();
            return $entrada;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);            
        }
    }

    public function SearchSalidasEntradas ($name) {
        try {            

            $salidas = Salidas::where('no_salida', 'LIKE', '%'.$name.'%')->get();
            return $salidas;

        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);            
        }
    }
 }