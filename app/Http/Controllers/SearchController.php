<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Areas;
use App\Models\Salidas;
use App\Models\User;
use App\Models\Productos;
use App\Models\Personal;
use App\Models\Entradas;
use App\Models\Partidas;
use Carbon\Carbon;

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

    public function SearchEntradasTable (Request $request) {
        try {
            $entradas = Entradas::from('entradas as t1')
            ->leftJoin('areas as t2', 't1.area_solicitante', '=', 't2.id')
            ->select(
                't1.id',
                't1.no_orden',
                't1.fecha_compra',
                't1.fecha_entrada',
                't2.area',
            );

            if ($request->filled('search_noentrada')) {
                $entradas->where('t1.no_orden', 'LIKE', '%' . $request->search_noentrada . '%');
            }

            if ($request->filled('search_fechacompra')) {
                $entradas->whereDate('t1.fecha_compra', $request->search_fechacompra);
            }

            if ($request->filled('search_fechaentrada')) {
                $entradas->whereDate('t1.fecha_entrada', $request->search_fechaentrada);
            }

            if ($request->filled('search_area')) {
                $entradas->where('t1.area_solicitante', $request->search_area);
            }

            $entradas = $entradas->get();

            foreach ($entradas as $item) {
                $entradas->fecha_compra = $item->fecha_compra ?  $item->fecha_compra = Carbon::parse($item->fecha_compra)->format('m/d/Y') : 'null';
                $entradas->fecha_entrada = $item->fecha_entrada ?  $item->fecha_entrada = Carbon::parse($item->fecha_entrada)->format('m/d/Y') : 'null';
            }

            return $entradas;

        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }

    public function SearchSalidasTable (Request $request) {
        try {
            $salidas = Salidas::from('salidas as t1')
            ->leftJoin('areas as t2', 't1.fk_area', '=', 't2.id')
            ->leftJoin('entradas as t3', 't1.fk_no_compra', '=', 't3.id')
            ->select(
                't1.id',
                't1.no_salida',
                't3.no_orden',
                't1.fecha_salida',
                't2.area',
            );            

            if ($request->filled('search_nosalida')) {
                $salidas->where('t1.no_salida', $request->search_nosalida);
            }

            if ($request->filled('search_noentrada')) {
                $salidas->where('t3.no_orden', $request->search_noentrada);
            }

            if ($request->filled('search_fechasalida')) {
                $salidas->where('t1.fecha_salida', $request->search_fechasalida);
            }

            if ($request->filled('search_area')) {
                $salidas->where('t2.id', $request->search_area);
            }            
            
            $salidas = $salidas->get();

            foreach($salidas as $item){
                $salidas->fecha_salida =  $item->fecha_salida = Carbon::parse($item->fecha_salida)->format('m/d/Y');
            }

            return $salidas;

        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }    
    }

    public function SearchPartidaTable (Request $request) {
        try {
            $partida = Partidas::query();

            if ($request->filled('search_nopartida')) {
                $partida->where('no_partida', $request->search_nopartida);
            }          

            if ($request->filled('search_nombre')) {
                $partida->where('nombre', $request->search_nombre);
            }          

            $partida = $partida->get();
            return $partida;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }

    public function SearchPersonalTable (Request $request) {
        try {

            $personal = Personal::from('personal as t1')
            ->leftJoin('personal_area as t2', 't1.id', '=', 't2.fk_personal')
            ->leftJoin('areas as t3', 't2.fk_area', '=', 't3.id')
            ->select(
                't1.id', 
                't1.nombre', 
                't3.area',
                't1.activo',
            );

            if ($request->filled('search_nombre')) {
                $personal->where('t1.nombre', 'LIKE', '%' . $request->search_nombre . '%');
            }

            if ($request->filled('search_area')) {
                $personal->where('t3.id', $request->search_area);
            }

            $personal = $personal->get();

            return $personal;
            
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }

    public function SearchUsersTable (Request $request) {
        try {
            $users = User::leftJoin('rol_usuario', 'users.id' , '=', 'rol_usuario.fk_usuario')
            ->leftJoin('roles', 'rol_usuario.fk_rol', '=', 'roles.id')
            ->select(
                'users.id', 
                'users.name', 
                'users.email',
                'nombre_rol'
            );
            
            
            if ($request->filled('search_nombre')) {
                $users->where('users.name', $request->search_nombre);
            }

            if ($request->filled('search_email')) {
                $users->where('users.email', 'LIKE', '%' . $request->search_email . '%');
            }

            if ($request->filled('search_rol')) {
                $users->where('roles.id', $request->search_rol);
            }
            
            
           return $users->get();

        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);            
        }
    }

    public function SearchAreaTable (Request $request) {
        try {

            $areas = Areas::query();            
            
            if ($request->filled('search_area')) {
                $areas->where('area', 'LIKE', '%' . $request->search_area . '%');
            }
            
           return $areas->get();

        } catch (\Throwable $th) {            
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);            
        }
    }
}