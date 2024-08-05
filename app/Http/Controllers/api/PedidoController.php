<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cajas;
use App\Models\Pedidos;
use App\Models\PedidosDetalle;
use App\Models\PedidosPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index (){
        $item = Pedidos::where('usuario_id', Auth::id())->with('pago')->paginate(10);
        return response()->json(["mensaje" => "Datos cargados", "datos" => $item], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required",
            "total" => "required",
            "tipo_pago" => "required|in:transferencia,tarjeta de credito"
        ], [
            'tipo_pago.in' => 'El tipo de pago debe ser "transferencia" o "tarjeta de credito".'
        ]);
        $request->validate([
            'nro_referencia' => 'required_if:tipo_pago,transferencia|required_if:tipo_pago,tarjeta de credito|digits:16',
            'expiracion' => 'required_if:tipo_pago,tarjeta de credito|date_format:m/Y',
            'cvv' => 'required_if:tipo_pago,tarjeta de credito|digits:3'
        ], [
            'nro_referencia.required_if' => 'El número de referencia es obligatorio para el tipo de pago seleccionado.',
            'nro_referencia.digits' => 'El número de referencia debe tener 16 dígitos.',
            'expiracion.required_if' => 'La fecha de expiración es obligatoria para pagos con tarjeta de crédito.',
            'expiracion.date_format' => 'La fecha de expiración debe tener el formato MM/AAAA.',
            'cvv.required_if' => 'El CVV es obligatorio para pagos con tarjeta de crédito.',
            'cvv.digits' => 'El CVV debe tener 3 dígitos.'
        ]);
        if (count($request->detalle) > 0) {
            try {
                DB::beginTransaction();
                $item = new Pedidos();
                $item->usuario_id = Auth::id();
                $item->total = round($request->total, 2);
                $item->save();
                foreach ($request->detalle as $value) {
                    $item2 = new PedidosDetalle();
                    $item2->pedido_id = $item->id;
                    $item2->producto_id = $value["producto_id"];
                    $item2->cantidad = $value["cantidad"];
                    $item2->precio_unitario = $value["precio_unitario"];
                    $item2->subtotal = $value["subtotal"];
                    $item2->save();
                }
                $item3 = new PedidosPago();
                $item3->pedido_id = $item->id;
                $item3->nombre = $request->nombre;
                $item3->tipo_pago = $request->tipo_pago;
                $item3->nro_referencia = $request->nro_referencia;
                if ($request->tipo_pago == 'tarjeta de credito') {
                    $item3->expiracion = $request->expiracion;
                    $item3->cvv = $request->cvv;
                }
                $item3->importe = round($request->total, 2);
                $item3->save();
                $item4 = new Cajas();
                $item4->usuario_id = Auth::id();
                $item4->persona_id = 1;
                if ($request->tipo_pago == 'transferencia') {                    
                    $item4->nro_respaldo = $request->nro_referencia;
                }else{
                    $item4->nro_respaldo = 0;
                }
                $item4->importe = round($request->total, 2);
                $item4->detalle = "Ingreso por VENTA";
                $item4->tipo_movimiento = "Ingreso";
                $item4->save();
                DB::commit();
                return response()->json(["mensaje" => "Registro exitoso", "datos" => $item], 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(["message" => "Error: $th"], 422);
            }
        } else {
            return response()->json(["message" => "La tabla debe contener al menos un producto"], 406);
        }
    }
}
