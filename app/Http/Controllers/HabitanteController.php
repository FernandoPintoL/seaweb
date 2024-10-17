<?php

namespace App\Http\Controllers;

use App\Models\Habitante;
use App\Models\Vivienda;
use App\Models\Condominio;
use App\Models\Perfil;
use App\Models\TipoDocumento;
use App\Http\Requests\StoreHabitanteRequest;
use App\Http\Requests\UpdateHabitanteRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class HabitanteController extends Controller
{
    public function validation($model_perfil)
    {
        $validator = Validator::make($model_perfil, [
            'email' => ['unique:perfils'],
            'nroDocumento' => ['unique:perfils']
        ]);
        if ($validator->fails()) {
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $validator->errors(),
                "messageError" => $validator->errors(),
                "data" => [],
                "statusCode" => 422
            ], 422);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function query(Request $request)
    {
        try {

            $queryStr  = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $condominioId = $request->get('condominio_id');
            $skip = $request->get('skip');
            $take = $request->get('take');
            $responsse  = [];
            $responsse = DB::table('habitantes as h')
                ->select(
                    'h.id as id',
                    'h.*',
                    'p.id as id_perfil',
                    'p.name',
                    'p.nroDocumento',
                    'p.celular',
                    'vd.id as id_vivienda',
                    'vd.nroVivienda',
                    'c.propietario'
                )
                ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
                ->join('condominios as c', 'vd.condominio_id', '=', 'c.id')
                // Aseguramos que condominio_id se esté utilizando
                ->where('vd.condominio_id', $condominioId)
                // Filtro opcional por nombre de residente o visitante si $queryUpper no está vacío
                ->when(!empty($queryUpper), function ($query) use ($queryUpper) {
                    $query->where(function ($q) use ($queryUpper) {
                        $q->where('p.name', 'LIKE', '%' . $queryUpper . '%')
                            ->orWhere('p.nroDocumento', 'LIKE', '%' . $queryUpper . '%')
                            ->orWhere('vd.nroVivienda', 'LIKE', '%' . $queryUpper . '%')
                            ->orWhere('p.name', 'LIKE', '%' . $queryUpper . '%');
                    });
                })
                // SKIP O TAKE
                ->when(!is_null($skip) && !is_null($take), function ($query) use ($skip, $take) {
                    $query->skip($skip)->take($take);
                })
                ->orderBy('h.id', 'DESC')
                ->get();
            $cantidad = count($responsse);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "$str datos encontrados",
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

    public function index()
    {
        Gate::authorize('viewAny', Habitante::class);

        $user = auth()->user();
        $isAdmin = $user->isSuperAdmin();
        $condominio_id = null;
        if ($isAdmin) {
            $condominios = Condominio::all();
        } else {
            /// NO ES USUARIO ADMINISTRADOR
            $condominios = $user->condominios->toArray(); //QUE CONDOMINIOS TENGO ASIGNADOS
            $condominio = $user->condominio; // ES UN USUARIO CONDOMINIO
            $condominio_id = $condominio->id;
        }
        $responsse = DB::table('habitantes as h')
            ->select('h.id as id', 'h.*', 'p.id as id_perfil', 'p.name', 'p.nroDocumento', 'vd.id as id_vivienda', 'vd.nroVivienda', 'c.razonSocial')
            ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
            ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
            ->join('condominios as c', 'vd.condominio_id', '=', 'c.id')
            ->when(!is_null($condominio_id), function ($query) use ($condominio_id) {
                // Filtrar por el condominio del usuario cuando no sea admin
                return $query->where('c.id', '=', $condominio_id);
            })
            ->skip(0)
            ->take(20)
            ->orderBy('id', 'DESC')
            ->get();
        $crear = $user->canCrear('HABITANTE');
        $editar = $user->canEditar('HABITANTE');
        $eliminar = $user->canEliminar('HABITANTE');
        return Inertia::render("Habitante/Index", [
            'listado' => $responsse,
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
        Gate::authorize('create', Habitante::class);
        $user = auth()->user();
        $isAdmin = $user->isSuperAdmin();
        $viviendasAsignadas = [];
        $tipo_documento = TipoDocumento::all();
        if ($isAdmin) {
            $viviendasAsignadas = Vivienda::with('condominio')->get();
            // Obtener todos los residentes ya que es Super Admin
            $residentes = DB::table('habitantes as h')
                ->select(
                    'h.id as id',
                    'h.*',
                    'p.id as id_perfil',
                    'p.name',
                    'p.nroDocumento',
                    'p.celular',
                    'vd.id as id_vivienda',
                    'vd.nroVivienda',
                    'c.propietario'
                )
                ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
                ->join('condominios as c', 'vd.condominio_id', '=', 'c.id')
                ->where('h.isDuenho', '=', true) // Filtrar solo dueños
                ->orderBy('h.id', 'DESC')
                ->get();
        } else {
            /// NO ES USUARIO ADMINISTRADOR
            $condominios = $user->condominios; //QUE CONDOMINIOS TENGO ASIGNADOS
            // Crear un array para almacenar los IDs de los condominios
            $condominioIds = $condominios->pluck('id')->toArray();
            // Obtener solo las viviendas de los condominios asignados al usuario, junto con el condominio
            $viviendasAsignadas = Vivienda::with('condominio')
                ->whereIn('condominio_id', $condominioIds) // Filtrar por los condominios asignados al usuario
                ->get();
            // Obtener solo los residentes de los condominios asignados al usuario
            $residentes = DB::table('habitantes as h')
                ->select(
                    'h.id as id',
                    'h.*',
                    'p.id as id_perfil',
                    'p.name',
                    'p.nroDocumento',
                    'p.celular',
                    'vd.id as id_vivienda',
                    'vd.nroVivienda',
                    'c.propietario'
                )
                ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
                ->join('condominios as c', 'vd.condominio_id', '=', 'c.id')
                ->where('h.isDuenho', '=', true) // Filtrar solo dueños
                ->whereIn('vd.condominio_id', $condominioIds) // Filtrar por los condominios asignados al usuario
                ->orderBy('h.id', 'DESC')
                ->get();
        }
        $crear = $user->canCrear('HABITANTE');
        $editar = $user->canEditar('HABITANTE');
        return Inertia::render("Habitante/CreateUpdate", [
            'crear' => $crear,
            'editar' => $editar,
            'viviendas' => $viviendasAsignadas,
            'tipo_documento' => $tipo_documento,
            'residentes' => $residentes
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHabitanteRequest $request)
    {
        try {
            $perfil = [];
            if ($request->isMobile) {
                $perfil    = $request->perfil;
                if ($perfil['email'] != null) {
                    $validator = Validator::make($perfil, [
                        'email' => ['unique:perfils']
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            "isRequest" => true,
                            "isSuccess" => false,
                            "isMessageError" => true,
                            "message" => $validator->errors(),
                            "messageError" => $validator->errors(),
                            "data" => [],
                            "statusCode" => 422
                        ], 422);
                    }
                }
                if ($perfil['nroDocumento'] != null) {

                    $validator = Validator::make($perfil, [
                        'nroDocumento' => ['unique:perfils']
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            "isRequest" => true,
                            "isSuccess" => false,
                            "isMessageError" => true,
                            "message" => $validator->errors(),
                            "messageError" => $validator->errors(),
                            "data" => [],
                            "statusCode" => 422
                        ], 422);
                    }
                }
                // $this->validation($perfil);
                $perfil = Perfil::create($perfil);
            } else {
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
    /**
     * Display the specified resource.
     */
    public function show(Habitante $apphabitante) {}

    public function getVivienda($idvivienda)
    {
        try {
            $habitante = Habitante::where('vivienda_id', '=', $idvivienda)->first();
            // $perfil    = Perfil::findOrFail( $habitante->perfil_id );
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Consulta Habitante realizada correctamente...",
                "messageError" => "",
                "data" => $habitante->perfil,
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

    public function getResidente($idresidente)
    {
        try {
            $responsse = DB::table('habitantes as h')
                ->select('h.*', 'p.*', 'td.*', 'vd.*')
                ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                ->join('viviendas as vd', 'h.vivienda_id', '=', 'h.id')
                ->join('tipo_documentos as td', 'p.tipo_documento_id', '=', 'td.id')
                ->where('h.id', '=', $idresidente)
                ->first();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Consulta Residente realizada correctamente...",
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
     * Show the form for editing the specified resource.
     */
    public function edit(Habitante $habitante)
    {
        Gate::authorize('update', $habitante);
        $user = auth()->user();
        $isAdmin = $user->isSuperAdmin();
        $viviendasAsignadas = [];
        $tipo_documento = TipoDocumento::all();
        if ($isAdmin) {
            $viviendasAsignadas = Vivienda::with('condominio')->get();
            // Obtener todos los residentes ya que es Super Admin
            $residentes = DB::table('habitantes as h')
                ->select(
                    'h.id as id',
                    'h.*',
                    'p.id as id_perfil',
                    'p.name',
                    'p.nroDocumento',
                    'p.celular',
                    'vd.id as id_vivienda',
                    'vd.nroVivienda',
                    'c.propietario'
                )
                ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
                ->join('condominios as c', 'vd.condominio_id', '=', 'c.id')
                ->where('h.isDuenho', '=', true) // Filtrar solo dueños
                ->orderBy('h.id', 'DESC')
                ->get();
        } else {
            /// NO ES USUARIO ADMINISTRADOR
            $condominios = $user->condominios; //QUE CONDOMINIOS TENGO ASIGNADOS
            // Crear un array para almacenar los IDs de los condominios
            $condominioIds = $condominios->pluck('id')->toArray();
            // Obtener solo las viviendas de los condominios asignados al usuario, junto con el condominio
            $viviendasAsignadas = Vivienda::with('condominio')
                ->whereIn('condominio_id', $condominioIds) // Filtrar por los condominios asignados al usuario
                ->get();
            // Obtener solo los residentes de los condominios asignados al usuario
            $residentes = DB::table('habitantes as h')
                ->select(
                    'h.id as id',
                    'h.*',
                    'p.id as id_perfil',
                    'p.name',
                    'p.nroDocumento',
                    'p.celular',
                    'vd.id as id_vivienda',
                    'vd.nroVivienda',
                    'c.propietario'
                )
                ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                ->join('viviendas as vd', 'h.vivienda_id', '=', 'vd.id')
                ->join('condominios as c', 'vd.condominio_id', '=', 'c.id')
                ->where('h.isDuenho', '=', true) // Filtrar solo dueños
                ->whereIn('vd.condominio_id', $condominioIds) // Filtrar por los condominios asignados al usuario
                ->orderBy('h.id', 'DESC')
                ->get();
        }
        $crear = $user->canCrear('HABITANTE');
        $editar = $user->canEditar('HABITANTE');
        $perfil = $habitante->perfil;
        $vivienda = $habitante->vivienda;
        $habitante->perfil = $perfil;
        $habitante->vivienda = $vivienda;
        return Inertia::render("Habitante/CreateUpdate", [
            'model' => $habitante,
            'crear' => $crear,
            'editar' => $editar,
            'viviendas' => $viviendasAsignadas,
            'tipo_documento' => $tipo_documento,
            'residentes' => $residentes
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHabitanteRequest $request, Habitante $habitante)
    {
        try {
            if ($request->isMobile) {
                $perfil    = $request->perfil;
                if ($perfil['email'] != null && $perfil['email'] != $habitante->perfil->email) {
                    $validator = Validator::make($perfil, [
                        'email' => ['unique:perfils']
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            "isRequest" => true,
                            "isSuccess" => false,
                            "isMessageError" => true,
                            "message" => $validator->errors(),
                            "messageError" => $validator->errors(),
                            "data" => [],
                            "statusCode" => 422
                        ], 422);
                    }
                }

                if ($perfil['nroDocumento'] != null && $perfil['nroDocumento'] != $habitante->perfil->nroDocumento) {
                    $validator = Validator::make($perfil, [
                        'nroDocumento' => ['unique:perfils']
                    ]);
                    if ($validator->fails()) {
                        return response()->json([
                            "isRequest" => true,
                            "isSuccess" => false,
                            "isMessageError" => true,
                            "message" => $validator->errors(),
                            "messageError" => $validator->errors(),
                            "data" => [],
                            "statusCode" => 422
                        ], 422);
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
                "isRequest" => true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Actualización completo" : "Error!!!",
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
    public function destroy(Habitante $habitante)
    {
        try {
            /*$perfil = Perfil::findOrFail($apphabitante->perfil_id);
            $response = $perfil->delete();
            $habitante = Habitante::findOrFail($apphabitante->id);
            $response = $habitante->delete();*/
            $response = $habitante->delete();
            $delete_perfil = $habitante->perfil->delete();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $response,
                "isMessageError" => $response,
                "message" => $response != null ? "completo" : "Error!!!",
                "messageError" => "",
                "data" => $response,
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
