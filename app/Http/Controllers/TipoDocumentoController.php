<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use App\Http\Requests\StoreTipoDocumentoRequest;
use App\Http\Requests\UpdateTipoDocumentoRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoDocumentoController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr = $request->get('query');
            $response = TipoDocumento::where('sigla','LIKE','%'.$queryStr.'%')
                        ->orWhere('detalle','LIKE','%'.$queryStr.'%')->get();
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Consulta TipoDocumento realizada correctamente...",
                "data" => $response
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Consulta TipoDocumento/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        $tipoDocumento = TipoDocumento::all();
        return Inertia::render("TipoDocumento/Index", ['tipodocumentos'=> $tipoDocumento]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("TipoDocumento/CreateUpdate");
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreTipoDocumentoRequest $request)
    {
        try{
            // return $request->all();
            $tipoDocumento = TipoDocumento::create($request->all());
            return response()->json([
                "isRequest"=> true,
                "success" => $tipoDocumento != null,
                "messageError" => $tipoDocumento != null,
                "message" => $tipoDocumento != null ? "Registro completo" : "Error!!!",
                "data" => $tipoDocumento
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
    public function show(TipoDocumento $tipodocumento)
    {
        try{
            // $habitante = Habitante::where('vivienda_id','=',$idvivienda)->first();
            // $perfil    = Perfil::findOrFail( $habitante->perfil_id );
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "data" => $tipodocumento
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
    public function edit(TipoDocumento $tipodocumento)
    {
        return Inertia::render("TipoDocumento/CreateUpdate", ['model'=> $tipodocumento]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoDocumento $tipodocumento)
    {
        try{
            if($request->sigla != $tipodocumento->sigla){
                $model     = $request->all();
                $validator = Validator::make($model, [
                        'sigla' => ['unique:tipo_documentos']
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
            $response = $tipodocumento->update($request->all());
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
    public function destroy(TipoDocumento $tipodocumento)
    {
        try{
            $response = $tipodocumento->delete();
            return response()->json([
                "isRequest"=> true,
                "success" => $response,
                "messageError" => !$response,
                "message" => $response ? "Datos eliminados correctamente" : "Los datos no pudieron ser eliminados",
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
}