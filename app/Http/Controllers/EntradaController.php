<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Entradas;
use App\Models\Producto_entrada;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class EntradaController extends Controller
{
    public function Showview () {
        $areas = Areas::all();        
        $entradas = Entradas::from('entradas as t1')
        ->leftJoin('areas as t2', 't1.area_solicitante', '=', 't2.id')
        ->select(
            't1.id',
            't1.no_orden',
            't1.fecha_compra',
            't1.fecha_entrada',
            't2.area',
        )->get();
            
        foreach ($entradas as $item) {
            $entradas->fecha_compra = $item->fecha_compra ?  $item->fecha_compra = Carbon::parse($item->fecha_compra)->format('m/d/Y') : 'null';
            $entradas->fecha_entrada = $item->fecha_entrada ?  $item->fecha_entrada = Carbon::parse($item->fecha_entrada)->format('m/d/Y') : 'null';
        }
    
        return Inertia::render('Entrada', 
            [
                'Entradas' => $entradas,
                'Areas' => $areas,
            ]
        );
    }

    public function Store (Request $request) {        

        $request->validate([
            'no_orden' => 'required',
            'proveedor' => 'required',
            'fecha_compra' => 'required',
            'area_solicitante' => 'required',
            'numero_requisicion' => 'required',
            'cantidad_piezas' => 'required',
            'precio_unitario' => 'required',
            'IVA' => 'required',
            'total' => 'required',
        ],[
            'no_orden.required' => 'El campo No° orden de compra es obligatorio.',
            'proveedor.required' => 'El campo proveedor es obligatorio.',
            'fecha_compra.required' => 'El campo Fecha de compra es obligatorio.',
            'area_solicitante.required' => 'El campo Area es obligatorio.',
            'numero_requisicion.required' => 'El campo No° requisicion es obligatorio.',
            'cantidad_piezas.required' => 'El campo Cantidad/piezas es obligatorio.',
            'precio_unitario.required' => 'El campo Precio unitario es obligatorio.',
            'IVA.required' => 'El campo IVA es obligatorio.',
            'total.required' => 'El campo Total es obligatorio.',
        ]);

        try {

            Entradas::updateOrCreate(
                ['id' => $request->id],
                [
                    'no_orden' => $request->no_orden, 
                    'fk_proveedor' => $request->proveedor,
                    'fecha_compra' => $request->fecha_compra,
                    'fecha_entrada' => $request->fecha_entrada,
                    'area_solicitante' => $request->area_solicitante,
                    'numero_requisicion' => $request->numero_requisicion,
                    'cantidad_piezas' => $request->cantidad_piezas,
                    'precio_unitario' => $request->precio_unitario,
                    'IVA' => $request->IVA,
                    'total' => $request->total
                ]
            );
            return response()->json(['result' => 1, 'msg' => 'Entrada creada con exito']);

        } catch (\Throwable $th) {
            return $th;
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'.$th]);
        }
    }   
    
    public function Edit ($id) {
        try {
            $entrada = Entradas::from('entradas as t1')
            ->leftJoin('areas as t2', 't1.area_solicitante', '=', 't2.id')
            ->leftJoin('proveedores as t3', 't1.fk_proveedor', '=', 't3.id')
            ->select(
                't1.id',
                't1.no_orden',
                't3.id as id_proveedor',
                't3.nombre as proveedor',
                't1.fecha_compra',
                't1.fecha_entrada',
                't1.area_solicitante',
                't1.numero_requisicion',
                't1.cantidad_piezas',
                't1.precio_unitario',
                't1.IVA',
                't1.total',
                't2.area',
            )
            ->where('t1.id', $id)
            ->first();            
            return $entrada;
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'.$th]);
        }
    }

    public function Delete ($id) {
        try {
            Producto_entrada::where('fk_entrada', $id)->delete();            
            Entradas::destroy($id);
            return response()->json(['result' => 1, 'msg' => 'Entrada Eliminada con exito']);
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
    }
}
