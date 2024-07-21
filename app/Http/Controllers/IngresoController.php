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

class IngresoController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            $response = Ingreso::where('id','LIKE',"%".$queryStr."%")
                        ->with('residente')
                        ->with('visitante')
                        ->with('vehiculo')
                        ->with('tipoVisita')
                        ->orderBy('id', 'DESC')
                        ->get();
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Consulta Ingreso realizada correctamente...",
                "data" => $response
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Consulta ingreso/ ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listado = Vehiculo::all();
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
            /* return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Llegando de la api..",
                "data" => $request->perfil
            ]); */
            // if($request->isMobile){
                /*$perfilrequest = $request->perfil;
                $vehiculorequest = $request->vehiculo;

                //creamos perfil
                if($request->isNewVisitante){
                    $perfil = Perfil::create($perfilrequest);
                    $visitante = Visitante::create([
                        'perfil_id' => $perfil->id
                    ]);
                    $idVisitante = $visitante->id;
                }

                if($request->isIngresoConVehiculo){
                    if($request->isNewVehiculo){
                        $vehiculo = Vehiculo::create( $vehiculorequest );
                        $idVehiculo = $vehiculo->id;
                    }else{
                        $idVehiculo = $request->vehiculo_id;
                    }
                }else{
                    $idVehiculo = null;
                }*/

                
            // }else{
                // $perfil = Perfil::create($request->all());
                //TODAVIA NO CREA DESDE LA WEB
            // }
            $responsse = Ingreso::create([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle'=> $request->detalle,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id,// ? $visitante->id : $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id'=> $request->autoriza_habitante_id,
                'vehiculo_id'=> $request->vehiculo_id == 0 ? null : $request->vehiculo_id, ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'user_id' => $request->user_id == 0 ? auth()->user()->id : $request->user_id,///FK
            ]);
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

    /**
     * Display the specified resource.
     */
    public function show(Ingreso $appingreso)
    {
        try{
            /*return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Llegando de la api..",
                "data" => $appingreso->perfil
            ]);*/
            $responsse = $appingreso;
            /*$responsse = $appingreso->update([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle'=> $request->detalle,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id, ///FK
                'vivienda_id' => $request->vivienda_id, ///FK
                'autoriza_habitante_id'=> $request->autoriza_habitante_id,
                'vehiculo_id'=> $request->vehiculo_id == 0 ? null : $request->vehiculo_id, ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'user_id' => $request->user_id,///FK
            ]);*/
            
            return response()->json([
                "isRequest"=> true,
                "success" => $appingreso != null,
                "messageError" => $appingreso != null,
                "message" => $appingreso != null ? "Registro completo" : "Error!!!",
                "data" => $appingreso
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
    public function edit(Ingreso $ingreso)
    {
        return Inertia::render("Ingreso/CreateUpdate",['model'=>$ingreso]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIngresoRequest $request, Ingreso $appingreso)
    {
        try{
            /*return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Llegando de la api..",
                "data" => $request->all()
            ]); */
            //creamos perfil
                /*if($request->isNewVisitante){
                    $profileId     = $request->perfil->id;
                    $perfil = Perfil::findOrFail($profileId);
                    $response  = $perfil->update( $request->perfil );
                }
                if($request->isIngresoConVehiculo){
                    if($request->isNewVehiculo){
                        if($request->vehiculo_id == 0){
                            $vehiculo = Vehiculo::create($request->vehiculo);
                            $idVehiculo = $vehiculo->id;
                        }else{
                            $vehiculoID = $request->vehiculo->id;
                            $vehiculo = Vehiculo::findOrFail($vehiculoID);
                            $vehiculo->update($request->vehiculo);
                            $idVehiculo = $vehiculoID;
                        }
                    }else{
                        $idVehiculo = $request->vehiculo_id;
                    }
                }else{
                    $idVehiculo = null;
                }*/
            $responsse = $appingreso->update([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle'=> $request->detalle,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id'=> $request->autoriza_habitante_id,
                'vehiculo_id'=> $request->vehiculo_id != 0 ? $request->vehiculo_id : null,  ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'user_id' => $request->user_id,///FK
            ]);
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

    public function updateIngreso(UpdateIngresoRequest $request, Ingreso $ingreso)
    {
        try{
            $responsse = $ingreso->update([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle'=> $request->detalle,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id'=> $request->autoriza_habitante_id,
                'vehiculo_id'=> $request->vehiculo_id != 0 ? $request->vehiculo_id : null,  ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                //'user_id' => $request->user_id == 0 ? auth()->user()->id : $request->user_id,
            ]);
            return response()->json([
                "isRequest"=> true,
                "success" => $responsse != null,
                "messageError" => $responsse != null,
                "message" => $responsse != null ? "Solicitud completo" : "Error!!!",
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
    public function destroy(Ingreso $appingreso)
    {
        //
    }
}