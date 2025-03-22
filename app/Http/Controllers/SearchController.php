<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Areas;

class SearchController extends Controller
{
    public function SearchArea ($name) {
        try {
            $areas = Areas::where('area', 'LIKE',  "%$name%")->get();   
            return $areas;    
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }

    public function SearchOrdenCompra ($name) {
        try {
            $areas = Areas::where('area', 'LIKE',  "%$name%")->get();   
            return $areas;    
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }

    public function SearchPartidas ($name) {
        try {
            $areas = Areas::where('area', 'LIKE',  "%$name%")->get();   
            return $areas;    
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }

    public function SearchProductos ($name) {
        try {
            $areas = Areas::where('area', 'LIKE',  "%$name%")->get();   
            return $areas;    
        } catch (\Throwable $th) {
            return response()->json(['result' => 0, 'msg' => 'Ups algo salio mal']);
        }
        
    }
}