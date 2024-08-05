<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cajas;
use App\Models\Personas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $item = Cajas::where('usuario_id', Auth::id())->with('persona')->paginate(10);
        return response()->json(["mensaje" => "Datos cargados", "datos" => $item]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "persona_id" => "required",
            "importe" => "required|numeric",
            "detalle" => "max:255",
            "tipo_movimiento" => "required|in:Ingreso,Egreso",
            "nro_respaldo" => "required"
        ]);
        try {
            DB::beginTransaction();
            $item = new Cajas();
            $item->usuario_id = Auth::id();
            $item->persona_id = $request->persona_id;
            $item->nro_respaldo = $request->nro_respaldo;
            $item->importe = $request->importe;
            if ($request->detalle != "") {
                $item->detalle = $request->detalle;
            }
            $item->tipo_movimiento = $request->tipo_movimiento;
            $item->save();
            DB::commit();
            return response()->json(["mensaje" => "Registro exitoso", "datos"=>$item],201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["message" => "Error: $th"], 406);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Cajas::where('id', $id)->with('persona')->first();
        return response()->json(["mensaje" => "Datos cargados", "datos" => $item]);        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "persona_id" => "required",
            "importe" => "required|numeric",
            "detalle" => "max:255",
            "tipo_movimiento" => "required|in:Ingreso,Egreso"
        ]);
        try {
            DB::beginTransaction();
            $item = Cajas::find($id);
            $item->usuario_id = Auth::id();
            $item->persona_id = $request->persona_id;
            $item->nro_respaldo = $request->nro_respaldo;
            $item->importe = $request->importe;
            $item->detalle = $request->detalle;
            $item->tipo_movimiento = $request->tipo_movimiento;
            $item->save();
            DB::commit();
            return response()->json(["mensaje" => "Modificacion exitosa", "datos"=>$item],202);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["message" => "Error: $th"], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Cajas::find($id);
        $item->estado = !$item->estado;
        $item->save();
        return response()->json(["mensaje" => "Modificacion exitosa", "datos"=>$item],203);
    }
    public function searchPersona(Request $request){
        $search = $request->get('search');
        $item = Personas::where('estado', true)
                            ->when($search, function($query) use ($search){
                                $query->where('nombre', 'LIKE', "%$search%")
                                        ->orWhere('apellido', 'LIKE', "%$search%")
                                        ->orWhere('identificacion', 'LIKE', "%$search%");
                            })->get();
        return response()->json(["mensaje" => "Datos cargados", "datos" => $item],200);
    }
    public function selectPersona(string $id){
        $item = Personas::find($id);
        return response()->json(["mensaje" => "Datos cargados", "datos" => $item],200);
    }
    public function flujoCaja(){
        $totalIngresos = Cajas::selectRaw('SUM(importe) AS totalIngreso')->where('tipo_movimiento', 'Ingreso')->where('estado', true)->where('usuario_id', Auth::id())->first();
        $totalEgresos = Cajas::selectRaw('SUM(importe) AS totalEgreso')->where('tipo_movimiento', 'Egreso')->where('estado', true)->where('usuario_id', Auth::id())->first();
        return response()->json(["mensaje" => "Datos Cargados", "ingresos" => $totalIngresos->totalIngreso, "egresos" => $totalEgresos->totalEgreso],200);
    }
}
