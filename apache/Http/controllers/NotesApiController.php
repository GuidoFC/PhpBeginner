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

        $user = $usuarioDAO->getUserByApiToken($getToken);

        $this->verifyUserWithToken($user);


        $notaID = $this->getNoteIdFromRequest();


        $this->validateNoteIdFromRequest($notaID);


        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);

        $getNote = $notaService->obtenerNota($notaID);


        $this->validateIDNoteBaseDates($getNote);




        $this->verifyNoteOwnership($getNote, $user);


        // Enviar mensaje de resupuesta si es exitoso la peticion
        http_response_code(200);
        // enviar una respuesta HTTP con contenido en formato JSON
        // header: Informa al cliente (por ejemplo, un navegador web o una aplicación)
        // que el contenido que se enviará está en formato JSON
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $getNote,
        ]);
        exit;
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

    public function validateNoteIdFromRequest($notaID)
    {
        if (!$notaID) {
            $this->sendErrorResponse(403, 'Se requiere el id de la nota como Parametro, ej: ?id=40');
        }
    }

    private function validateIDNoteBaseDates($getNote)
    {
        if (!$getNote) {
            $this->sendErrorResponse(403, 'Nota no encontrada en base Datos, verifique id nota');
        }
    }

    private function verifyNoteOwnership($getNote, $user)
    {
        // Verificar si la nota pertenece al usuario
        if ($getNote['user_id'] !== $user['id']) {
            $this->sendErrorResponse(403, 'Este usuario no tienes permiso para ver esta nota');
        }
    }

    /**
     * @return mixed|null
     */
    public function getNoteIdFromRequest(): mixed
    {
        return $_GET['id'] ?? null;
    }
}

