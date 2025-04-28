<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Productos;
use App\Models\Entradas;

class PdfController extends Controller
{
    public function verPDF($id = null)
    {
        
        // Determinar qué registros mostrar según si hay ID o no
        // if ($id) {
            // Buscar entrada por ID
            // $entrada = Entradas::find($id);
            
            // if (!$entrada) {
            //     abort(404, 'Registro no encontrado');
            // }
            
            // Crear colección con un solo elemento
        //     $entradas = collect([$entrada]);
        // } else {
            // Obtener todas las entradas
            // return 'holis';
            $entradas = Entradas::where('id', $id)->get();
            return $entradas;
        // }

        /* 
            select * from entradas t1 
            left join productos_entrada t2 on t1.id = t2.entrada_id
            where t1.id = 1
        */
    
        // Preparar la ruta para la imagen del logo
        // $rutaImagen = storage_path("app/private/img/sopast3.jpg");
    
        // if (!file_exists($rutaImagen)) {
        //     abort(404, 'Imagen no encontrada');
        // }
    
        // Título dinámico basado en si es un registro específico o todos
        // $titulo = $id ? 'Detalle de Entrada #' . $id : 'Reporte de Todas las Entradas';
    
        // Cargar la vista y generar el PDF
        // $pdf = PDF::loadView('pdf.entradas', [
        //     'entradas' => $entradas,
        //     'titulo' => $titulo,
        //     'rutaImagen' => $rutaImagen,
        // ]);
        
        // Stream para visualizar en navegador (a diferencia de download que fuerza descarga)
        // return $pdf->stream('reporte_entradas.pdf');
    }

    public function descargar($no_orden = null)
    {
        // Si se proporciona un ID, obtener solo esa entrada
        if ($no_orden) {
            $entrada = Entradas::where('no_orden', $no_orden)->firstOrFail();   
            $entradas = collect([$entrada]);
        } else {
            // Si no se proporciona ID, obtener todas las entradas
            $entradas = Entradas::all();
        }

        // Preparar la ruta para la imagen del logo
        $rutaImagen = storage_path("app/private/img/sopast3.jpg");

        if (!file_exists($rutaImagen)) {
            abort(404, 'Imagen no encontrada');
        }

        $pdf = PDF::loadView('pdf.entradas', [
            'entradas' => $entradas,
            'titulo' => 'Reporte de Entradas',
            'rutaImagen' => $rutaImagen,
        ]);
        
        return $pdf->download('reporte_entradas.pdf');
    }
}
