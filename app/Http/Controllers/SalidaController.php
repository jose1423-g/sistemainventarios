<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SalidaController extends Controller
{
    public function Showview () {
        return Inertia::render('Salida');
    }

    public function Store (Request $request) {        
    }   
    
    public function Edit ($id) {
    }

    public function Delete ($id) {
    }
}
