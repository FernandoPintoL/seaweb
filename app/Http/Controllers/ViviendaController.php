<?php

namespace App\Http\Controllers;

use App\Models\Vivienda;
use App\Models\Condominio;
use App\Http\Requests\StoreViviendaRequest;
use App\Http\Requests\UpdateViviendaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class ViviendaController extends Controller
{
    public function query(Request $request)
    {
        try {
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $listado    = [];
            if ($request->get('skip') != null && $request->get('take') != null) {
                $skip = $request->get('skip');
                $take = $request->get('take');
                $listado = DB::table('viviendas as v')
                    ->select('v.id as id', 'v.*', 'c.razonSocial', 'tv.detalle')
                    ->join('condominios as c', 'c.id', '=', 'v.condominio_id')
                    ->join('tipo_viviendas as tv', 'tv.id', '=', 'v.tipo_vivienda_id')
                    ->skip($skip)
                    ->take($take)
                    ->orderBy('v.id', 'DESC')
                    ->get();
            } else {
                $condominioID = $request->get('condominio_id');
                $listado = DB::table('viviendas as v')
                    ->select('v.id as id', 'v.*', 'c.razonSocial', 'tv.detalle')
                    ->join('condominios as c', 'c.id', '=', 'v.condominio_id')
                    ->join('tipo_viviendas as tv', 'tv.id', '=', 'v.tipo_vivienda_id')
                    ->where('v.condominio_id', '=', $condominioID) // Siempre filtrar por condominio_id
                    ->when($queryUpper, function ($query) use ($queryUpper) {
                        // Aplicar filtros LIKE solo si $queryUpper no está vacío
                        $query->where(function ($query) use ($queryUpper) {
                            $query->where('v.nroVivienda', 'LIKE', '%' . $queryUpper . '%')
                                ->orWhere('c.razonSocial', 'LIKE', '%' . $queryUpper . '%')
                                ->orWhere('tv.detalle', 'LIKE', '%' . $queryUpper . '%');
                        });
                    })->get();
            }
            $cantidad = count($listado);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $listado,
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
        Gate::authorize('viewAny', Vivienda::class);
        $listado = DB::table('viviendas as v')
            ->select('v.id as id', 'v.*', 'c.razonSocial', 'tv.detalle')
            ->join('condominios as c', 'c.id', '=', 'v.condominio_id')
            ->join('tipo_viviendas as tv', 'tv.id', '=', 'v.tipo_vivienda_id')
            ->orderBy('v.id', 'DESC')
            ->where('v.condominio_id', '=', 1)
            ->get();
        $condominios = Condominio::all();
        $user = auth()->user();
        $crear = $user->canCrear('VIVIENDA');
        $editar = $user->canEditar('VIVIENDA');
        $eliminar = $user->canEliminar('VIVIENDA');
        return Inertia::render("Vivienda/Index", [
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
        Gate::authorize('create', Vivienda::class);
        $user = auth()->user();
        $crear = $user->canCrear('VIVIENDA');
        $editar = $user->canEditar('VIVIENDA');
        $eliminar = $user->canEliminar('VIVIENDA');
        return Inertia::render("Vivienda/CreateUpdate", [
            'crear' => $crear,
            'editar' => $editar,
            'eliminar' => $eliminar
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreViviendaRequest $request)
    {
        try {
            $model = Vivienda::create($request->all());
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $model != null,
                "isMessageError" => $model != null,
                "message" => $model != null ? "Solicitud completada" : "Error!!!",
                "messageError" => "",
                "data" => $model,
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
    public function show(Vivienda $vivienda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vivienda $vivienda)
    {
        Gate::authorize('update', $vivienda);
        $user = auth()->user();
        $crear = $user->canCrear('VIVIENDA');
        $editar = $user->canEditar('VIVIENDA');
        $eliminar = $user->canEliminar('VIVIENDA');
        return Inertia::render("Vivienda/CreateUpdate", [
            'model' => $vivienda,
            'crear' => $crear,
            'editar' => $editar,
            'eliminar' => $eliminar
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vivienda $vivienda)
    {
        try {
            if ($request->nroVivienda != $vivienda->nroVivienda) {
                $model     = $request->all();
                $validator = Validator::make($model, [
                    'nroVivienda' => ['unique:viviendas']
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
            $response = $vivienda->update($request->all());
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $response,
                "isMessageError" => !$response,
                "message" => $response ? "Datos actualizados correctamente" : "Datos no actualizados",
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vivienda $vivienda)
    {
        try {
            $response = $vivienda->delete();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $response,
                "isMessageError" => !$response,
                "message" => $response ? "Datos eliminados correctamente" : "Los datos no pudieron ser eliminados",
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
