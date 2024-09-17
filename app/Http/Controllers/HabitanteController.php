<?php
namespace App\Http\Controllers;
use App\Models\Habitante;
use App\Models\Perfil;
use App\Http\Requests\StoreHabitanteRequest;
use App\Http\Requests\UpdateHabitanteRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class HabitanteController extends Controller
{
    public function validation($model_perfil){
        $validator = Validator::make($model_perfil, [
            'email' => ['unique:perfils'],
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

    /**
     * Display a listing of the resource.
     */
    public function query(Request $request){
        try{

            $queryStr  = $request->get( 'query' );
            $queryUpper = strtoupper($queryStr);
            $responsse  = [];
            if($request->get('skip') == null && $request->get('take') == null){
                $responsse = DB::table('habitantes as h')
                        ->select('h.id as id',
                                'h.*',
                                'p.id as id_perfil',
                                'p.name',
                                'p.nroDocumento',
                                'p.celular',
                                'vd.id as id_vivienda',
                                'vd.nroVivienda')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
                        ->where('p.nroDocumento','LIKE','%'.$queryUpper.'%')
                        ->orWhere('p.name','LIKE','%'.$queryUpper.'%')
                        ->orWhere('vd.nroVivienda','LIKE','%'.$queryUpper.'%')
                        ->orderBy('id', 'DESC')
                        ->get();
            }else{
                $skip = $request->get('skip');
                $take = $request->get('take');
                $responsse = DB::table('habitantes as h')
                        ->select('h.id as id',
                                'h.*',
                                'p.id as id_perfil',
                                'p.name',
                                'p.nroDocumento',
                                'p.celular',
                                'vd.id as id_vivienda',
                                'vd.nroVivienda')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
                        ->where('p.nroDocumento','LIKE','%'.$queryUpper.'%')
                        ->orWhere('p.name','LIKE','%'.$queryUpper.'%')
                        ->orWhere('vd.nroVivienda','LIKE','%'.$queryUpper.'%')
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
                "messageError" => "$str datos encontrados",
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

    public function index()
    {
        $responsse = DB::table('habitantes as h')
                        ->select('h.id as id','h.*','p.id as id_perfil','p.name','p.nroDocumento','vd.id as id_vivienda','vd.nroVivienda')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
                        ->skip(0)
                        ->take(20)
                        ->orderBy('id', 'DESC')
                        ->get();
        return Inertia::render("Habitante/Index", ['listado'=> $responsse]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Habitante/CreateUpdate");
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHabitanteRequest $request)
    {
        try{
            $perfil = [];
            if($request->isMobile){
                $perfil    = $request->perfil;
                if($perfil['email'] != null){
                    $validator = Validator::make($perfil, [
                        'email' => ['unique:perfils']
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
                if($perfil['nroDocumento'] != null){

                    $validator = Validator::make($perfil, [
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
                // $this->validation($perfil);
                $perfil = Perfil::create($perfil);
            }else{
                $perfil = Perfil::create($request->all());
            }
            $responsse = Habitante::create([
                'isDuenho' => $request->isDuenho,
                'isDependiente' => $request->isDependiente,
                'responsable_id' => $request->responsable_id == 0 || $request->responsable_id == null ? null : $request->responsable_id,
                'vivienda_id' => $request->vivienda_id,
                'perfil_id' => $perfil->id,
                'profile_photo_path' => '',
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ]);
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

    /**
     * Display the specified resource.
     */
    public function show(Habitante $apphabitante)
    {

    }

    public function getVivienda($idvivienda)
    {
        try{
            $habitante = Habitante::where('vivienda_id','=',$idvivienda)->first();
            // $perfil    = Perfil::findOrFail( $habitante->perfil_id );
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Consulta Habitante realizada correctamente...",
                "messageError" => "",
                "data" => $habitante->perfil,
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

    public function getResidente($idresidente)
    {
        try{
            $responsse = DB::table('habitantes as h')
                        ->select('h.*','p.*','td.*','vd.*')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('viviendas as vd', 'h.vivienda_id', '=', 'h.id')
                        ->join('tipo_documentos as td', 'p.tipo_documento_id', '=', 'td.id')
                        ->where('h.id','=',$idresidente)
                        ->first();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Consulta Residente realizada correctamente...",
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
    public function edit(Habitante $habitante)
    {
        $perfil = $habitante->perfil;
        $vivienda = $habitante->vivienda;
        $habitante->perfil = $perfil;
        $habitante->vivienda = $vivienda;
        return Inertia::render("Habitante/CreateUpdate", ['model'=> $habitante]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHabitanteRequest $request, Habitante $habitante)
    {
        try{
            if($request->isMobile){
                $perfil    = $request->perfil;
                if($perfil['email'] != null && $perfil['email'] != $habitante->perfil->email){
                    $validator = Validator::make($perfil, [
                        'email' => ['unique:perfils']
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

                if($perfil['nroDocumento'] != null && $perfil['nroDocumento'] != $habitante->perfil->nroDocumento){
                    $validator = Validator::make($perfil, [
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
                $perfilUpdate = $habitante->perfil;
                $perfilUpdate->update($request->perfil);
            }
            $responsse = $habitante->update([
                'isDuenho' => $request->isDuenho,
                'isDependiente' => $request->isDependiente,
                'responsable_id' => $request->responsable_id == 0 || $request->responsable_id == null ? null : $request->responsable_id,
                'perfil_id' => $request->perfil_id,
                'vivienda_id' => $request->vivienda_id,
                'profile_photo_path' => '',
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ]);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Actualización completo" : "Error!!!",
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
    public function destroy(Habitante $apphabitante)
    {
        try{
            /* return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Verificacion",
                "data" => $apphabitante
            ]);  */
            $perfil = Perfil::findOrFail($apphabitante->perfil_id);
            $response = $perfil->delete();
            $habitante = Habitante::findOrFail($apphabitante->id);
            $response = $habitante->delete();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $response,
                "isMessageError" => $response,
                "message" => $response != null ? "completo" : "Error!!!",
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
}