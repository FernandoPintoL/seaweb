<?php

namespace App\Http\Controllers;

use App\Models\Vivienda;
use App\Http\Requests\StoreViviendaRequest;
use App\Http\Requests\UpdateViviendaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class ViviendaController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            if($request->get('skip') == null && $request->get('take') == null){
                $listado = DB::table('viviendas as v')
                            ->select('v.id as id', 'v.*', 'c.razonSocial', 'tv.detalle')
                            ->join('condominios as c', 'c.id','=','v.condominio_id')
                            ->join('tipo_viviendas as tv', 'tv.id','=','v.tipo_vivienda_id')
                            ->where('v.nroVivienda','LIKE', '%'.$queryUpper.'%')
                            ->orWhere('c.razonSocial','LIKE', '%'.$queryUpper.'%')
                            ->orWhere('tv.detalle','LIKE', '%'.$queryUpper.'%')
                            ->get();
            }else{
                $skip = $request->get('skip');
                $take = $request->get('take');
                $listado = DB::table('viviendas as v')
                            ->select('v.id as id', 'v.*', 'c.razonSocial', 'tv.detalle')
                            ->join('condominios as c', 'c.id','=','v.condominio_id')
                            ->join('tipo_viviendas as tv', 'tv.id','=','v.tipo_vivienda_id')
                            ->skip($skip)
                            ->take($take)
                            ->orderBy('id', 'DESC')
                            ->get();
            }
            $cantidad = count( $listado );
            $str = strval($cantidad);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $listado,
                "statusCode" => 200
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listado = DB::table('viviendas as v')
                            ->select('v.id as id', 'v.*', 'c.razonSocial', 'tv.detalle')
                            ->join('condominios as c', 'c.id','=','v.condominio_id')
                            ->join('tipo_viviendas as tv', 'tv.id','=','v.tipo_vivienda_id')
                            ->orderBy('v.id', 'DESC')
                            ->get();
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
                "isSuccess" => $model != null,
                "isMessageError" => $model != null,
                "message" => $model != null ? "Solicitud completada" : "Error!!!",
                "messageError" => "",
                "data" => $model,
                "statusCode" => 200
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
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
                            "isSuccess" => false,
                            "isMessageError" => true,
                            "message" => $validator->errors(),
                            "messageError" => $validator->errors(),
                            "data" => [],
                            "statusCode" => 422
                        ], 422 );
                    }
            }
            $response = $vivienda->update($request->all());
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $response,
                "isMessageError" => !$response,
                "message" => $response ? "Datos actualizados correctamente" : "Datos no actualizados",
                "messageError" => "",
                "data" => $response,
                "statusCode" => 200
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
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