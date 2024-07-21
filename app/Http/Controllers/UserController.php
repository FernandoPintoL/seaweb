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
class UserController extends Controller
{
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
            $user = User::where('usernick', $request->usernick)->orWhere('email', $request->usernick)->first();
            if($user == null){
                return response()->json([
                    "isRequest"=> true,
                    "success" => false,
                    "messageError" => true,
                    "message" => "Error Usuario no encontrado...",
                    "data" => []
                ]);
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
                        "success" => true,
                        "messageError" => false,
                        "message" => "Inicio de sessión correcto...",
                        "data" => $userLogin
                    ]);
                }else{
                    return response()->json([
                        "isRequest"=> true,
                        "success" => false,
                        "messageError" => true,
                        "message" => "Usuario no pudó ser autenticado",
                        "data" => $user
                    ]);
                }
            }else{
                return response()->json([
                    "isRequest"=> true,
                    "success" => false,
                    "messageError" => true,
                    "message" => "Usuario encontrado, el password es incorrecto...",
                    "data" => $user
                ]);
            }
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
    public function logout(Request $request)
    {
        try{
            Auth::logout();
    
            // $request->session()->invalidate();
    
            // $request->session()->regenerateToken();

            return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "Session cerrada conrrectamente..",
                "data" => $request->all()
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
        // return redirect('/login');
    }
    /* public function destroy(Request $request): LogoutResponse
    {
        $this->guard->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return app(LogoutResponse::class);
    } */

    public function updateInformacion(Request $request, User $user){
        /*$datas = $request->all();
        return response()->json([
                "isRequest"=> true,
                "success" => false,
                "messageError" => true,
                "message" => "Verificacion User Informacion",
                "data" => [
                    "data" => $request->all(), 
                    "user" => $user,
                    "consulta" => $datas['email'] != $user->email || $datas['usernick'] != $user->usernick
                    ]
        ]);*/
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
                        "success" => false,
                        "messageError" => true,
                        "message" => $validator->errors(),
                        "data" => []
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
                "success" => $responsse != null,
                "messageError" => $responsse != null,
                "message" => $responsse != null ? "Actualización completo" : "Error!!!",
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