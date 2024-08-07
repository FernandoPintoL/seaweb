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
            $responsse = Condominio::with('perfil')
                        ->with('user')
                        ->where('propietario', 'LIKE', "%".$queryStr."%")
                        ->orWhere('razonSocial', 'LIKE', "%".$queryStr."%")
                        ->get();
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "data" => $responsse
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Consulta condominio/ ".$message." Code: ".$code,
                "data" => []
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
        /*return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Verificacion Condominio",
                "data" => $request->all()
        ]);*/
        try{
            $perfilRequest = $request->perfil;
            $userRequest   = $request->user;
            $validatorPerfil = Validator::make($perfilRequest, [
                                'email' => ['unique:perfils'],
                                'nroDocumento' => ['unique:perfils']
                            ]);
            if ($validatorPerfil->fails()) {
                return response()->json( [ 
                    "isRequest" => true,
                    "success" => false,
                    "messageError" => true,
                    "message" => $validatorPerfil->errors(),
                    "data" => []
                ], 422 );
            }
            $validatorUser = Validator::make($userRequest, [
                                'email' => ['unique:users'],
                                'usernick' => ['unique:users']
                            ]);
            if ($validatorUser->fails()) {
                return response()->json( [ 
                    "isRequest" => true,
                    "success" => false,
                    "messageError" => true,
                    "message" => $validatorUser->errors(),
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
            ]);
            $responsse = Condominio::create([
                'propietario' => $condominio['propietario'],
                'razonSocial' => $condominio['razonSocial'],
                'nit' => $condominio['nit'],
                'perfil_id' => $perfil['id'],
                'user_id' => $user['id'],
                'cantidad_viviendas' => 0
            ]);
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "data" => ["response" => $responsse, "user" => $user, "perfil" => $perfil]
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Store condominio error-message: / ".$message." Code: ".$code,
                "data" => []
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
            if($datas['razonSocial'] != $condominio->razonSocial || $datas['nit'] != $condominio->nit){
                $validator = Validator::make($datas, [
                                'razonSocial' => ['unique:condominios'],
                                'nit' => ['unique:condominios']
                            ]);
                if ($validator->fails()) {
                    return response()->json( [ 
                        "isRequest" => true,
                        "success" => false,
                        "messageError" => true,
                        "message" => $validator->errors(),
                        "data" => []
                    ], 422 );
                }
                $perfil = $datas['perfil'];
                $validator = Validator::make($perfil, [
                                'nroDocumento' => ['unique:perfils']
                            ]);
                if ($validator->fails()) {
                    return response()->json( [ 
                        "isRequest" => true,
                        "success" => false,
                        "messageError" => true,
                        "message" => $validator->errors(),
                        "data" => []
                    ], 422 );
                }
            }
            $perfilToUpdate = $condominio->perfil;
            $userToUpdate = $condominio->user;
            $perfilToUpdate->update([
                'name' => $datas['propietario'],
                'nroDocumento' => $datas['nit']
            ]);
            $userToUpdate->update( [
                'name' => $datas['propietario']
            ] );
            $responsse = $condominio->update( $datas );
            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Solicitud realizada correctamente...",
                "data" => $responsse
            ]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Update solicitud error-message: / ".$message." Code: ".$code,
                "data" => []
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condominio $condominio)
    {
        //
    }
}