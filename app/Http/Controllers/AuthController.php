<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        //Validar 
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => [
                'required',
                //'confirmed',
                //new Password(),
            ],

        ]);

        //Verificar
        // Auth para obtener 
        if (!Auth::attempt($credenciales)) {
            return response()->json([
                'message' => 'Credenciales inválidos.',
                'error' => true,
            ]);
            // ], 401);
        }
        // generar el token
        $user = Auth::user(); //capturamos la sesion del usuario
        $tokenResult = $user->createToken('Token Personal'); //
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'token' => $token,
            'type_token' => 'Bearer',
            'usuario' => $user
        ]);
    }
    public function registro(Request $request)
    {

        // Validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            // "password" => "required",
            //validacion de l PAssword 8 caracteres Mayuscula Minuscla numeros symbols
            "password" => [
                'required', 'string', Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                //    ->uncompromised()
            ],
            "cpassword" => "required|same:password" // same para que compare el password
        ]);
        // Regitrar usuario
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        // $usuario->password = bcrypt($request->password);
        $usuario->save();
        //Retornar una respuesta Json
        return response()->json([
            "message" => "Usuario Registrado exitosamente",
            "error" => false,
            "usuario" => [
                "id" => $usuario->id,
                "name" => $usuario->name,
                "email" => $usuario->email,

            ]
        ]);


        /*
        //esto genero automaticamente lo guardamos por si acaso
        // Guardamos el usuario
        $guard = new Guard();
        $guard->name = $usuario->name;
        $guard->email = $usuario->email;
        $guard->password = Hash::make($request->cpassword);
        // $guard->password = bcrypt($request->cpassword);
        $guard->save();

        // Guardamos el token
        $token = new Token();
        $token->user_id = $usuario->id;
        $token->guard_id = $guard->id;
        $token->save();

        // Guardamos el token
        $token = new Token();
        $token->user_id = $usuario->id;
        $token->guard_id = $guard->id;
*/
    }
    public function perfil(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }
    public function salir(Request $request)
    {
    }
}