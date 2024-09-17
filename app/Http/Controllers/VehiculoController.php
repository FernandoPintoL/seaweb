<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\GaleriaVehiculo;
use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class VehiculoController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $responsse  = [];
            if($request->get('skip') == null && $request->get('take') == null){
                $responsse = Vehiculo::where('placa','LIKE',"%".$queryUpper."%")
                            ->orderBy('id', 'DESC')
                            ->get();
            }else{
                $skip = $request->get('skip');
                $take = $request->get('take');
                $responsse = Vehiculo::where('placa','LIKE',"%".$queryUpper."%")
                            ->skip($skip)
                            ->take($take)
                            ->orderBy('id', 'DESC')
                            ->get();
            }
            $cantidad = count( $responsse );
            $str = strval($cantidad);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
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

    public function queryId(Request $request){
        try{
            $queryStr    = $request->get('query');
            $responsse = Vehiculo::where('id','=',$queryStr)->get();
            $cantidad = count( $responsse );
            $str = strval($cantidad);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
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
        $listado = Vehiculo::skip(0)
                    ->take(20)
                    ->orderBy('id', 'DESC')
                    ->get();
        return Inertia::render("Vehiculo/Index", ['listado'=> $listado]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Vehiculo/CreateUpdate");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehiculoRequest $request)
    {
        try{
            $responsse = Vehiculo::create($request->all());
            $responsse->update([
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ]);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Transacción correcta" : "Error!!!",
                "messageError" => "",
                "data" => $responsse,
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
    public function show(Vehiculo $appvehiculo)
    {
        try{
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Transacción Correcta...",
                "messageError" => "",
                "data" => $appvehiculo,
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
     * Show the form for editing the specified resource.
     */
    public function edit(Vehiculo $vehiculo)
    {
        $list_galeria = GaleriaVehiculo::where('vehiculo_id','=',$vehiculo->id)->get();
        return Inertia::render("Vehiculo/CreateUpdate", ['model'=> $vehiculo, 'listado' => $list_galeria]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehiculo $appvehiculo)
    {
        try{
            $responsse = $appvehiculo->update($request->all());
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Transacción correcta" : "Error!!!",
                "messageError" => "",
                "data" => $responsse,
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

    public function updateVehiculo(Request $request, Vehiculo $vehiculo)
    {
        try{
            $responsse = $vehiculo->update($request->all());
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Transacción correcta" : "Error!!!",
                "messageError" => "",
                "data" => $responsse,
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
    public function destroy(Vehiculo $appvehiculo)
    {
        try{
            $responsse = $appvehiculo->delete();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Transacción correcta" : "Error!!!",
                "messageError" => "",
                "data" => $responsse,
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
}