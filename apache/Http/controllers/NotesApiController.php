<?php

namespace Http\controllers;

// Cogido de aqui:
// https://github.com/SajeebChakraborty/RESTful_CRUD_Auth_API_Laravel/blob/master/app/Console/Kernel.php

use Core\App;
use Core\DAO\NotaDAOImplMySql;
use Core\DAO\UsuarioDAO;
use Core\Database;
use Core\Middleware\AuthApiRestFul;
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

    // Todo me falta: Eliminar nota, crear nota


    public function updateNote()
    {

        // Obtener el token del encabezado Authorization
        $headers = getallheaders();
        $getToken = $headers['Authorization'] ?? null;

        // Validar la presencia del token
        $this->verifyTokenPresence($getToken);

        // Validar el token y obtener el usuario
        $usuarioDAO = new UsuarioDAO();
        $user = $usuarioDAO->getUserByApiToken($getToken);


        // Verificar que el usuario sea válido
        $this->verifyUserWithToken($user);

        // Obtener el ID  y Body de la nota desde la solicitud
        $dataFromJson = json_decode(file_get_contents('php://input'), true);


        if (!$dataFromJson) {
            $this->sendErrorResponse(400, 'Los campos {idNota} y {body} son obligatorios');
        }

        if (!$dataFromJson || !isset($dataFromJson['idNota'])) {
            $this->sendErrorResponse(400, 'El campo {idNota} es obligatorio');
        }

        if (!$dataFromJson || !isset($dataFromJson['body'])) {
            $this->sendErrorResponse(400, 'El campo {body} es obligatorio');
        }


        // Verificar si la nota existe
        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);


        $note = $notaService->obtenerNota($dataFromJson['idNota']);


        $this->existIdNoteBaseDates($note);

        // Verificar que la nota pertenezca al usuario
        $this->verifyNoteOwnership($note, $user);


        // Actualizar la nota
        $notaService->updateNota($note["id"], $dataFromJson['body']);


        // Todo tengo que ver como solucionar si no se actualiza la nota
//        if (!$updatedNote) {
//            $this->sendErrorResponse(500, 'Error al actualizar la nota');
//        }

        // Enviar respuesta de éxito
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Nota actualizada correctamente',
            'data' => $dataFromJson,
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
            $this->sendErrorResponse(403, 'Se requiere el id de la nota como Parametro en la URL, ej: ?id=40');
        }
    }

    private function existIdNoteBaseDates($getNote)
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

