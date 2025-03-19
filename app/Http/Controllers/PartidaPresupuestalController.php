<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PartidaPresupuestalController extends Controller
{
    public function Showview () {
        return Inertia::render('PartidaPresupuestal');
    }   
    
    public function Store () {
        
    }   

}
