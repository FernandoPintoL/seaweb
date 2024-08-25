<?php

namespace App\Http\Controllers;

use App\Models\TipoVivienda;
use App\Http\Requests\StoreTipoViviendaRequest;
use App\Http\Requests\UpdateTipoViviendaRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class TipoViviendaController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr = $request->get('query');
            $responsse = TipoVivienda::where('sigla','LIKE','%'.$queryStr.'%')
                        ->orWhere('detalle','LIKE','%'.$queryStr.'%')
                        ->orderBy('id', 'DESC')
                        ->get();
            $cantidad = count( $responsse );
            $str = strval($cantidad);
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "$str datos encontrados",
                "data" => $responsse
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Consulta Visita/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listado = TipoVivienda::all();
        return Inertia::render("TipoVivienda/Index", ['listado'=> $listado]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("TipoVivienda/CreateUpdate");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoViviendaRequest $request)
    {
        try{
            $model = TipoVivienda::create($request->all());
            return response()->json([
                "isRequest"=> true,
                "success" => $model != null,
                "messageError" => $model != null,
                "message" => $model != null ? "Solicitud completada" : "Error!!!",
                "data" => $model
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => $message." Code: ".$code,
                "data" => []
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoVivienda $tipoVivienda)
    {
        try{
            // $habitante = Habitante::where('vivienda_id','=',$idvivienda)->first();
            // $perfil    = Perfil::findOrFail( $habitante->perfil_id );
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "data" => $tipoVivienda
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => $message." Code: ".$code,
                "data" => []
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoVivienda $tipovivienda)
    {
        return Inertia::render("TipoVivienda/CreateUpdate", ['model'=> $tipovivienda]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoVivienda $tipovivienda)
    {
        try{
            if($request->sigla != $tipovivienda->sigla){
                $model     = $request->all();
                $validator = Validator::make($model, [
                        'sigla' => ['unique:tipo_viviendas']
                    ]);
                    if ($validator->fails()) {
                        return response()->json( [
                            "isRequest" => true,
                            "success" => false,
                            "messageError" => true,
                            "message" => $validator->errors(),
                            "data" => []
                        ], 422 );
                    }
            }
            $response = $tipovivienda->update($request->all());
            return response()->json([
                "isRequest"=> true,
                "success" => $response,
                "messageError" => !$response,
                "message" => $response ? "Datos actualizados correctamente" : "Datos no actualizados",
                "data" => $response
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => $message." Code: ".$code,
                "data" => []
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoVivienda $tipoVivienda)
    {
        //
    }
}
