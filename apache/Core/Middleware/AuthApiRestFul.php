<?php

namespace Core\Middleware;

use Core\App;
use Core\DAO\UsuarioDAO;
use Core\Database;

class AuthApiRestFul
{
    public function handle()
    {
        // Obtener el token del encabezado Authorization
        $headers = getallheaders();
        $getToken = $headers['Authorization'] ?? null;

        // obtenemos el token
        $this->verifyTokenPresence($getToken);


        // cogemos el email de la peticion
        // Obtener datos de entrada
        $input = file_get_contents("php://input");
        $req = json_decode($input, true);

        if (!$req) {
            http_response_code(400);
            echo json_encode(['message' => 'Entrada JSON no válida']);
            exit;
        }

        // Validar los datos enviados
        $errors = [];

        if (!isset($req['email']) || empty($req['email'])) {
            $errors['email'] = 'email is required';
        } elseif (!filter_var($req['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'email is invalid';
        }


        if (!empty($errors)) {
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