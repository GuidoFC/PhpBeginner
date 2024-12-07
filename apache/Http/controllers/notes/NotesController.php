<?php

namespace Http\controllers\notes;

use Core\App;
use Core\DAO\NotaDAOImplMySql;
use Core\Database;
use Core\Middleware\AuthApiRestFul;
use Core\model\Nota;
use Core\services\NotaService;
use Core\Validator;

class NotesController
{


    // Ya tengo los cambios en mi master!!
    // git push origin master
    protected $conexionBaseDatos;
    protected $currentUserId;

    // Aqui estoy aplicando la injeccion de dependencias
    public function __construct($conexionBaseDatos)
    {
        $this->conexionBaseDatos = $conexionBaseDatos;
        // Puedo hacer que si viene por una api, me guarde un dato, y si viene de web otro dato


        $authenticatedUser = AuthApiRestFul::getAuthenticatedUser();

        if ($authenticatedUser == null) {
            $this->currentUserId = $_SESSION['user']['id'];
        }

    }

    public function create()
    {

        PathGoview("notes/create.view.php", [
            'heading' => 'Create a Note',
            'errors' => []
        ]);
    }

    public function destroy()
    {

        $notaID = $_POST['id'];
        $notaDAO = new NotaDAOImplMySql();

        $notaService = new NotaService($notaDAO);
        $notaService->eliminarNota($notaID);

        header('location: /notes');
        exit();
    }

    public function edit()
    {

        $notaID = $_GET['id'];
        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);

        $getNote =  $notaService->obtenerNota($notaID);
        authorize( $getNote['user_id'] === $this->currentUserId);
        PathGoview("notes/edit.view.php", [
            'heading' => 'Edit a Note',
            'errors' => [],
            'note' => $getNote
        ]);
    }

    public function index()
    {

        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);
        $getNote =  $notaService->getAllNotasCurrentUser();

        PathGoview("notes/index.view.php", [
            'heading' => 'Todas Mis Notas Personales!!!',
            'notes' => $getNote
        ]);
    }

    public function showNote()
    {
// TODO coincide con el metodo edit()



        $authenticatedUser = AuthApiRestFul::getAuthenticatedUser();

        $notaID = $this->getNoteIdFromRequest();
        $this->validateNoteIdFromRequest($notaID);

        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);
        $getNote =  $notaService->obtenerNota($notaID);

        if ($authenticatedUser){
            $this->existIdNoteBaseDates($getNote);

            $this->verifyNoteOwnership($getNote, $authenticatedUser);
            // Enviar mensaje de resupuesta si es exitoso la peticion
            http_response_code(200);
            // enviar una respuesta HTTP con contenido en formato JSON
            // header: Informa al cliente (por ejemplo, un navegador web o una aplicación)
            // que el contenido que se enviará está en formato JSON
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'Exito en la peticion',
                'Nota' => $getNote,
            ]);
            exit;
        }

        authorize( $getNote['user_id'] === $this->currentUserId);
        PathGoview("notes/show.view.php", [
            'heading' => 'Mostrando la nota id: ' . $getNote['id'],
            'note' => $getNote
        ]);
    }


    public function store()
    {
        // El mé_todo store() lo que hace es guardar una nota que se ha creado por primera vez!!!
        $bodyNote = $_POST['body'];

        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);

        $errors = $notaService->isNoteBodyValidLength($bodyNote, "Insert");

        if (!empty($errors)) {
            PathGoview("notes/create.view.php", [
                'heading' => 'Create a Note',
                'errors' => $errors
            ]);
        }

        $notaService->insertNote($bodyNote);

        header('location: /notes');
        die();
    }

    public function update()
    {
        $authenticatedUser = AuthApiRestFul::getAuthenticatedUser();


        if ($authenticatedUser) {
            $notaID = $this->getNoteIdFromRequest();
            $this->validateNoteIdFromRequest($notaID);

            $input = file_get_contents("php://input"); // Sirve para obtener los datos enviados en el cuerpo (body) de una petición HTTP y guardarlos en una variable como un string.

            $req = json_decode($input, true); // Convierte ese string JSON en un array asociativo para que puedas trabajar con los datos más fácilmente.


            if (!$req) {
                $this->sendErrorResponse(400, 'Los campos {idNota} y {body} son obligatorios');
            }

            if (!isset($req['body'])) {
                $this->sendErrorResponse(400, 'El campo {body} es obligatorio');
            }
            $bodyNote = $req['body'];
            // tengo el id de la nota??

        } else {
            $notaID = $_POST['id'];
            $bodyNote = $_POST['body'];
        }

        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);

        $getNote = $notaService->obtenerNota($notaID);


        $errors = $notaService->isNoteBodyValidLength($bodyNote, "Update");

        if ($authenticatedUser) {
            if (!empty($errors)) {
                $this->sendErrorResponse(400, $errors['body']);
            }
            $this->existIdNoteBaseDates($getNote);


            // Verificar que la nota pertenezca al usuario
            $this->verifyNoteOwnership($getNote, $authenticatedUser);


        } else {
            if (count($errors)) {
                PathGoview("notes/edit.view.php", [
                    'heading' => 'Edit Note',
                    'errors' => $errors,
                    'note' => $getNote
                ]);
            }
            authorize( $getNote['user_id'] === $this->currentUserId);
        }


        $notaService->updateNota($notaID, $bodyNote);


        if ($authenticatedUser) {

            // Enviar respuesta de éxito
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'Nota edita con exito',
                'DatosEnviados' => $req,
            ]);
            exit;
        } else {
            header('location: /notes');
            die();
        }

    }

    public function getNoteIdFromRequest(): mixed
    {
        return $_GET['id'] ?? null;
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

    private function verifyNoteOwnership($getNote, $user)
    {
        // Verificar si la nota pertenece al usuario
        if ($getNote['user_id'] !== $user['id']) {
            $this->sendErrorResponse(403, 'Este usuario no tienes permiso para ver esta nota');
        }
    }


}