<?php

namespace Http\controllers;

// Cogido de aqui:
// https://github.com/SajeebChakraborty/RESTful_CRUD_Auth_API_Laravel/blob/master/app/Console/Kernel.php

use Core\App;
use Core\DAO\NotaDAOImplMySql;
use Core\DAO\UsuarioDAO;
use Core\Database;
use Core\services\NotaService;


class NotesApiController

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
    public function getNote()
    {

        // Verificar si la solicitud es GET
        // Creo que no es necesario porque en routes
        // espefico que tiene q ser por GET la peticion

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Metodo no permitido']);
            exit;
        }

        // Obtener el token del encabezado Authorization
        $headers = getallheaders();
        $getToken = $headers['Authorization'] ?? null;


        $this->verifyTokenPresence($getToken);


        // Validar el token y obtener el usuario
        $usuarioDAO = new UsuarioDAO();

        $user = $usuarioDAO->validateApiToken($getToken);

        $this->verifyUserWithToken($user);


        $notaID = $_GET['id'] ?? null;


        if (!$notaID) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Se requiere el ID de la nota'
            ]);
            return;
        }


        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);

        $getNote = $notaService->obtenerNota($notaID);


        if (!$getNote) {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'No tienes permiso para ver esta nota',
            ]);
            return;
        }


        // Responder en JSON
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $getNote,
        ]);
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

    private function verifyUserWithToken($user)
    {
        if (!$user) {
            $this->sendErrorResponse(403, 'Token invalido, no pertenece a su cuenta');
        }
    }
}

