<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StoreRolesRequest;
use App\Http\Requests\UpdateRolesRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller
{
    public function query(Request $request)
    {
        try {
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $responsse = Roles::where('name', 'LIKE', '%' . $queryUpper . '%')
                ->get();
            $cantidad = count($responsse);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMesageError" => false,
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
        Gate::authorize('viewAny', Role::class);
        $user = auth()->user();
        $crear = $user->canCrear('ROLE');
        $editar = $user->canEditar('ROLE');
        $eliminar = $user->canEliminar('ROLE');
        $listado = Role::all();
        return Inertia::render("Roles/Index", ['listado' => $listado, 'crear' => $crear, 'editar' => $editar, 'eliminar' => $eliminar]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Role::class);
        $user = auth()->user();
        $crear = $user->canCrear('ROLE');
        $editar = $user->canEditar('ROLE');
        $eliminar = $user->canEliminar('ROLE');
        $permissions = Permission::all();
        return Inertia::render("Roles/CreateUpdate", ['permissions' => $permissions, 'crear' => $crear, 'editar' => $editar, 'eliminar' => $eliminar]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRolesRequest $request)
    {
        try {
            $model = Role::create([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            $name_permission_mostrar       = $request->name . '.MOSTRAR';
            $name_permission_listar       = $request->name . '.LISTAR';
            $name_permission_crear       = $request->name . '.CREAR';
            $name_permission_editar       = $request->name . '.EDITAR';
            $name_permission_eliminar       = $request->name . '.ELIMINAR';

            $permission_mostrar = Permission::create(['name' => $name_permission_mostrar, 'guard_name' => 'web']);
            $permission_listrar = Permission::create(['name' => $name_permission_listar, 'guard_name' => 'web']);
            $permission_crear = Permission::create(['name' => $name_permission_crear, 'guard_name' => 'web']);
            $permission_editar = Permission::create(['name' => $name_permission_editar, 'guard_name' => 'web']);
            $permission_eliminar = Permission::create(['name' => $name_permission_eliminar, 'guard_name' => 'web']);

            $model->givePermissionTo($request->permissions);
            $model->givePermissionTo([
                $permission_mostrar,
                $permission_listrar,
                $permission_crear,
                $permission_editar,
                $permission_eliminar,
            ]);

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
    public function show(Roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        Gate::authorize('update', $role);
        $permissions = Permission::all();
        $model_permissions = $role->permissions->pluck('name');
        $user = auth()->user();
        $crear = $user->canCrear('ROLE');
        $editar = $user->canEditar('ROLE');
        $eliminar = $user->canEliminar('ROLE');
        return Inertia::render("Roles/CreateUpdate", ['model' => $role, 'permissions' => $permissions, 'model_permissions' => $model_permissions, 'crear' => $crear, 'editar' => $editar, 'eliminar' => $eliminar]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            if ($request->name != $role->name) {
                $model     = $request->all();
                $validator = Validator::make($model, [
                    'name' => ['unique:roles']
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

            $response = $role->update([
                'name' => $request->name,
                'guard_name' => 'web'
            ]);

            $role->syncPermissions($request->permissions);
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
    public function destroy(Roles $role)
    {
        try {
            $responseData = $role->delete();
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
