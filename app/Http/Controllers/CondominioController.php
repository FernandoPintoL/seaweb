<?php

namespace App\Http\Controllers;

use App\Models\Condominio;
use App\Models\Perfil;
use App\Models\User;
use App\Http\Requests\StoreCondominioRequest;
use App\Http\Requests\UpdateCondominioRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CondominioController extends Controller
{
    public function query(Request $request){
        try{
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $responsse = Condominio::with('perfil')
                        ->with('user')
                        ->where('propietario', 'LIKE', "%".$queryUpper."%")
                        ->orWhere('razonSocial', 'LIKE', "%".$queryUpper."%")
                        ->orderBy('id', 'DESC')
                        ->get();
            $cantidad = count( $responsse );
            $str = strval($cantidad);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMesageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
                "statusCode"=> 200
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
        $listado = Condominio::with('perfil')
                        ->with('user')->get();
        return Inertia::render("Condominio/Index", ['listado'=> $listado]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Condominio/CreateUpdate");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCondominioRequest $request)
    {
        try{
            if($request->get('razonSocial') != null){
                $validator = Validator::make($request->all(), [
                                'razonSocial' => ['unique:condominios']
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

            if($request->get('nit') != null){
                $validator = Validator::make($request->all(), [
                                'nit' => ['unique:condominios'],
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

            $perfilRequest = $request->perfil;
            $userRequest   = $request->user;

            $validatorPerfil = Validator::make($perfilRequest, [
                                'email' => ['unique:perfils']
                            ]);
            if ($validatorPerfil->fails()) {
                return response()->json( [
                    "isRequest" => true,
                    "isSuccess" => false,
                    "isMessageError" => true,
                    "message" => $validatorPerfil->errors(),
                    "messageError" => $validatorPerfil->errors(),
                    "data" => [],
                    "statusCode" => 422
                ], 422 );
            }
            $validatorUser = Validator::make($userRequest, [
                                'email' => ['unique:users'],
                                'usernick' => ['unique:users']
                            ]);
            if ($validatorUser->fails()) {
                return response()->json( [
                    "isRequest" => true,
                    "isSuccess" => false,
                    "isMessageError" => true,
                    "message" => $validatorUser->errors(),
                    "messageError" => $validatorUser->errors(),
                    "data" => []
                ], 422 );
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
            $user->assignRole(['CONDOMINIO']);

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
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "messageError" => "",
                "data" => ["response" => $responsse, "user" => $user, "perfil" => $perfil],
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
    public function show(Condominio $condominio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condominio $condominio)
    {
        $perfil = $condominio->perfil;
        $user   = $condominio->user;
        $condominio->perfil = $perfil;
        $condominio->user   = $user;
        return Inertia::render("Condominio/CreateUpdate", ['model'=>$condominio]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condominio $condominio)
    {
        try{
            $datas     = $request->all();
            if($datas['razonSocial'] != $condominio->razonSocial){
                $validator = Validator::make($datas, [
                                'razonSocial' => ['unique:condominios']
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
            if($datas['nit'] != $condominio->nit){
                $validator = Validator::make($datas, [
                                'nit' => ['unique:condominios']
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
                $perfil = $datas['perfil'];
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
            $perfilToUpdate = $condominio->perfil;
            $userToUpdate = $condominio->user;
            $propietario    = strtoupper( $datas['propietario'] );
            $perfilToUpdate->update([
                'name' => $propietario,
                'nroDocumento' => $datas['nit']
            ]);
            $userToUpdate->update( [
                'name' => $propietario
            ] );
            $responsse = $condominio->update( $datas );
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Solicitud realizada correctamente...",
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
    public function destroy(Condominio $condominio)
    {
        try{
            $responseData  = $condominio->delete();
            $delete_user = $condominio->user->delete();
            $delete_perfil = $condominio->perfil->delete();
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