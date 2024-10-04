<?php

namespace App\Http\Controllers;

use App\Models\Visitante;
use App\Models\Perfil;
use App\Http\Requests\StoreVisitanteRequest;
use App\Http\Requests\UpdateVisitanteRequest;
use App\Models\GaleriaVisitante;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class VisitanteController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);

            if($request->get('black_list')){
                $responsse = DB::table('visitantes as v')
                        ->select('v.id as id','v.*','p.name','p.nroDocumento', 'p.celular')
                        ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                        ->where('v.is_permitido','=', false)
                        ->orderBy('v.id', 'DESC')
                        ->get();
            }else{
                if($request->get('skip') == null && $request->get('take') == null){
                    $responsse = DB::table('visitantes as v')
                        ->select('v.id as id','v.*','p.name','p.nroDocumento', 'p.celular')
                        ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                        ->where('p.name','LIKE',"%".$queryUpper."%")
                        ->orWhere('p.nroDocumento','LIKE',"%".$queryUpper."%")
                        ->orderBy('v.id', 'DESC')
                        ->get();
                }else{
                    $skip = $request->get('skip');
                    $take = $request->get('take');
                    $responsse = DB::table('visitantes as v')
                            ->select('v.id as id','v.*','p.name','p.nroDocumento', 'p.celular')
                            ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                            ->where('p.name','LIKE',"%".$queryUpper."%")
                            ->orWhere('p.nroDocumento','LIKE',"%".$queryUpper."%")
                            ->skip($skip)
                            ->take($take)
                            ->orderBy('v.id', 'DESC')
                            ->get();
                }
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
            $responsse = Visitante::with( 'perfil' )
                        ->where('id', '=', $request->get('query'))
                        ->orderBy('id', 'DESC')
                        ->get();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Consulta visitante realizada correctamente...",
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
        $listado = $responsse = DB::table('visitantes as v')
                            ->select('v.id as id','v.*','p.name','p.nroDocumento', 'p.celular')
                            ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                            ->skip(0)
                            ->take(20)
                            ->orderBy('v.id', 'DESC')
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
                        "isSuccess" => false,
                        "isMessageError" => true,
                        "message" => $validator->errors(),
                        "messageError" => $validator->errors(),
                        "data" => [],
                        "statusCode" => 422
                    ], 422 );
                }
                $perfil = Perfil::create($perfil);
                $perfil->update( [
                    'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
            }else{
                $perfil = Perfil::create($request->all());
                $perfil->update( [
                    'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
            }
            $responsse = Visitante::create([
                'is_permitido' => $request->is_permitido,
                'descripition_is_no_permitido' => $request->descripition_is_no_permitido,
                'perfil_id' => $perfil->id,
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ]);
            $datas         = $responsse;
            $datas->perfil = $responsse->perfil;
            // $model     = Visitante::findOrFail( $responsse->id )->with('perfil');

            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse == null,
                "message" => $responsse != null ? "Registro completo" : "Error!!!",
                "messageError" => "",
                "data" => $datas,
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
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Show Visitante realizada correctamente...",
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
     * Show the form for editing the specified resource.
     */
    public function edit(Visitante $visitante)
    {
        $perfil = $visitante->perfil;
        $visitante->perfil = $perfil;
        $list_galeria = GaleriaVisitante::where('visitante_id','=',$visitante->id)->get();
        return Inertia::render("Visitante/CreateUpdate", ['model'=> $visitante, 'listado' => $list_galeria ]);
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
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
            }else{
                //ACTUALIZAR DESDE LA WEB
                $responsse = $perfil->update($request->all());
                $perfil->update( [
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
            }
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Registro completo" : "Error!!!",
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

    public function updateIsPermitido(Request $request, Visitante $visitante){
        try{
            // $visitante = Visitante::findOrFail( $request->get('id'));
            $responsse = $visitante->update( [
                    'is_permitido' => $request->get('is_permitido'),
                    'description_is_no_permitido' => $request->get('description_is_no_permitido'),
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ] );
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Registro completo" : "Error!!!",
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
                            "isSuccess" => false,
                            "isMessageError" => true,
                            "message" => $validator->errors(),
                            "messageError" => $validator->errors(),
                            "data" => [],
                            "statusCode" => 422
                        ], 422 );
                    }
                }
                //ACTUALIZACION DESDE EL MOVIL
                $responsse = $perfil->update($perfilRequest);
                $perfil->update( [
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
            }else{
                //ACTUALIZAR DESDE LA WEB
                $responsse = $perfil->update($request->all());
                $perfil->update( [
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
            }
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Actualización completa" : "Error!!!",
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
    public function destroy(Visitante $visitante)
    {
        try{
            $galerias = GaleriaVisitante::where('visitante_id','=',$visitante->id)->get();
            foreach($galerias as $galeria){
                $existe = Storage::disk( 'public' )->exists( $galeria->detalle );
            if ($existe) {
                Storage::disk('public')->delete($galeria->detalle);
            }
                $responseData = $galeria->delete();
            }
            $response = $visitante->delete();
            $delete_perfil = $visitante->perfil->delete();

            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $response,
                "isMessageError" => !$response,
                "message" => $response != null ? "Eliminado Correctamente" : "Error!!!",
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
}