<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use App\Models\Perfil;
use App\Http\Requests\StoreVisitanteRequest;
use App\Http\Requests\UpdateVisitanteRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VisitanteController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            if($request->get('black_list')){
                $responsse = DB::table('visitantes as v')
                        ->select('v.id as id','v.*','p.name','p.nroDocumento', 'p.celular')
                        ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                        // ->join('tipo_documentos as td', 'p.tipo_documento_id', '=', 'td.id')
                        ->where('v.is_permitido','=', false)
                        ->orderBy('v.created_at', 'DESC')
                        ->get();
            }else{
                $responsse = DB::table('visitantes as v')
                        ->select('v.id as id','v.*','p.name','p.nroDocumento', 'p.celular')
                        ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                        // ->join('tipo_documentos as td', 'p.tipo_documento_id', '=', 'td.id')
                        ->where('p.name','LIKE',"%".$queryStr."%")
                        ->orWhere('p.nroDocumento','LIKE',"%".$queryStr."%")
                        ->orderBy('v.created_at', 'DESC')
                        ->get();
            }
            
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Consulta visitante realizada correctamente...",
                "data" => $responsse
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Consulta visitante/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }

    public function queryId(Request $request){
        try{
            $responsse = Visitante::with( 'perfil' )
                        ->where('id', '=', $request->get('query'))
                        ->orderBy('id', 'DESC')
                        ->get();
            /*$queryStr    = $request->get('query');
            $responsse = DB::table('visitantes as v')
                        ->select('v.*','p.name','p.nroDocumento', 'p.celular', 'td.sigla', 'td.detalle')
                        ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                        ->join('tipo_documentos as td', 'p.tipo_documento_id', '=', 'td.id')
                        ->where('p.name','LIKE',"%".$queryStr."%")
                        ->orWhere('p.nroDocumento','LIKE',"%".$queryStr."%")
                        ->orderBy('v.created_at', 'DESC')
                        ->get();*/
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Consulta visitante realizada correctamente...",
                "data" => $responsse
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Consulta visitante/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listado = Visitante::with( 'perfil' )
                        ->orderBy('id', 'DESC')
                        ->get();
        return Inertia::render("Visitante/Index", ['listado'=> $listado]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Visitante/CreateUpdate");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $perfil = [];
            if($request->isMobile){
                $perfil    = $request->perfil;
                $validator = Validator::make($perfil, [
                    'name' => ['required','min:5'],
                    'nroDocumento' => ['unique:perfils'],
                    'tipo_documento_id' => ['required', 'numeric']
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
                $perfil = Perfil::create($perfil);
                $perfil->update( [ 
                    'created_at' => $request->created_at,
                    'updated_at' => $request->updated_at,
                ]); 
            }else{
                $perfil = Perfil::create($request->all());
                $perfil->update( [ 
                    'created_at' => $request->created_at,
                    'updated_at' => $request->updated_at,
                ]); 
            }
            $responsse = Visitante::create([
                'is_permitido' => $request->is_permitido,
                'descripition_is_no_permitido' => $request->descripition_is_no_permitido,
                'perfil_id' => $perfil->id,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
            ]);
            $datas         = $responsse;
            $datas->perfil = $responsse->perfil;
            // $model     = Visitante::findOrFail( $responsse->id )->with('perfil');

            return response()->json([
                "isRequest"=> true,
                "success" => $responsse != null,
                "messageError" => $responsse == null,
                "message" => $responsse != null ? "Registro completo" : "Error!!!",
                "data" => $datas
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
    public function show($appvisitante)
    {
        try{
            $responsse = Visitante::with( 'perfil' )->get();
            /*$responsse = DB::table('visitantes as m')
                        ->select('m.*','p.*','td.*')
                        ->join('perfils as p', 'm.perfil_id', '=', 'p.id')
                        ->join('tipo_documentos as td', 'p.tipo_documento_id', '=', 'td.id')
                        ->where('m.id','=',$appvisitante)
                        ->first();*/
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Show Visitante realizada correctamente...",
                "data" => $responsse
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
    public function edit(Visitante $visitante)
    {
        $perfil = $visitante->perfil;
        $visitante->perfil = $perfil;
        return Inertia::render("Visitante/CreateUpdate", ['model'=> $visitante]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitante $appvisitante)
    {
        try{
            $responsse = 0;
            $perfil = Perfil::findOrFail($appvisitante->perfil_id);
            if($request->isMobile){
                //ACTUALIZACION DESDE EL MOVIL
                $responsse = $perfil->update($request->perfil);
                $perfil->update( [
                    'updated_at' => toDay(),
                ]); 
            }else{
                //ACTUALIZAR DESDE LA WEB
                $responsse = $perfil->update($request->all());
                $perfil->update( [
                    'updated_at' => toDay(),
                ]);
            }
            return response()->json([
                "isRequest"=> true,
                "success" => $responsse != null,
                "messageError" => $responsse != null,
                "message" => $responsse != null ? "Registro completo" : "Error!!!",
                "data" => $responsse
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

    public function updateIsPermitido(Request $request, Visitante $visitante){
        try{
            // $visitante = Visitante::findOrFail( $request->get('id'));
            $responsse = $visitante->update( [
                    'is_permitido' => $request->get('is_permitido'),
                    'description_is_no_permitido' => $request->get('description_is_no_permitido')
            ] );
            return response()->json([
                "isRequest"=> true,
                "success" => $responsse != null,
                "messageError" => $responsse != null,
                "message" => $responsse != null ? "Registro completo" : "Error!!!",
                "data" => $responsse
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

    public function updateWeb(Request $request, Visitante $visitante)
    {
        /*return response()->json([
            "isRequest"=> true,
            "success" => false,
            "messageError" => true,
            "message" => "Verificación WEB",
            "data" => [
                // "email_request" => $request->perfil->email,
                // "email_habitante" => $habitante->perfil->email,
                "nroDocumento_modificado" => $request->perfil['nroDocumento'] != $visitante->perfil->nroDocumento,
                "request" => $request->all(),
                "visitante" => $visitante,
                "visitante_perfil_nroDoc" => $visitante->perfil->nroDocumento,
                "request_perfil_nroDoc" => $request->perfil['nroDocumento'],
            ]
        ]);*/
        try{
            $responsse = 0;
            $perfilRequest    = $request->perfil;
            $perfil        = $visitante->perfil;
            if($request->isMobile){
                if($perfilRequest['nroDocumento'] != $perfil->nroDocumento){
                    $validator = Validator::make($perfilRequest, [
                        'nroDocumento' => ['unique:perfils']
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
                //ACTUALIZACION DESDE EL MOVIL
                $responsse = $perfil->update($perfilRequest);
                $perfil->update( [
                    'updated_at' => toDay(),
                ]);
            }else{
                //ACTUALIZAR DESDE LA WEB
                $responsse = $perfil->update($request->all());
                $perfil->update( [
                    'updated_at' => toDay(),
                ]); 
            }
            return response()->json([
                "isRequest"=> true,
                "success" => $responsse != null,
                "messageError" => $responsse != null,
                "message" => $responsse != null ? "Actualización completa" : "Error!!!",
                "data" => $responsse
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
    public function destroy(Visitante $appvisitante)
    {
        try{
            /* return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Verificacion",
                "data" => $appvisitante
            ]); */
            $id       = $appvisitante->perfil_id;
            $response = $appvisitante->delete();
            $model = Perfil::findOrFail($id);
            $response = $model->delete();
            //$model = Visitante::findOrFail($appvisitante->id);
            //$response = $model->delete();
            return response()->json([
                "isRequest"=> true,
                "success" => $response,
                "messageError" => !$response,
                "message" => $response != null ? "Eliminado Correctamente" : "Error!!!",
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
}