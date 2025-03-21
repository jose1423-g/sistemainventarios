<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Areas;

class SearchController extends Controller
{
    public function SearchArea ($name) {
        $data = Areas::where('area', $name)->get();   
        return $data;
    }
}
