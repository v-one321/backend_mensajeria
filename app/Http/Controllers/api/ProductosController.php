<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Productos;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function index(Request $request){
        $search = $request->get('search');
        $categoria = $request->get('categoria');
        $item = Productos::when($categoria, function ($query) use ($categoria){
            $query->where('categoria_id', $categoria);
        })
        ->when($search, function($query) use ($search){
            $query->where('nombre', 'LIKE', "%$search%")
                    ->orWhere('codigo', 'LIKE', "%$search%");
        })
        ->with('categoria')->paginate(5);
        return response()->json(["mensaje" => "Datos cargados", "datos" => $item], 200);
    }
    public function productoAleatorio(){
        $item = Productos::inRandomOrder()->first();
        return response()->json(["mensaje" => "Registro cargado", "datos" => $item], 200);
    }
}
