<?php

namespace App\Http\Controllers;

use App\Models\GaleriaVisitante;
use App\Http\Requests\StoreGaleriaVisitanteRequest;
use App\Http\Requests\UpdateGaleriaVisitanteRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\File;

class GaleriaVisitanteController extends Controller
{
    public function uploadimage(Request $request){
        try{
            /* return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "messages" => $request->hasFile('file'),
                "messagesValue" => $request->hasFile('file'),
                "data" => $request->all()
            ]); */

            $response = null;
            $path     = null;

            if($request->hasFile('file')){
                $model = GaleriaVisitante::create([
                    "visitante_id" => $request->get("id"),
                    'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
                $file = $request->file( 'file' );
                $extension = $file->getClientOriginalExtension();
                $filename= "cod".$model->id."-visitanteid".$request->get("id").'.'.$extension;
                // $path      = Storage::putFileAs( 'visitantes', $file, $filename);
                $path      = Storage::putFile( 'visitantes', $file, 'public' );
                // $path = $file->storeAs('visitantes', $filename, 'public');
                if($path == null || $path == 0){
                    $model->delete();
                }else{
                    $url = Storage::url($path);
                    $response = $model->update([
                        'photo_path'=> $url,
                        'detalle'=> $path
                    ]);
                }
                // $path = Storage::putFileAs('/visitantes', $request->file('file'), $filename);
                // $path = Storage::disk('s3')->put('visitantes', $file);
            }
            return response()->json([
                "isRequest"=> true,
                "success" => $request->hasFile('file') && ($path != null || $path != 0),
                "messageError" => !$request->hasFile('file') && ($path == null || $path == 0),
                "message" => $path != null || $path != 0 ? "Archivos subidos" : "Archivos no subidos",
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

    public function getGaleriaVisitante(Request $request){
        try{
            $responsse = GaleriaVisitante::where('visitante_id','=',$request->get('visitante_id'))
                        ->with('visitante')
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
                "message" => "Consulta galeria visitantes/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }

    public function query(Request $request){
        try{
            $responsse = GaleriaVisitante::with('visitante')
                        ->orderBy('id', 'DESC')
                         ->get();
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Consulta de galeria visitantes conrrectamente..",
                "data" => $responsse
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Consulta galeria visitantes/ ".$message." Code: ".$code,
                "data" => []
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
    public function store(StoreGaleriaVisitanteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GaleriaVisitante $galeriaVisitante)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GaleriaVisitante $galeriaVisitante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGaleriaVisitanteRequest $request, GaleriaVisitante $galeriaVisitante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GaleriaVisitante $galeriavisitante)
    {
        try{
            $responseFile = Storage::delete($galeriavisitante->detalle);
            $responseData = $galeriavisitante->delete();
            return response()->json([
                "isRequest"=> true,
                "success" => $responseData && $responseFile,
                "messageError" => !$responseData && !$responseFile,
                "message" => $responseData && $responseFile ? "TRANSACCION CORRECTA": "TRANSACCION INCORRECTA",
                "data" => []
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Destroy galeria visitantes/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }

    public function destroyApp(GaleriaVisitante $appgaleriaVisitante)
    {
        try{
            Storage::disk('public')->delete($appgaleriaVisitante->detalle);
            $responseData = $appgaleriaVisitante->delete();
            return response()->json([
                "isRequest"=> true,
                "success" => $responseData,
                "messageError" => !$responseData,
                "message" => $responseData ? "TRANSACCION CORRECTA": "TRANSACCION INCORRECTA",
                "data" => []
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Destroy galeria visitantes/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }
}
