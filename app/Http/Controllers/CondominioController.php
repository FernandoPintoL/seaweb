<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\Perfil;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Roles;
use Spatie\Permission\Models\Permission;
use App\Models\Permissions;
use App\Http\Requests\StoreCondominioRequest;
use App\Http\Requests\UpdateCondominioRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class CondominioController extends Controller
{
    public function query(Request $request)
    {
        try {
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $responsse = Condominio::with('perfil')
                ->with('user')
                ->where('propietario', 'LIKE', "%" . $queryUpper . "%")
                ->orWhere('razonSocial', 'LIKE', "%" . $queryUpper . "%")
                ->orderBy('id', 'DESC')
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
        Gate::authorize('viewAny', Condominio::class);
        $user = auth()->user();
        $crear = $user->canCrear('CONDOMINIOS');
        $editar = $user->canEditar('CONDOMINIOS');
        $eliminar = $user->canEliminar('CONDOMINIOS');
        $listado = Condominio::with('perfil')->with('user')->get();
        return Inertia::render("Condominio/Index", ['listado' => $listado, 'crear' => $crear, 'editar' => $editar, 'eliminar' => $eliminar]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Condominio::class);
        $user = auth()->user();
        $crear = $user->canCrear('CONDOMINIOS');
        $editar = $user->canEditar('CONDOMINIOS');
        return Inertia::render("Condominio/CreateUpdate", ['crear' => $crear, 'editar' => $editar]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCondominioRequest $request)
    {
        try {
            if ($request->get('razonSocial') != null) {
                $validator = Validator::make($request->all(), [
                    'razonSocial' => ['unique:condominios']
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

            if ($request->get('nit') != null) {
                $validator = Validator::make($request->all(), [
                    'nit' => ['unique:condominios'],
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

            $perfilRequest = $request->perfil;
            $userRequest   = $request->user;

            $validatorPerfil = Validator::make($perfilRequest, [
                'email' => ['unique:perfils']
            ]);
            if ($validatorPerfil->fails()) {
                return response()->json([
                    "isRequest" => true,
                    "isSuccess" => false,
                    "isMessageError" => true,
                    "message" => $validatorPerfil->errors(),
                    "messageError" => $validatorPerfil->errors(),
                    "data" => [],
                    "statusCode" => 422
                ], 422);
            }

            $validatorUser = Validator::make($userRequest, [
                'email' => ['unique:users'],
                'usernick' => ['unique:users']
            ]);

            if ($validatorUser->fails()) {
                return response()->json([
                    "isRequest" => true,
                    "isSuccess" => false,
                    "isMessageError" => true,
                    "message" => $validatorUser->errors(),
                    "messageError" => $validatorUser->errors(),
                    "data" => []
                ], 422);
            }

            $condominio = $request->all();
            $perfil        = Perfil::create($perfilRequest);

            $user          = User::create([
                'name' => $userRequest['name'],
                'email' => $userRequest['email'],
                'usernick' => $userRequest['usernick'],
                'password' => Hash::make($userRequest['password']),
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ]);

            $name_role       = $user->name;

            $name_permission_mostrar       = $user->name . '.MOSTRAR';
            $name_permission_listar       = $user->name . '.LISTAR';
            $name_permission_crear       = $user->name . '.CREAR';
            $name_permission_editar       = $user->name . '.EDITAR';
            $name_permission_eliminar       = $user->name . '.ELIMINAR';

            $name_permission_vivienda_listar       = $user->name . '.VIVIENDA.LISTAR';
            $name_permission_vivienda_mostrar       = $user->name . '.VIVIENDA.MOSTRAR';
            $name_permission_vivienda_crear       = $user->name . '.VIVIENDA.CREAR';
            $name_permission_vivienda_editar       = $user->name . '.VIVIENDA.EDITAR';
            $name_permission_vivienda_eliminar       = $user->name . '.VIVIENDA.ELIMINAR';

            $name_permission_ingreso_mostrar       = $user->name . '.INGRESO.MOSTRAR';
            $name_permission_ingreso_listar       = $user->name . '.INGRESO.LISTAR';
            $name_permission_ingreso_crear       = $user->name . '.INGRESO.CREAR';
            $name_permission_ingreso_editar       = $user->name . '.INGRESO.EDITAR';
            $name_permission_ingreso_eliminar       = $user->name . '.INGRESO.ELIMINAR';

            $role_condominio = Role::create(['name' => $name_role, 'guard_name' => 'web']);

            $permission_mostrar = Permission::create(['name' => $name_permission_mostrar, 'guard_name' => 'web']);
            $permission_listrar = Permission::create(['name' => $name_permission_listar, 'guard_name' => 'web']);
            $permission_crear = Permission::create(['name' => $name_permission_crear, 'guard_name' => 'web']);
            $permission_editar = Permission::create(['name' => $name_permission_editar, 'guard_name' => 'web']);
            $permission_eliminar = Permission::create(['name' => $name_permission_eliminar, 'guard_name' => 'web']);

            $permission_vivienda_mostrar = Permission::create(['name' => $name_permission_vivienda_mostrar, 'guard_name' => 'web']);
            $permission_vivienda_listrar = Permission::create(['name' => $name_permission_vivienda_listar, 'guard_name' => 'web']);
            $permission_vivienda_crear = Permission::create(['name' => $name_permission_vivienda_crear, 'guard_name' => 'web']);
            $permission_vivienda_editar = Permission::create(['name' => $name_permission_vivienda_editar, 'guard_name' => 'web']);
            $permission_vivienda_eliminar = Permission::create(['name' => $name_permission_vivienda_eliminar, 'guard_name' => 'web']);

            $permission_ingreso_mostrar = Permission::create(['name' => $name_permission_ingreso_mostrar, 'guard_name' => 'web']);
            $permission_ingreso_listrar = Permission::create(['name' => $name_permission_ingreso_listar, 'guard_name' => 'web']);
            $permission_ingreso_crear = Permission::create(['name' => $name_permission_ingreso_crear, 'guard_name' => 'web']);
            $permission_ingreso_editar = Permission::create(['name' => $name_permission_ingreso_editar, 'guard_name' => 'web']);
            $permission_ingreso_eliminar = Permission::create(['name' => $name_permission_ingreso_eliminar, 'guard_name' => 'web']);


            $role_condominio->syncPermissions([
                $permission_mostrar,
                $permission_listrar,
                $permission_crear,
                $permission_editar,
                $permission_eliminar,
                $permission_vivienda_mostrar,
                $permission_vivienda_listrar,
                $permission_vivienda_crear,
                $permission_vivienda_editar,
                $permission_vivienda_eliminar,
                $permission_ingreso_mostrar,
                $permission_ingreso_listrar,
                $permission_ingreso_crear,
                $permission_ingreso_editar,
                $permission_ingreso_eliminar,
            ]);

            $user->syncRoles([$name_role]);
            $user->syncPermissions([
                $permission_mostrar,
                $permission_listrar,
                $permission_crear,
                $permission_editar,
                $permission_eliminar,
                $permission_vivienda_mostrar,
                $permission_vivienda_listrar,
                $permission_vivienda_crear,
                $permission_vivienda_editar,
                $permission_vivienda_eliminar,
                $permission_ingreso_mostrar,
                $permission_ingreso_listrar,
                $permission_ingreso_crear,
                $permission_ingreso_editar,
                $permission_ingreso_eliminar,
            ]);

            $responsse = Condominio::create([
                'propietario' => $condominio['propietario'],
                'razonSocial' => $condominio['razonSocial'],
                'nit' => $condominio['nit'],
                'perfil_id' => $perfil['id'],
                'user_id' => $user['id'],
                'cantidad_viviendas' => 0,
                'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
            ]);

            $permisos  = [
                $name_permission_mostrar,
                $name_permission_listar,
                $name_permission_crear,
                $name_permission_editar,
                $name_permission_eliminar,
                $name_permission_vivienda_mostrar,
                $name_permission_vivienda_listar,
                $name_permission_vivienda_crear,
                $name_permission_vivienda_editar,
                $name_permission_vivienda_eliminar,
                $name_permission_ingreso_mostrar,
                $name_permission_ingreso_listar,
                $name_permission_ingreso_crear,
                $name_permission_ingreso_editar,
                $name_permission_ingreso_eliminar,
            ];

            $user->condominios()->attach($responsse->id, [
                'permisos' => json_encode($permisos)
            ]);

            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "messageError" => "",
                "data" => ["response" => $responsse, "user" => $user, "perfil" => $perfil],
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
    public function show(Condominio $condominio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condominio $condominio)
    {
        Gate::authorize('update', $condominio);
        $user = auth()->user();
        $crear = $user->canCrear('CONDOMINIOS');
        $editar = $user->canEditar('CONDOMINIOS');
        $perfil = $condominio->perfil;
        $user   = $condominio->user;
        $condominio->perfil = $perfil;
        $condominio->user   = $user;
        return Inertia::render("Condominio/CreateUpdate", ['model' => $condominio, 'crear' => $crear, 'editar' => $editar]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condominio $condominio)
    {
        try {
            $datas     = $request->all();
            if ($datas['razonSocial'] != $condominio->razonSocial) {
                $validator = Validator::make($datas, [
                    'razonSocial' => ['unique:condominios']
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
            if ($datas['nit'] != $condominio->nit) {
                $validator = Validator::make($datas, [
                    'nit' => ['unique:condominios']
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
                $perfil = $datas['perfil'];
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
            $perfilToUpdate = $condominio->perfil;
            $userToUpdate = $condominio->user;
            $propietario    = strtoupper($datas['propietario']);
            $perfilToUpdate->update([
                'name' => $propietario,
                'nroDocumento' => $datas['nit']
            ]);
            $userToUpdate->update([
                'name' => $propietario
            ]);
            $responsse = $condominio->update($datas);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Solicitud realizada correctamente...",
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
    public function destroy(Condominio $condominio)
    {
        try {
            $user          = $condominio->user;
            $user->syncRoles([]);
            $user->syncPermissions([]);
            $role          = Role::where('name', 'LIKE', '%' . $user->name . '%')->first();
            if ($role) {
                $permissions = DB::table('role_has_permissions')->where('role_id', '=', $role->id)->pluck('permission_id');
                $role->syncPermissions([]);
                DB::table('permissions')->whereIn('id', $permissions)->delete();
                $delete_role = $role->delete();
            }
            $responseData  = $condominio->delete();
            $delete_user = $condominio->user->delete();
            $delete_perfil = $condominio->perfil->delete();
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
