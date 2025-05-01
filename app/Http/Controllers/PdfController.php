<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Entradas;
use App\Models\Productos;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function ViewPdfEntrada ($id)
    {
                        
        $entrada = Entradas::from('entradas as t1')        
        ->leftJoin('areas as t2', 't1.area_solicitante', '=', 't2.id')
        ->leftJoin('personal_area as t3', 't2.id', '=', 't3.fk_area')
        ->leftJoin('personal as t4', 't3.fk_personal', '=', 't4.id')
        ->select(
            't1.no_orden', 
            't1.proveedor', 
            't1.fecha_compra', 
            't1.fecha_entrada', 
            't1.numero_requisicion', 
            't1.cantidad_piezas', 
            't1.precio_unitario', 
            't1.iva',
            't1.total',            
            't2.area',
            't4.nombre',
        )
        ->where('t1.id', $id)->first();

        $productos_entrada = Productos::from('productos as t1')
        ->leftJoin('producto_entrada as t2', 't1.id',  '=', 't2.fk_producto')
        ->select(
            't1.nombre',
            't1.stock',
            't1.unidad',
            't1.precio',
            't1.img'
        )
        ->where('t2.fk_entrada', $id)->get();


        $entrada->fecha_compra = $entrada->fecha_compra = Carbon::parse($entrada->fecha_compra)->format('m/d/Y');
        $entrada->fecha_entrada = $entrada->fecha_entrada = Carbon::parse($entrada->fecha_entrada)->format('m/d/Y');

        foreach($productos_entrada as $item) {
            $productos_entrada->img = $item->img = Storage::path($item->img);
        }

       $html = view('pdf.entradas', ['entrada' => $entrada, 'productos_entrada' => $productos_entrada])->render();

        // Configurar Dompdf
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', storage_path('app/public')); // << Aquí das acceso a esta carpeta
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $output = $dompdf->output();
        $fileName = 'reporte_de_entrada_' . $entrada->no_orden . '.pdf';

        Storage::put('pdf/'. $fileName, $output);
       
        $fileUrl = Storage::url('pdf/'.$fileName);

        // Devolver la URL al frontend
        return response()->json(['url' => $fileUrl]);
    }

    public function ViewPdfSalida ($id)
    {
        try {
            $entrada = Entradas::from('entradas as t1')        
            ->leftJoin('areas as t2', 't1.area_solicitante', '=', 't2.id')
            ->leftJoin('personal_area as t3', 't2.id', '=', 't3.fk_area')
            ->leftJoin('personal as t4', 't3.fk_personal', '=', 't4.id')
            ->select(
                't1.no_orden', 
                't1.proveedor', 
                't1.fecha_compra', 
                't1.fecha_entrada', 
                't1.numero_requisicion', 
                't1.cantidad_piezas', 
                't1.precio_unitario', 
                't1.iva',
                't1.total',            
                't2.area',
                't4.nombre',
            )
            ->where('t1.id', $id)->first();

            $productos_entrada = Productos::from('productos as t1')
            ->leftJoin('producto_entrada as t2', 't1.id',  '=', 't2.fk_producto')
            ->select(
                't1.nombre',
                't1.stock',
                't1.unidad',
                't1.precio',
                't1.img'
            )
            ->where('t2.fk_entrada', $id)->get();


            $entrada->fecha_compra = $entrada->fecha_compra = Carbon::parse($entrada->fecha_compra)->format('m/d/Y');
            $entrada->fecha_entrada = $entrada->fecha_entrada = Carbon::parse($entrada->fecha_entrada)->format('m/d/Y');

            foreach($productos_entrada as $item) {
                $productos_entrada->img = $item->img = Storage::path($item->img);
            }

            $html = view('pdf.entradas', ['entrada' => $entrada, 'productos_entrada' => $productos_entrada])->render();

            // Configurar Dompdf
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', true);
            $options->set('chroot', storage_path('app/public')); // << Aquí das acceso a esta carpeta
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $output = $dompdf->output();
            $fileName = 'reporte_de_entrada_' . $entrada->no_orden . '.pdf';

            Storage::put('pdf/'. $fileName, $output);
        
            $fileUrl = Storage::url('pdf/'.$fileName);

            // Devolver la URL al frontend
            return response()->json(['url' => $fileUrl]);

        } catch (\Throwable $th) {

            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal'], 422);
        }
    }
}
