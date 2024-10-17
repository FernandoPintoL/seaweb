<?php

namespace App\Http\Controllers;

use App\Models\TipoVisita;
use App\Http\Requests\StoreTipoVisitaRequest;
use App\Http\Requests\UpdateTipoVisitaRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class TipoVisitaController extends Controller
{
    public function query(Request $request)
    {
        try {
            $queryStr = $request->get('query');
            $responsse = TipoVisita::where('sigla', 'LIKE', '%' . $queryStr . '%')
                ->orWhere('detalle', 'LIKE', '%' . $queryStr . '%')
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
        Gate::authorize('viewAny', TipoVisita::class);
        $user = auth()->user();
        $crear = $user->canCrear('TIPO_VISITA');
        $editar = $user->canEditar('TIPO_VISITA');
        $eliminar = $user->canEliminar('TIPO_VISITA');
        $listado = TipoVisita::all();
        return Inertia::render("TipoVisita/Index", [
            'listado' => $listado,
            'crear' => $crear,
            'editar' => $editar,
            'elimnar' => $eliminar
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', TipoVisita::class);
        $user = auth()->user();
        $crear = $user->canCrear('TIPO_VISITA');
        $editar = $user->canEditar('TIPO_VISITA');
        $eliminar = $user->canEliminar('TIPO_VISITA');
        return Inertia::render("TipoVisita/CreateUpdate", [
            'crear' => $crear,
            'editar' => $editar,
            'elimnar' => $eliminar
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoVisitaRequest $request)
    {
        try {
            $model = TipoVisita::create($request->all());
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
    public function show(TipoVisita $apptipoVisita)
    {
        try {
            // $habitante = Habitante::where('vivienda_id','=',$idvivienda)->first();
            // $perfil    = Perfil::findOrFail( $habitante->perfil_id );
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "messageError" => "",
                "data" => $apptipoVisita,
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
    public function edit(TipoVisita $tipovisitum)
    {
        Gate::authorize('update', $tipovisitum);
        $crear = $user->canCrear('TIPO_VISITA');
        $editar = $user->canEditar('TIPO_VISITA');
        $eliminar = $user->canEliminar('TIPO_VISITA');
        return Inertia::render("TipoVisita/CreateUpdate", [
            'model' => $tipovisitum,
            'crear' => $crear,
            'editar' => $editar,
            'eliminar' => $eliminar
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoVisita $tipovisitum)
    {
        try {
            if ($request->sigla != $tipovisitum->sigla) {
                $model     = $request->all();
                $validator = Validator::make($model, [
                    'sigla' => ['unique:tipo_visitas']
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
            $response = $tipovisitum->update($request->all());
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
    public function destroy(TipoVisita $tipovisitum)
    {
        try {
            $response = $tipovisitum->delete();
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
