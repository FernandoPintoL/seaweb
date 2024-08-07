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
            /*return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "messages" => $request->hasFile('image'),
                "messagesValue" => $request->hasFile('imageValue'),
                "data" => $request->all()
            ]);*/
            
            $response = null;
            $path     = null;

            if($request->hasFile('file')){
                $model = GaleriaVisitante::create([
                    "visitante_id" => $request->get("id"), 
                    'created_at' => $request->get("created_at"),
                    'updated_at' => $request->get("updated_at")
                ]);
                $file = $request->file( 'file' );
                $extension = $file->getClientOriginalExtension();
                $filename= "cod".$model->id."-visitanteid".$request->get("id").'.'.$extension;
                $path = $file->storeAs('visitantes', $filename, 'public');
                //$path = Storage::putFileAs('/visitantes', $request->file('file'), $filename);
                //$path = Storage::disk('s3')->put('visitantes', $file);
                
                /*ACTUALIZA LA TABLA CON LA RUTA*/
                $url = Storage::url($path);
                $response = $model->update([
                    'photo_path'=> $url,
                    'detalle'=> $path
                ]);
            }
            return response()->json([
                "isRequest"=> true,
                "success" => $request->hasFile('file'),
                "messageError" => !$request->hasFile('file'),
                "message" => isset($path) ? "Archivos subidos" : "Archivos no subidos",
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

    public function query(Request $request){
        try{
            $responsse = GaleriaVisitante::with('visitante')
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
    public function destroy(GaleriaVisitante $galeriaVisitante)
    {
        //
    }
}