<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
Use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
/** @OA\Info(
    *             title="Sistema de Logueo",
    *             version="1.0",
    *             description="Sistema de prática con los estudiantes de quinto nivel"
    * )
    *
    * @OA\Server(url="http://127.0.0.1:8000/")
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Login with email and password to get the authentication token",
     *     name="Token based Based",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     * ) 
*/


class AuthController extends Controller
{
    /**
     * Listado de los registro de Usuarios
     * @OA\Get (
     *     path="/api/usuario",
     *     tags={"user"},
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="dilan"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="cris@gmail.com"
     *                     ),
     *                       
     *                     @OA\Property(
     *                         property="email_varified_at",
     *                         type="timestamp",
     *                         example=""
     *                     ),
     *                      @OA\Property(
     *                         property="password",
     *                         type="string",
     *                         example="132135"
     *                     ),
     * 
     *                     @OA\Property(
     *                         property="estado",
     *                         type="booleano",
     *                         example="0"
     *                     ),
     *                         @OA\Property(
     *                         property="tipo_id",
     *                         type="foreignId",
     *                         example="2"
     *                     ),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $usuario=User::where('estado',false)->get();
        return response()->json(['usuario'=>$usuario],200);
    }
 
    /*
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * @OA\Post(
     *     path="/api/registrarse",
     *     tags={"user"},
     *     summary="Registo usuario",
     *     operationId="idcreate",
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="nombre del usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         description="Correo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
    *     @OA\Parameter(
     *         name="password",
     *         in="path",
     *         description="Contrasena",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="tipo_id",
     *         in="path",
     *         description="Tipo de usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok"
     *     ),
     *     @OA\Response(
     *         response="402",
     *         description="Campos requeridos"
     *     )
     * )
     */
     
       
    public function store(Request $request)
    {
        $validateUsuario = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' =>                  
                'required|email|unique:users,email',
                'password' => 'required',
                'tipo_id'=> 'required'
            ]);
            if($validateUsuario->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Existen campos vacios',
                    'errors' => $validateUsuario->errors()
                ], 401);
            }
            $usuario = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'tipo_id'=>$request->tipo_id
            ]);
            return response()->json([
                'message' => 'Usuario creado correctamente',
                'token' => $usuario->createToken("API TOKEN")->plainTextToken
            ], 201);

        
    }
    public function logear(Request $request){
        $validateUsuario = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ]
        );
        if ($validateUsuario->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validaciones requeridas',
                'errors' => $validateUsuario->errors()
            ], 401);
        }
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas',
            ], 401);
        }
        $user = User::where('email', $request->email)->first();
        return response()->json([
            "Usuario" => $user,
            'message' => 'Usuario logeado correctamente',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function showUser(string $name)
{
    $usuario = User::where("name", $name)->get();

    // Verificar si la colección no está vacía
    if (!$usuario->isEmpty()) {
        return response()->json(["usuario" => $usuario]);
    } else {
        return response()->json([
            'message' => 'Usuario no encontrado',
        ], 404);
    }
}


    /**
     * Show the form for editing the specified resource.
     */
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateUsuario = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' =>                  
                'required|email|unique:users,email',
                'password' => 'required'
            ]);
            if($validateUsuario->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Existen campos vacios',
                    'errors' => $validateUsuario->errors()
                ], 401);
            }
            
            $usuario=User::find($id);
            if(!$usuario){
                return response()->json([
                    'message' => 'Usuario no encontrado',
                ], 404);
            }
                $usuario->name =$request->name;
                $usuario->email=$request->email;
                $usuario->password=$request->password;
                $usuario->save();
            
            return response()->json([
                'message' => 'Usuario actualizado correctamente',
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $usuario=User::find($id);

        if($usuario){
            $usuario->estado=true;
            $usuario->save();
            return response()->json([
                "message"=>"Usuario eliminado"
            ],200);

        }
        else{
            return response()->json([
                "message"=>"Usuario no exitente"
            ],404);
        }
        
    }
}
