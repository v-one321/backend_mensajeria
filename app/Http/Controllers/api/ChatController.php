<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ChatModel;
use App\Models\DetalleChatModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function viewClientChat(string $id){
        if (Auth::user()->rol == "cliente") {
            $item = ChatModel::where('cliente_id', Auth::id())->where('trabajador_id', $id)->with('detalle_chat', 'trabajador', 'cliente')->first();
        } else {
            $item = ChatModel::where('trabajador_id', Auth::id())->where('cliente_id', $id)->with('detalle_chat', 'trabajador', 'cliente')->first();
        }        
        return response()->json(["mensaje" => "Mensajes cargados", "datos" => $item]);
    }
    public function storeClientChat(Request $request){
        $request->validate([
            "mensaje" => "required|max:255",
            "destino_id" => "required"
        ]);
        if (Auth::user()->rol == "cliente") {
            $datos = ChatModel::where('cliente_id', Auth::id())->where('trabajador_id', $request->destino_id)->first();
            $cliente_id = Auth::id();
            $trabajador_id = $request->destino_id;
        }else{
            $datos = ChatModel::where('trabajador_id', Auth::id())->where('cliente_id', $request->destino_id)->first();
            $cliente_id = $request->destino_id;
            $trabajador_id = Auth::id();
        }
        try {
            DB::beginTransaction();
            if ($datos) {
                $item = $datos;
            }else{
                $item = new ChatModel();
            }
            $item->trabajador_id = $trabajador_id;
            $item->cliente_id = $cliente_id;
            $item->save();
            $item2 = new DetalleChatModel();
            $item2->chat_id = $item->id;
            $item2->mensaje = $request->mensaje;
            $item2->tipo = Auth::user()->rol;
            $item2->save();
            DB::commit();
            return response()->json(["mensaje" => "Mensaje enviado", "datos" => $item]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["mensaje" => "Error: $th"], 422);
        }
    }
    public function chatRecientes(){
        if (Auth::user()->rol == "cliente") {
            $item = ChatModel::where('cliente_id', Auth::id())->with('ultimoDetalleChat', 'trabajador', 'cliente')->get();
        } else {
            $item = ChatModel::where('trabajador_id', Auth::id())->with('ultimoDetalleChat', 'trabajador', 'cliente')->get();
        }        
        return response()->json(["mensaje" => "Mensajes cargados", "datos" => $item]);
    }
}
