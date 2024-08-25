<?php

namespace App\Http\Controllers;

use App\Models\TipoVisita;
use App\Http\Requests\StoreTipoVisitaRequest;
use App\Http\Requests\UpdateTipoVisitaRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
class TipoVisitaController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr = $request->get('query');
            $responsse = TipoVisita::where('sigla','LIKE','%'.$queryStr.'%')
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
        $listado = TipoVisita::all();
        return Inertia::render("TipoVisita/Index", ['listado'=> $listado]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("TipoVisita/CreateUpdate");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoVisitaRequest $request)
    {
        try{
            $model = TipoVisita::create($request->all());
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
    public function show(TipoVisita $apptipoVisita)
    {
        try{
            // $habitante = Habitante::where('vivienda_id','=',$idvivienda)->first();
            // $perfil    = Perfil::findOrFail( $habitante->perfil_id );
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "data" => $apptipoVisita
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
    public function edit(TipoVisita $tipovisitum)
    {
        return Inertia::render("TipoVisita/CreateUpdate", ['model'=> $tipovisitum]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoVisita $tipovisitum)
    {
        try{
            if($request->sigla != $tipovisitum->sigla){
                $model     = $request->all();
                $validator = Validator::make($model, [
                        'sigla' => ['unique:tipo_visitas']
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
            $response = $tipovisitum->update($request->all());
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
    public function destroy(TipoVisita $tipovisitum)
    {
        //
    }
}
