# Auth Api Rest Laravel

## 1. Crear controller

AuthController

-   Generar el e controlador para las funciones (Login, register, profile, logout)

```bash
php artisan make:controller AuthController
```

## 2. Agregamos las rutas

-   Desde el archivo api.php

```php

//Auth Api Rest
Route::prefix("/v1/auth")->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'registro']);

    //creamos un middleware
    Route::middleware("auth:sanctum")->group(function () {
        Route::get('/perfil', [AuthController::class, 'perfil']);
        Route::post('/logout', [AuthController::class, 'salir']);
    });
});
```

## 3. Creamos la migracion

-   Desde la terminal ejecutamos el siguiente comando para la migracion
-   para esto se tiene que configurar el archivo .env con la BD del proyecto

```bash
php artisan migrate
```

### 3.1 configurar el .env

-   Nombre de la BD
-   EL userName y el password de la BD

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=back-poryecto-laravel
DB_USERNAME=root
DB_PASSWORD=
```

## 3. Creamos las funciones en el controller AuthController LOGICA

### 3.1 function Login

-   Ruta:

```bash
http://127.0.0.1:8000/api/v1/auth/login
```

```php
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
```

### JSON PARA ENDPOINT

```json
### Login
POST http://127.0.0.1:8000/api/v1/auth/login
Content-Type: application/json
Accept: application/json

{
	"email":"porfy9@test.com",
	"password":"Pepito12$"
}
```

### 3.2 function register

-   Ruta:

```bash
http://127.0.0.1:8000/api/v1/auth/register
```

```php
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

    }
```

### JSON PARA ENDPOINT

```json
### registro de Usuario
POST http://127.0.0.1:8000/api/v1/auth/register
Content-Type: application/json
Accept: application/json

{
	"name":"Porfirio",
	"email":"porfy10@test.com",
	"password":"Pepito12$",
	"cpassword":"Pepito12$"
}
```

### 3.3 function perfil

-   Ruta:

```bash
http://127.0.0.1:8000/api/v1/auth/perfil
```

```php
    public function perfil(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }
```

### JSON PARA ENDPOINT

-   Para este endpoint necesitamos el token del login
-   ese token le enviamos en el endpoint para recuperar al usuario

```json
### Perfil
GET http://127.0.0.1:8000/api/v1/auth/perfil
Accept: application/json
Authorization: Bearer 3|pGMYHPaGxd5TgaERzvXHzyzDgTbqsuMXh3DT1bBA
```

### 3.4 function logout salir

-   Ruta:

```bash
http://127.0.0.1:8000/api/v1/auth/salir
```

```php

```

## 3. Creamos las funciones en el controller AuthController LOGICA

```php

```

## 3. Creamos las funciones en el controller AuthController LOGICA

```php

```
