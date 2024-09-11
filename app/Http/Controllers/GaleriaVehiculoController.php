<?php

namespace App\Http\Controllers;

use App\Models\GaleriaVehiculo;
use App\Http\Requests\StoreGaleriaVehiculoRequest;
use App\Http\Requests\UpdateGaleriaVehiculoRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GaleriaVehiculoController extends Controller
{
    public function uploadimage(Request $request){
        try{
            /*return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Mensaje",
                "data" => $request->all()
            ]);*/
            $response = "";
            $path     = null;

            if($request->hasFile('file')){
                $model = GaleriaVehiculo::create([
                    "vehiculo_id" => $request->get("id"),
                    'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
                $file = $request->file( 'file' );
                $extension = $request->file('file')->getClientOriginalExtension();
                $filename= "cod".$model->id."-vehiculoid".$request->get("id").'.'.$extension;
                $path      = Storage::putFile( 'vehiculos', $file, 'public' );
                // $path = $request->file('file')->storeAs('vehiculos', $filename, 'public');
                if($path == null || $path == 0){
                    $model->delete();
                }else{
                    $url = Storage::url($path);
                    $response = $model->update([
                                    'photo_path'=> $url,
                                    'detalle'=> $path
                                ]);
                }
            }
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $request->hasFile('file') && ($path != null || $path != 0),
                "isMessageError" => !$request->hasFile('file') && ($path == null || $path == 0),
                "message" => $path != null || $path != 0 ? "Archivos subidos" : "Archivos no subidos",
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

    public function getGaleriaVehiculo(Request $request){
        try{
            $responsse = GaleriaVehiculo::where('vehiculo_id','=',$request->get('vehiculo_id'))
                        ->with('vehiculo')
                        ->orderBy('id', 'DESC')
                        ->get();
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

    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $responsse  = [];
            if($request->get('skip') == null && $request->get('take') == null){
                $responsse = DB::table('galeria_vehiculos as gvh')
                            ->select('gvh.id as id','gvh.*','vh.id as v_id', 'vh.placa','vh.tipo_vehiculo', 'vh.visitante_id')
                            ->join('vehiculos as vh', 'vh.id', '=', 'gvh.vehiculo_id')
                            ->where('vh.placa','LIKE',"%".$queryUpper."%")
                            ->orderBy('gvh.id', 'DESC')
                            ->get();
            }else{
                $skip = $request->get('skip');
                $take = $request->get('take');
                $responsse = DB::table('galeria_vehiculos as gvh')
                            ->select('gvh.id as id','gvh.*','vh.id as v_id', 'vh.placa','vh.tipo_vehiculo', 'vh.visitante_id')
                            ->join('vehiculos as vh', 'vh.id', '=', 'gvh.vehiculo_id')
                            ->where('vh.placa','LIKE',"%".$queryUpper."%")
                            ->skip($skip)
                            ->take($take)
                            ->orderBy('gv.id', 'DESC')
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGaleriaVehiculoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GaleriaVehiculo $galeriaVehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GaleriaVehiculo $galeriaVehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGaleriaVehiculoRequest $request, GaleriaVehiculo $galeriaVehiculo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GaleriaVehiculo $galeriavehiculo)
    {
        try{
            $responseFile = Storage::delete($galeriavehiculo->detalle);
            $responseData = $galeriavehiculo->delete();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responseData && $responseFile,
                "isMessageError" => !$responseData && !$responseFile,
                "message" => $responseData && $responseFile ? "TRANSACCION CORRECTA": "TRANSACCION INCORRECTA",
                "messageError" => "",
                "data" => [],
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

    public function descargar($id){
        try{
            $directorio = Storage::files( 'vehiculos' );
            $link = "https://sea-production-2d37.up.railway.app/storage/".$directorio[$id];
            // $link = public_path("storage/".$directorio[$id]);
            // dd( $link );
            return response()->download($link);
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