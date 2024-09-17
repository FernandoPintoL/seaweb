<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perfil;
use App\Models\Condominio;
use App\Http\Requests\StoreLoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function token(Request $request){
        // return $request->all();
        $token = "";
        $user = User::where('email', '=',$request->get('email'))->first();
        if(!$user || !Hash::check($request->get('password'), $user->password)){
            return response()->json([
                'message' => 'credenciales incorrectas',
                'token' => $token]);
        }
        $tokens = $user->tokens()->first();
        if(!$tokens){
            $token_create = $user->createToken( $user->id )->plainTextToken;
            $tokens        = $user->tokens()->first();
            $token        = $tokens->token;
        }else{
            $token = $tokens->token;
        }
        return response()->json( [ 'user' => $user, 'token' => $token ]);
    }
    public function existeNick(Request $request){
        $data = $request->all();
        $consult = User::where('nick', $data['nick'])->get();
        return response()->json(["cantidad" => count($consult)]);
    }

    public function existeEmail(Request $request){
        $data = $request->all();
        $consult = User::where('email', $data['email'])->get();
        return response()->json(["cantidad" => count($consult)]);
    }

    public function getUser(Request $request){
        $data = $request->all();
        $user = User::where('email', $data['query'])
                ->orWhere('nick', $data['query'])->first();
        return response()->json(["user" => $user]);
    }

    public function getAllUser(Request $request){
        $data = $request->all();
        $users = User::where('email', "LIKE", '%'. $data['query'] . '%')
                ->orWhere('name','%'. $data['query'] . '%')
                ->orWhere('nick','%'. $data['query'] . '%')->get();
        return response()->json(["users" => $users]);
    }

    public function loginOnApi(StoreLoginRequest $request){
        try{
            // return $request->all();
            $user = User::where('usernick', $request->usernick)
                    ->orWhere('email', $request->usernick)->first();
            if($user == null){
                return response()->json([
                    "isRequest"=> true,
                    "isSuccess" => false,
                    "isMessageError" => true,
                    "message" => "Error Usuario no encontrado...",
                    "messageError" => "",
                    "data" => [],
                    "statusCode" => 422,
                ], 422);
            }
            if ($user && Hash::check($request->password, $user->password)) {
                $userData = array(
                    'email' => $user->email,
                    'password' => $request->password
                );
                if (Auth::attempt($userData)) {
                    Auth::login($user);
                    $userLogin = auth()->user();
                    return response()->json([
                        "isRequest"=> true,
                        "isSuccess" => true,
                        "isMessageError" => false,
                        "message" => "Inicio de sessión correcto...",
                        "messageError" => "",
                        "data" => $userLogin,
                        "statusCode" => 200
                    ]);
                }else{
                    return response()->json([
                        "isRequest"=> true,
                        "isSuccess" => false,
                        "isMessageError" => true,
                        "message" => "Usuario no pudó ser autenticado",
                        "messageError" => "",
                        "data" => $user,
                        "statusCode" => 422
                    ],422);
                }
            }else{
                return response()->json([
                    "isRequest"=> true,
                    "isSuccess" => false,
                    "isMessageError" => true,
                    "message" => "Usuario encontrado, el password es incorrecto...",
                    "messageError" => "",
                    "data" => $user,
                    "statusCode" => 422
                ], 422);
            }
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
    public function logout(Request $request)
    {
        try{
            Auth::logout();

            // $request->session()->invalidate();

            // $request->session()->regenerateToken();

            return response()->json([
                "isRequest"=> true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "Session cerrada conrrectamente..",
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
        // return redirect('/login');
    }

    public function updateInformacion(Request $request, User $user){
        try{
            $datas = $request->all();
            if($datas['email'] != $user->email || $datas['usernick'] != $user->usernick){
                $validator = Validator::make($datas, [
                    'email' => ['unique:users', 'unique:perfils'],
                    'usernick' => ['unique:users']
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
            //ACTUALIZAR EL NAME EL PERFIL
            $condominio = Condominio::where('user_id','=',$datas['id'])->first();
            $perfil     = $condominio->perfil;
            $condominio->update([
                'propietario' => $datas['name']
            ]);
            $perfil->update([
                'name' => $datas['name'],
                'email' => $datas['email']
            ]);
            $responsse = $user->update([
                'name' => $datas['name'],
                'email' => $datas['email'],
                'usernick' => $datas['usernick']
            ]);
            return response()->json([
                "isRequest"=> true,
                "isSuccess" => $responsse != null,
                "isMessageError" => $responsse != null,
                "message" => $responsse != null ? "Actualización completo" : "Error!!!",
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}