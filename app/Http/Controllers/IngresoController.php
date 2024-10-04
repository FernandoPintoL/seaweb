<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Perfil;
use App\Models\Visitante;
use App\Models\Vehiculo;
use App\Http\Requests\StoreIngresoRequest;
use App\Http\Requests\UpdateIngresoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class IngresoController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $responsse = [];
            if($request->get('skip') == null && $request->get('take') == null){
                $responsse = DB::table('ingresos as i')
                        ->select('i.id as id',
                                'i.*',
                                'h.id as id_residente',
                                'p.name as name_residente',
                                'p.nroDocumento as nroDocumento_residente',
                                'vvd.id as id_vivienda',
                                'vvd.nroVivienda',
                                'v.id as id_visitante',
                                'v.is_permitido',
                                'v.description_is_no_permitido',
                                'pv.nroDocumento as nroDocumento_visitante',
                                'pv.name as name_visitante',
                                'tv.id as tv_id',
                                'tv.sigla as tv_sigla',
                                'tv.detalle as tv_detalle')
                        ->join('habitantes as h', 'h.id', '=', 'i.residente_habitante_id')
                        ->join('viviendas as vvd', 'h.vivienda_id', '=', 'vvd.id')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('visitantes as v', 'v.id', '=', 'i.visitante_id')
                        ->join('perfils as pv', 'v.perfil_id', '=', 'pv.id')
                        ->join('tipo_visitas as tv', 'i.tipo_visita_id', '=', 'tv.id')
                        ->where('p.name','LIKE','%'.$queryUpper.'%')
                        ->orWhere('pv.name','LIKE','%'.$queryUpper.'%')
                        ->orderBy('id', 'DESC')
                        ->get();
            }else{
                $skip = $request->get('skip');
                $take = $request->get('take');
                $responsse = DB::table('ingresos as i')
                        ->select('i.id as id',
                                'i.*',
                                'h.id as id_residente',
                                'p.name as name_residente',
                                'p.nroDocumento as nroDocumento_residente',
                                'vvd.id as id_vivienda',
                                'vvd.nroVivienda',
                                'v.id as id_visitante',
                                'v.is_permitido',
                                'v.description_is_no_permitido',
                                'pv.nroDocumento as nroDocumento_visitante',
                                'pv.name as name_visitante',
                                'tv.id as tv_id',
                                'tv.sigla as tv_sigla',
                                'tv.detalle as tv_detalle')
                        ->join('habitantes as h', 'h.id', '=', 'i.residente_habitante_id')
                        ->join('viviendas as vvd', 'h.vivienda_id', '=', 'vvd.id')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('visitantes as v', 'v.id', '=', 'i.visitante_id')
                        ->join('perfils as pv', 'v.perfil_id', '=', 'pv.id')
                        ->join('tipo_visitas as tv', 'i.tipo_visita_id', '=', 'tv.id')
                        ->where('p.name','LIKE','%'.$queryUpper.'%')
                        ->orWhere('pv.name','LIKE','%'.$queryUpper.'%')
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

    public function queryDate(Request $request){
        try{
            $start    = $request->get('start');
            $end    = $request->get('end');
            $responsse = DB::table('ingresos as i')
                        ->select('i.id as id',
                                'i.*',
                                'h.id as id_residente',
                                'p.name as name_residente',
                                'p.nroDocumento as nroDocumento_residente',
                                'vvd.id as id_vivienda',
                                'vvd.nroVivienda',
                                'v.id as id_visitante',
                                'v.is_permitido',
                                'v.description_is_no_permitido',
                                'pv.nroDocumento as nroDocumento_visitante',
                                'pv.name as name_visitante',
                                'tv.id as tv_id',
                                'tv.sigla as tv_sigla',
                                'tv.detalle as tv_detalle')
                        ->join('habitantes as h', 'h.id', '=', 'i.residente_habitante_id')
                        ->join('viviendas as vvd', 'h.vivienda_id', '=', 'vvd.id')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('visitantes as v', 'v.id', '=', 'i.visitante_id')
                        ->join('perfils as pv', 'v.perfil_id', '=', 'pv.id')
                        ->join('tipo_visitas as tv', 'i.tipo_visita_id', '=', 'tv.id')
                        ->whereBetween('i.created_at',[$start, $end])
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listado = DB::table('ingresos as i')
                        ->select('i.id as id',
                                'i.*',
                                'h.id as id_residente',
                                'p.name as name_residente',
                                'p.nroDocumento as nroDocumento_residente',
                                'vvd.id as id_vivienda',
                                'vvd.nroVivienda',
                                'v.id as id_visitante',
                                'v.is_permitido',
                                'v.description_is_no_permitido',
                                'pv.nroDocumento as nroDocumento_visitante',
                                'pv.name as name_visitante',
                                'tv.id as tv_id',
                                'tv.sigla as tv_sigla',
                                'tv.detalle as tv_detalle')
                        ->join('habitantes as h', 'h.id', '=', 'i.residente_habitante_id')
                        ->join('viviendas as vvd', 'h.vivienda_id', '=', 'vvd.id')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('visitantes as v', 'v.id', '=', 'i.visitante_id')
                        ->join('perfils as pv', 'v.perfil_id', '=', 'pv.id')
                        ->join('tipo_visitas as tv', 'i.tipo_visita_id', '=', 'tv.id')
                        ->skip(0)
                        ->take(10)
                        ->orderBy('id', 'DESC')
                        ->get();
        return Inertia::render("Ingreso/Index", ['listado'=> $listado]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Ingreso/CreateUpdate");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIngresoRequest $request)
    {
        try{
            if($request->get('black_list')){
                $visitante = Visitante::findOrFail( $request->get('visitante_id'));
                $visitante->update( [
                        'is_permitido' => false,
                        'description_is_no_permitido' => $request->get('detalle')
                ] );
            }
            $responsse = Ingreso::create([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle'=> $request->detalle,
                // 'detalle_salida'=> $request->detalle_salida,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id,// ? $visitante->id : $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id'=> $request->autoriza_habitante_id,
                'vehiculo_id'=> $request->vehiculo_id == 0 ? null : $request->vehiculo_id, ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'user_id' => $request->user_id == 0 ? auth()->user()->id : $request->user_id,///FK
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ]);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null || $responsse != 0,
                "isMessageError" => $responsse != null || $responsse != 0,
                "message" => $responsse != null ? "Registro de datos correcto" : "Error!!!",
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

    public function registerSalida(Request $request, Ingreso $appingreso){
        try{
            $responsse = $appingreso->update([
                'detalle_salida'=> $request->detalle_salida,
                'salida_created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->salida_created_at,
                'salida_updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->salida_updated_at
            ]);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Datos de salida actualizados correctamente" : "Error!!!",
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
    public function show(Ingreso $appingreso)
    {
        try{
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $appingreso != null,
                "isMessageError" => $appingreso != null,
                "message" => $appingreso != null ? "Registro completo" : "Error!!!",
                "messageError" => "",
                "data" => $appingreso,
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
    public function edit(Ingreso $ingreso)
    {
        return Inertia::render("Ingreso/CreateUpdate",['model'=>$ingreso, 'isRegister' => false]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIngresoRequest $request, Ingreso $appingreso)
    {
        try{
            if($request->get('black_list')){
                $visitante = Visitante::findOrFail( $request->get('visitante_id'));
                $visitante->update( [
                        'is_permitido' => false,
                        'description_is_no_permitido' => $request->get('detalle')
                ] );
            }
            $responsse = $appingreso->update([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle'=> $request->detalle,
                'detalle_salida'=> $request->detalle_salida,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id'=> $request->autoriza_habitante_id,
                'vehiculo_id'=> $request->vehiculo_id != 0 ? $request->vehiculo_id : null,  ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'user_id' => $request->user_id,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at,
                'created_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at,
                'salida_created_at' => $request->salida_created_at == null ? null : $request->salida_created_at,

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

    public function updateIngreso(UpdateIngresoRequest $request, Ingreso $ingreso)
    {
        try{
            $responsse = $ingreso->update([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle'=> $request->detalle,
                'detalle_salida'=> $request->detalle_salida,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id'=> $request->autoriza_habitante_id,
                'vehiculo_id'=> $request->vehiculo_id != 0 ? $request->vehiculo_id : null,  ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at,
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'salida_created_at' => $request->salida_created_at == null ? null : $request->salida_created_at,
                //'user_id' => $request->user_id == 0 ? auth()->user()->id : $request->user_id,
            ]);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Solicitud completo" : "Error!!!",
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
    public function destroy(Ingreso $ingreso)
    {
        try{
            $responseData  = $ingreso->delete();
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responseData != 0 && $responseData != null,
                "isMessageError" => !$responseData != 0 || $responseData == null,
                "message" => $responseData != 0 && $responseData != null? "TRANSACCION CORRECTA": "TRANSACCION INCORRECTA",
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
