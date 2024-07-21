<?php

namespace App\Http\Controllers;

use App\Models\Vivienda;
use App\Http\Requests\StoreViviendaRequest;
use App\Http\Requests\UpdateViviendaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ViviendaController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            $response = Vivienda::where('nroVivienda','LIKE',"%".$queryStr."%")
                        ->with('tipoVivienda')
                        ->with('condominio')
                        ->get();
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Consulta vivienda realizada correctamente...",
                "data" => $response
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Consulta vivienda/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listado = Vivienda::all();
        return Inertia::render("Vivienda/Index", ['listado'=> $listado]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Vivienda/CreateUpdate");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreViviendaRequest $request)
    {
        try{
            $model = Vivienda::create($request->all());
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
    public function show(Vivienda $vivienda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vivienda $vivienda)
    {
        return Inertia::render("Vivienda/CreateUpdate", ['model'=> $vivienda]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vivienda $vivienda)
    {
        try{
            if($request->nroVivienda != $vivienda->nroVivienda){
                $model     = $request->all();
                $validator = Validator::make($model, [
                        'nroVivienda' => ['unique:viviendas']
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
            $response = $vivienda->update($request->all());
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
    public function destroy(Vivienda $vivienda)
    {
        //
    }
}