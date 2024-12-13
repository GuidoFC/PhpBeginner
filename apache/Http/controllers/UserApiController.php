<?php

namespace Http\controllers;

// Cogido de aqui:
// https://github.com/SajeebChakraborty/RESTful_CRUD_Auth_API_Laravel/blob/master/app/Console/Kernel.php

use Core\App;
use Core\DAO\UsuarioDAO;
use Core\Database;
use Core\Middleware\AuthApiRestFul;


class UserApiController

{

    private $baseDatos;

    public function __construct()
    {
        try {
            $this->baseDatos = App::resolve(Database::class);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Función para manejar el inicio de sesión
    public function loginUser()
    {
        // Verificar si la solicitud es POST
        // TODO pongo un momento Get
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Metodo no permitido']);
            exit;
        }

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

        if (!isset($req['password']) || strlen($req['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
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

        if (!$user || !password_verify($req['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid email or password']);
            exit;
        }

        // Generar el token único
        $token = bin2hex(random_bytes(32));


        // encriptar el token
        $tokenEncriptado =  $this->encriptarToken($token . "sal");

        // Guardar el token en la base de datos

        // TODO guadar en la TABLA TOKEN
        $dateActual = date('Y/m/d h:i:s', time());

        $incrementarUnDia = 86400;

        $caducidadToken = date('Y/m/d h:i:s', time() + $incrementarUnDia);

        $dispotivo = "Api_restful";





        $UsuarioDAO = new UsuarioDAO();
        // $token, $dispotivo, $user_id, $created_at, $finaliza
        $UsuarioDAO->storeTokenInDatabase($tokenEncriptado ,$dispotivo ,$user['id'], $dateActual, $caducidadToken);

        // Responder con el token
        http_response_code(200);
        echo json_encode([
            'message' => 'User successfully logged in',
            'access_token_guardalo' => $token,
        ]);
        exit;
    }

    public function logoutUser()
    {
        $authenticatedUser = AuthApiRestFul::getAuthenticatedUser();

        $UsuarioDAO = new UsuarioDAO();
        $UsuarioDAO->deleteTokenFromDatabase($authenticatedUser["api_token"]);


        // Responder con éxito
        http_response_code(200);
        echo json_encode(['message' => 'Logout exitoso']);
        exit;
    }

    private  function encriptarToken($TokenSinEncriptar)
    {
        return password_hash($TokenSinEncriptar, PASSWORD_BCRYPT);
    }
}

