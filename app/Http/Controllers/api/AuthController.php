<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{    
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8"
        ]);
        $credenciales = request(["email", "password"]);
        if (Auth::attempt($credenciales)) {
            $usuario = $request->user();
            $creacionToken = $usuario->createToken('Personal token');
            $token = $creacionToken->plainTextToken;
            $item = User::find($usuario->id);
            $item->sesion = 1;
            $item->save();
            return response()->json(["mensaje" => "Sesion iniciada", "user" => $usuario, "access_token" => $token, "token_type" => "Bearer"], 200);
        }else{
            //las credenciales no existen
            return response()->json(["mensaje" => "Usuario o contraseña no validos"], 401);
        }
    }
    public function logout(){
        $item = User::find(Auth::id());
        $item->sesion = 0;
        $item->save();
        Auth::user()->tokens()->delete();
        return response()->json(["mensaje" => "Sesion finalizada"], 200);
    }
}
