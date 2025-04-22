<?php

namespace App\Http\Controllers;
use App\Models\Salidas;
use App\Models\Producto_salida;
use App\Models\Productos;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class SalidaController extends Controller
{
    public function Showview () {
        $salidas = Salidas::from('salidas as t1')
        ->leftJoin('areas as t2', 't1.fk_area', '=', 't2.id')
        ->leftJoin('entradas as t3', 't1.fk_no_compra', '=', 't3.id')
        ->select(
            't1.id',
            't1.no_salida',
            't3.no_orden',
            't1.fecha_salida',
            't2.area',
        )
        ->get();

        foreach($salidas as $item){
            $salidas->fecha_salida =  $item->fecha_salida = Carbon::parse($item->fecha_salida)->format('m/d/Y');
        }

        return Inertia::render('Salida', 
            [
                'Salidas' => $salidas,
            ]
        );
    }

    public function Store (Request $request) {   

        $request->validate([
            'no_salida' => 'required',
            'fk_no_compra' => 'required',
            'fecha_salida' => 'required',
            'fk_area' => 'required',
            'Productos' => 'required|array|min:1',         
        ],[
            'no_salida.required' => 'El campo No° salida es obligatorio.',
            'fk_no_compra.required' => 'El campo no° orden de compra  es obligatorio.',
            'fecha_salida.required' => 'El campo fecha de salida es obligatorio.',
            'fk_area.required' => 'El campo area es obligatorio.',
            'Productos.required' => 'Agrega al menos un producto.',            
        ]);
        

        try {

            /* restar la cantidad de los productos existentes */            

            foreach ($request->Productos as $value) {                

                $producto =  Productos::where('id', $value['id'])->first();

                $stock = (int) $producto->stock;
                $cantidad = (int) $value['cantidad'];
                
                if($stock >= $cantidad) {
                    $new_cantidad = abs($stock - $cantidad);
                    Productos::where('id', $value['id'])->update(['stock' => $new_cantidad]);
                } else {
                    return response()->json(['result' => 0, 'msg' => 'El producto "'.$value['nombre'].'" no cuenta con sufiente stock']);
                }
                
            }
            
            $salida = Salidas::updateOrCreate(
                ['id' => $request->id],
                [
                    'no_salida' => $request->no_salida,
                    'fk_no_compra' => $request->fk_no_compra, 
                    'fecha_salida' => $request->fecha_salida,
                    'fk_area' => $request->fk_area,
                    'personal' => $request->personal,
                ]
            );
    
            foreach ($request->Productos as $value) {

                Producto_salida::updateOrCreate(
                    [
                        'fk_salida' => $salida->id,
                        'fk_producto' => $value['id'],
                    ],
                    [
                        'fk_producto' => $value['id'],
                        'fk_salida' => $salida->id,
                        'cantidad' => $value['cantidad'],
                    ]
                );
            }

            return response()->json(['result' => 1, 'msg' => 'Salida creada con exito']);

        } catch (\Throwable $th) {  
            return $th;
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }       
    }   
    
    public function Edit ($id) {

        $salidas = Salidas::from('salidas as t1')
        ->leftJoin('areas as t2', 't1.fk_area', '=', 't2.id')
        ->leftJoin('entradas as t3', 't1.fk_no_compra', '=', 't3.id')
        ->select(
            't1.id',
            't1.no_salida',
            't3.no_orden',
            't1.fecha_salida',
            't2.area',
        )
        ->get();

        $producto_salida = Salidas::from('salidas as t1')
        ->leftJoin('producto_salida as t2', 't1.id', '=', 't2.fk_salida')
        ->leftJoin('productos as t3', 't2.fk_producto', '=', 't3.id')
        ->select(
            't2.fk_producto',
            't2.cantidad',
            't3.nombre',
        )
        ->get();

    }

    public function Delete ($id) {
    }
}
