<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Categorias;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    public function index(){
        $item = Categorias::all();
        return response()->json(["mensaje" => "Datos cargados", "datos" => $item], 200);
    }
}
