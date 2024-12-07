<?php

namespace Core\Middleware;

use Core\App;
use Core\DAO\UsuarioDAO;
use Core\Database;

class AuthApiRestFul
{
    private static $authenticatedUser = null;

    public static function setAuthenticatedUser($user)
    {
        // recuerda que self hace referencia a la clase actual, se usa en metodos estaticos o variables estaticas
        self::$authenticatedUser = $user;
    }

    public static function getAuthenticatedUser()
    {
        return self::$authenticatedUser;
    }

    public function handle()
    {
        // Obtener el token del encabezado Authorization
        $headers = getallheaders();
        $getToken = $headers['Authorization'] ?? null;

        // obtenemos el token
        $this->verifyTokenPresence($getToken);


        // cogemos el email de la peticion
        // Obtener datos de entrada
        $input = file_get_contents("php://input"); // Sirve para obtener los datos enviados en el cuerpo (body) de una petición HTTP y guardarlos en una variable como un string.

        $req = json_decode($input, true); // Convierte ese string JSON en un array asociativo para que puedas trabajar con los datos más fácilmente.


        if (!$req) {
            http_response_code(400);
            echo json_encode(['message' => 'En el body de la peticion, enviar el "email": ']);
            exit;
        }

        // Validar los datos enviados
        $errors = [];

        if (!isset($req['email']) || empty($req['email'])) { // isset verifica si la clave 'email' está definida en el array
            $errors['email'] = 'email is required';
        } elseif (!filter_var($req['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'email is invalid';
        }


        if (!empty($errors)) { // Verifica si la variable $errors NOO está vacía.  // Un valor se considera vacío si es: null, false, cadena vacía "", número 0, array vacío [] o no está definido.
            http_response_code(422);
            echo json_encode(['message' => $errors]);
            exit;
        }


        // Verificar las credenciales del usuario
        $user = App::resolve(Database::class)
            ->query('select * from users where email = :email', [
                ':email' => $req['email']
            ])->find();


        if (!password_verify($getToken . "sal", $user['api_token'])) {
            $this->sendErrorResponse(401, 'Token de seguridad no válido para este Usuario');
        }

        self::setAuthenticatedUser($user); // Guardar usuario autenticado


    }

    private function verifyTokenPresence($getToken)
    {
        if (!$getToken) {
            $this->sendErrorResponse(401, 'Token no proporcionado');
        }
    }

    private function sendErrorResponse($statusCode, $message)
    {
        http_response_code($statusCode);
        echo json_encode([
            'status' => 'error',
            'message' => $message,
        ]);
        // Detiene la ejecución después de enviar la respuesta.
        exit;
    }

    private function encriptarToken($TokenSinEncriptar)
    {
        return password_hash($TokenSinEncriptar, PASSWORD_BCRYPT);
    }

}