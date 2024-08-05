<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->rol == "cliente") {
            $item = User::where('estado', true)->where('rol', 'usuario')->where('id', '!=', Auth::id())->paginate(8);
        }else{
            $item = User::where('estado', true)->where('rol', 'cliente')->where('id', '!=', Auth::id())->paginate(8);
        }
        return response()->json(["mensaje" => "Datos cargados", "datos" =>$item], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required|min:8"
        ]);
        $item = new User();
        $item->nombre = $request->nombre;
        $item->email = $request->email;
        $item->password = bcrypt($request->password);
        $item->rol = 'usuario';
        $item->save();
        return response()->json(["mensaje" => "Usuario registrado", "datos" => $item], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = User::find(Auth::id());
        return response()->json($item, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario_id = Auth::id();
        $request->validate([
            "nombre" => "required",
            "email" => "required|email|unique:users,email,$usuario_id",
            "password" => "confirmed"
        ]);
        $item = User::find($usuario_id);
        $item->nombre = $request->nombre;
        $item->email = $request->email;
        if ($request->password != "") {
            $item->password = bcrypt($request->password);
        }
        if ($request->file('imagen')) {
            if($item->imagen_perfil){
                unlink('fotos/'.$item->imagen_perfil);
            }
            $file = $request->file('imagen');
            $nombreImagen = time().'.png';
            $file->move('fotos/', $nombreImagen);
            $item->imagen_perfil = $nombreImagen;
        }
        $item->save();
        return response()->json(["mensaje" => "Registro modificado", "datos" => $item]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = User::find(Auth::id());
        $item->estado = !$item->estado;
        $item->save();
        return response()->json(["mensaje" => "Usuario bloqueado", "datos" =>$item]);
    }
    /**************************     CLIENTE         ****************** */
    public function storeCliente(Request $request)
    {
        $request->validate([
            "nombre" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required|min:8"
        ]);
        $item = new User();
        $item->nombre = $request->nombre;
        $item->email = $request->email;
        $item->password = bcrypt($request->password);
        $item->rol = 'cliente';
        $item->save();
        return response()->json(["mensaje" => "Usuario registrado", "datos" => $item], 200);
    }
}
