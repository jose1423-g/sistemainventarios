<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entradas;
use App\Models\Salidas;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        $entradas = Entradas::count();
        $datosEntradas = Entradas::select('id', 'fecha_entrada', 'no_orden')->get();

        $salidas = Salidas::count();
        $datosSalidas = Salidas::select('id', 'fecha_salida', 'no_salida')->get();
        return Inertia::render('Dashboard', [
            'entradas' => $entradas,
            'datosEntradas' => $datosEntradas,
            'salidas' => $salidas,
            'datosSalidas' => $datosSalidas,
        ]);
    }
}
