<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Condominio;
use App\Models\Perfil;
use App\Models\Vehiculo;
use App\Models\Visitante;
use App\Models\TipoVisita;
use App\Http\Requests\StoreIngresoRequest;
use App\Http\Requests\UpdateIngresoRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class IngresoController extends Controller
{
    public function querySkipTake(Request $request) {}
    public function query(Request $request)
    {
        try {
            $responsse = [];
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);

            $condominioId = $request->get('condominio_id');

            $created_start    = $request->get('created_at_start');
            $created_end    = $request->get('created_at_end');

            $salida_start    = $request->get('salida_created_at_start');
            $salida_end    = $request->get('salida_created_at_end');

            $salidas_registradas = $request->get('salidas_registradas');

            $skip = $request->get('skip');
            $take = $request->get('take');

            $responsse = DB::table('ingresos as i')
                ->select(
                    'i.id as id',
                    'i.*',
                    'h.id as id_residente',
                    'p.name as name_residente',
                    'p.nroDocumento as nroDocumento_residente',
                    'vvd.id as id_vivienda',
                    'vvd.nroVivienda',
                    'c.propietario',
                    'v.id as id_visitante',
                    'v.is_permitido',
                    'v.description_is_no_permitido',
                    'pv.nroDocumento as nroDocumento_visitante',
                    'pv.name as name_visitante',
                    'tv.id as tv_id',
                    'tv.sigla as tv_sigla',
                    'tv.detalle as tv_detalle'
                )
                ->join('habitantes as h', 'h.id', '=', 'i.residente_habitante_id')
                ->join('viviendas as vvd', 'h.vivienda_id', '=', 'vvd.id')
                ->join(
                    'condominios as c',
                    'vvd.condominio_id',
                    '=',
                    'c.id'
                )
                ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                ->join('visitantes as v', 'v.id', '=', 'i.visitante_id')
                ->join('perfils as pv', 'v.perfil_id', '=', 'pv.id')
                ->join('tipo_visitas as tv', 'i.tipo_visita_id', '=', 'tv.id')

                // Aseguramos que condominio_id se esté utilizando
                ->where('vvd.condominio_id', $condominioId)

                // Filtro opcional por nombre de residente o visitante si $queryUpper no está vacío
                ->when(!empty($queryUpper), function ($query) use ($queryUpper) {
                    $query->where(function ($q) use ($queryUpper) {
                        $q->where('p.name', 'LIKE', '%' . $queryUpper . '%')
                            ->orWhere('pv.nroDocumento', 'LIKE', '%' . $queryUpper . '%')
                            ->orWhere('vvd.nroVivienda', 'LIKE', '%' . $queryUpper . '%')
                            ->orWhere('pv.name', 'LIKE', '%' . $queryUpper . '%');
                    });
                })
                // Filtro para verificar que salida_created_at sea null o no
                ->when(
                    !is_null($created_start) && !is_null($created_end),
                    function ($query) use ($created_start, $created_end) {
                        // $query->where('i.created_at', $createdAt);
                        $query->whereBetween('i.created_at', [$created_start, $created_end]);
                    }
                )
                // Filtro para verificar que salida_created_at sea null o no
                ->when(!is_null($salida_start) && !is_null($salida_end), function ($query) use ($salida_start, $salida_end) {
                    // $query->where('i.salida_created_at', $salidaCreatedAt);
                    $query->whereBetween('i.salida_created_at', [$salida_start, $salida_end]);
                })

                ->when(!is_null($salidas_registradas) && $salidas_registradas, function ($query) use ($salidas_registradas) {
                    $query->whereNotNull('i.salida_created_at');
                })

                ->when(!is_null($salidas_registradas) && !$salidas_registradas, function ($query) use ($salidas_registradas) {
                    $query->whereNull('i.salida_created_at');
                })
                // SKIP O TAKE
                ->when(!is_null($skip) && !is_null($take), function ($query) use ($skip, $take) {
                    // $query->where('i.salida_created_at', $salidaCreatedAt);
                    // $query->whereBetween('i.salida_created_at', [$salida_start, $salida_end]);
                    $query->skip($skip)->take($take);
                })
                // Ordenar por ID de ingreso en orden descendente
                ->orderBy('i.id', 'DESC')
                // Obtener los resultados
                ->get();
            $cantidad = count($responsse);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function queryDate(Request $request)
    {

        try {
            $start    = $request->get('start');
            $end    = $request->get('end');
            $responsse = DB::table('ingresos as i')
                ->select(
                    'i.id as id',
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
                    'tv.detalle as tv_detalle'
                )
                ->join('habitantes as h', 'h.id', '=', 'i.residente_habitante_id')
                ->join('viviendas as vvd', 'h.vivienda_id', '=', 'vvd.id')
                ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                ->join('visitantes as v', 'v.id', '=', 'i.visitante_id')
                ->join('perfils as pv', 'v.perfil_id', '=', 'pv.id')
                ->join('tipo_visitas as tv', 'i.tipo_visita_id', '=', 'tv.id')
                ->whereBetween('i.created_at', [$start, $end])
                ->orderBy('id', 'DESC')
                ->get();
            $cantidad = count($responsse);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
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
        Gate::authorize('viewAny', Ingreso::class);
        $user = auth()->user();
        $crear = $user->canCrear('INGRESO');
        $editar = $user->canEditar('INGRESO');
        $eliminar = $user->canEliminar('INGRESO');
        $isAdmin = $user->isSuperAdmin();
        $idUserCondominio = null;
        if ($isAdmin) {
            $condominios = Condominio::all();
        } else {
            /// NO ES USUARIO ADMINISTRADOR
            $condominios = $user->condominios->toArray();
            $condominio = $user->condominio;
            if ($condominio) {
                // array_push($condominios, $condominio);
                $idUserCondominio = $user->id;
            }
        }

        $listado = DB::table('ingresos as i')
            ->select(
                'i.id as id',
                'i.*',
                'h.id as id_residente',
                'p.name as name_residente',
                'p.nroDocumento as nroDocumento_residente',
                'vvd.id as id_vivienda',
                'vvd.nroVivienda',
                'c.propietario',
                'v.id as id_visitante',
                'v.is_permitido',
                'v.description_is_no_permitido',
                'pv.nroDocumento as nroDocumento_visitante',
                'pv.name as name_visitante',
                'tv.id as tv_id',
                'tv.sigla as tv_sigla',
                'tv.detalle as tv_detalle'
            )
            ->join('habitantes as h', 'h.id', '=', 'i.residente_habitante_id')
            ->join('viviendas as vvd', 'h.vivienda_id', '=', 'vvd.id')
            ->join('condominios as c', 'vvd.condominio_id', '=', 'c.id')
            ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
            ->join('visitantes as v', 'v.id', '=', 'i.visitante_id')
            ->join('perfils as pv', 'v.perfil_id', '=', 'pv.id')
            ->join('tipo_visitas as tv', 'i.tipo_visita_id', '=', 'tv.id')
            ->when(!is_null($idUserCondominio), function ($query) use ($idUserCondominio) {
                // Filtrar por el condominio del usuario cuando no sea admin
                return $query->where('i.user_id', '=', $idUserCondominio);
            })
            ->skip(0)
            ->take(10)
            ->orderBy('id', 'DESC')
            ->get();

        return Inertia::render("Ingreso/Index", [
            'listado' => $listado,
            'condominios' => $condominios,
            'crear' => $crear,
            'editar' => $editar,
            'eliminar' => $eliminar
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Ingreso::class);
        $user = auth()->user();
        $crear = $user->canCrear('INGRESO');
        $editar = $user->canEditar('INGRESO');
        $isAdmin = $user->isSuperAdmin();
        $condominio_ids = [];
        if (!$isAdmin) {
            $condominios = $user->condominios;
            $condominio_ids = $condominios->pluck('id')->toArray();
        }
        // Construcción de la consulta
        $query = DB::table('habitantes as h')
            ->select(
                'h.id as id',
                'h.*',
                'p.id as id_perfil',
                'p.name',
                'p.nroDocumento',
                'p.celular',
                'vd.id as id_vivienda',
                'vd.nroVivienda',
                'c.razonSocial',
                'c.propietario'
            )
            ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
            ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
            ->join('condominios as c', 'vd.condominio_id', '=', 'c.id')
            ->orderBy('h.id', 'DESC');
        // Si el usuario NO es admin, se filtran los resultados por los condominios asignados
        if (!$isAdmin && !empty($condominio_ids)) {
            $query->whereIn('vd.condominio_id', $condominio_ids);
        }
        // Ejecuta la consulta
        $residentes = $query->get();
        $visitantes = Visitante::with('perfil')->get();
        $vehiculos = Vehiculo::all();
        $tipo_visitas = TipoVisita::all();

        return Inertia::render("Ingreso/CreateUpdate", [
            'residentes' => $residentes,
            'visitantes' => $visitantes,
            'vehiculos' => $vehiculos,
            'tipo_visitas' => $tipo_visitas,
            'crear' => $crear,
            'editar' => $editar
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIngresoRequest $request)
    {
        try {
            if ($request->get('black_list')) {
                $visitante = Visitante::findOrFail($request->get('visitante_id'));
                $visitante->update([
                    'is_permitido' => false,
                    'description_is_no_permitido' => $request->get('detalle')
                ]);
            }
            $responsse = Ingreso::create([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle' => $request->detalle,
                // 'detalle_salida'=> $request->detalle_salida,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id, // ? $visitante->id : $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id' => $request->autoriza_habitante_id,
                'vehiculo_id' => $request->vehiculo_id == 0 ? null : $request->vehiculo_id, ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'user_id' => $request->user_id == 0 ? auth()->user()->id : $request->user_id, ///FK
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ]);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $responsse != null || $responsse != 0,
                "isMessageError" => $responsse != null || $responsse != 0,
                "message" => $responsse != null ? "Registro de datos correcto" : "Error!!!",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function registerSalida(Request $request, Ingreso $appingreso)
    {
        try {
            $responsse = $appingreso->update([
                'detalle_salida' => $request->detalle_salida,
                'salida_created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->salida_created_at,
                'salida_updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->salida_updated_at
            ]);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Datos de salida actualizados correctamente" : "Error!!!",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
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
        try {
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $appingreso != null,
                "isMessageError" => $appingreso != null,
                "message" => $appingreso != null ? "Registro completo" : "Error!!!",
                "messageError" => "",
                "data" => $appingreso,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
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
        Gate::authorize('update', $ingreso);
        $user = auth()->user();
        $crear = $user->canCrear('INGRESO');
        $editar = $user->canEditar('INGRESO');
        $isAdmin = $user->isSuperAdmin();
        $condominio_ids = [];
        if (!$isAdmin) {
            $condominios = $user->condominios;
            $condominio_ids = $condominios->pluck('id')->toArray();
        }
        // Construcción de la consulta
        $query = DB::table('habitantes as h')
            ->select(
                'h.id as id',
                'h.*',
                'p.id as id_perfil',
                'p.name',
                'p.nroDocumento',
                'p.celular',
                'vd.id as id_vivienda',
                'vd.nroVivienda',
                'c.razonSocial',
                'c.propietario'
            )
            ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
            ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
            ->join('condominios as c', 'vd.condominio_id', '=', 'c.id')
            ->orderBy('h.id', 'DESC');
        // Si el usuario NO es admin, se filtran los resultados por los condominios asignados
        if (!$isAdmin && !empty($condominio_ids)) {
            $query->whereIn('vd.condominio_id', $condominio_ids);
        }
        // Ejecuta la consulta
        $residentes = $query->get();
        $visitantes = Visitante::with('perfil')->get();
        $vehiculos = Vehiculo::all();
        $tipo_visitas = TipoVisita::all();
        return Inertia::render("Ingreso/CreateUpdate", [
            'model' => $ingreso,
            'residentes' => $residentes,
            'visitantes' => $visitantes,
            'vehiculos' => $vehiculos,
            'tipo_visitas' => $tipo_visitas,
            'isRegister' => false,
            'crear' => $crear,
            'editar' => $editar
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIngresoRequest $request, Ingreso $appingreso)
    {
        try {
            if ($request->get('black_list')) {
                $visitante = Visitante::findOrFail($request->get('visitante_id'));
                $visitante->update([
                    'is_permitido' => false,
                    'description_is_no_permitido' => $request->get('detalle')
                ]);
            }
            $responsse = $appingreso->update([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle' => $request->detalle,
                'detalle_salida' => $request->detalle_salida,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id' => $request->autoriza_habitante_id,
                'vehiculo_id' => $request->vehiculo_id != 0 ? $request->vehiculo_id : null,  ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'user_id' => $request->user_id,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at,
                'created_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at,
                'salida_created_at' => $request->salida_created_at == null ? null : $request->salida_created_at,

            ]);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Registro completo" : "Error!!!",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
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
        try {
            $responsse = $ingreso->update([
                'tipo_ingreso' => $request->tipo_ingreso,
                'detalle' => $request->detalle,
                'detalle_salida' => $request->detalle_salida,
                'isAutorizado' => $request->isAutorizado,
                'visitante_id' => $request->visitante_id, ///FK
                'residente_habitante_id' => $request->residente_habitante_id, ///FK
                'autoriza_habitante_id' => $request->autoriza_habitante_id,
                'vehiculo_id' => $request->vehiculo_id != 0 ? $request->vehiculo_id : null,  ///FK
                'tipo_visita_id' => $request->tipo_visita_id, ///FK
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at,
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'salida_created_at' => $request->salida_created_at == null ? null : $request->salida_created_at,
                //'user_id' => $request->user_id == 0 ? auth()->user()->id : $request->user_id,
            ]);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Solicitud completo" : "Error!!!",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
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
        try {
            $responseData  = $ingreso->delete();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $responseData != 0 && $responseData != null,
                "isMessageError" => !$responseData != 0 || $responseData == null,
                "message" => $responseData != 0 && $responseData != null ? "TRANSACCION CORRECTA" : "TRANSACCION INCORRECTA",
                "messageError" => "",
                "data" => [],
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
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
