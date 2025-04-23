<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use App\Models\Producto_entrada;
use App\Models\producto_partidas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function PHPUnit\Framework\returnValueMap;

class ProductosController extends Controller
{
    public function Showview () {

        $productos = Productos::from('productos as t1')
        ->leftJoin('producto_entrada as t2', 't1.id', '=', 't2.fk_producto')
        ->leftJoin('entradas as t3', 't3.id', '=', 't2.fk_entrada')
        ->leftJoin('producto_partidas as t4', 't1.id', '=', 't4.fk_producto')
        ->leftJoin('partidas as t5', 't5.id', '=', 't4.fk_partida')
        ->select(
            't1.id',
            't1.nombre',
            't3.no_orden',
            't3.fecha_compra',
            't3.fecha_entrada',
            't5.no_partida',
        )->get();

        return Inertia::render('Productos', ['Productos' => $productos]);
    }

    public function Store (Request $request) {

        $request->validate([
            'fk_entrada' => 'required',
            'fk_partida' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required',
            'stock' => 'required',
            'unidad' => 'required',
            'precio' => 'required',
            'img' => 'required',
        ],[
            'fk_entrada.required' => 'El campo No° de entrada es obligatorio.',
            'fk_partida.required' => 'El campo No° partida es obligatorio.',
            'nombre.required' => 'El campo nombre del producto es obligatorio.',
            'descripcion.required' => 'El campo descripcion es obligatorio.',
            'stock.required' => 'El campo total de articulos es obligatorio.',
            'unidad.required' => 'El campo unidad es obligatorio.',
            'precio.required' => 'El campo precio es obligatorio.',
            'img.required' => 'El campo imagen es obligatorio',
        ]);

        /* guarda la imagen con un nombre generado y unico */
        $img_name = '';
        if (!Storage::disk('public')->exists($request->img)) {
            $img_name = $request->file('img')->store('img');
        } else {
            $img_name = $request->img;
        }        
        
        try {
        
            $productos =  Productos::updateOrCreate(
                ['id' => $request->id],
                [
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion, 
                    'stock' => $request->stock,
                    'unidad' => $request->unidad,
                    'precio' => $request->precio, 
                    'img' => $img_name,
                ]
            );


            Producto_entrada::updateOrCreate(
                [
                    'fk_producto' => $productos->id,
                ],
                [
                    'fk_producto' => $productos->id,
                    'fk_entrada' => $request->fk_entrada,
                ]
            );

            producto_partidas::updateOrCreate(
                [
                    'fk_producto' => $productos->id,                    
                ],
                [
                    'fk_producto' => $productos->id,
                    'fk_partida' => $request->fk_partida, 
                ]
            );

            return response()->json(['result' => 1, 'msg' => 'Producto agregado con exito']);

        } catch (\Throwable $th) {
            
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }        
                
    }

    
    public function Edit ($id) {
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
            ->where('t1.id', $id)
            ->first();
                    
            $productos->url_img = Storage::url($productos->img);
            
            return $productos;
        } catch (\Throwable $th) {
            return $th;
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }        
    }

    public function Delete ($id) {
        try {
            
            Producto_entrada::where('fk_producto', $id)->delete();        
            producto_partidas::where('fk_producto', $id)->delete();
            Productos::destroy($id);
            
            return response()->json(['result' => 1, 'msg' => 'Producto Eliminado con exito']);
        } catch (\Throwable $th) {        
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
    }
}