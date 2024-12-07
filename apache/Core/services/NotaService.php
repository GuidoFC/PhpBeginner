<?php

namespace Core\services;

use Core\DAO\NotaDAOImplMySql;
use Core\model\Nota;
use Core\Validator;
use Core\interfaces\CrudNota;

class NotaService
{

    protected $currentUserId;
    private CrudNota $notaDAO;

    public function __construct($notaDAO)
    {
        $this->notaDAO = $notaDAO;
        $this->currentUserId = $_SESSION['user']['id'];

    }

    public function obtenerNota($notaID)
    {


        $getNote = $this->notaDAO->buscarUnaNota($notaID, $this->currentUserId);
        return $getNote;
    }

    public function isNoteBodyValidLength($NotaModificada, $InsertOrUpdate)
    {

        $errors = [];
        if (!Validator::string($NotaModificada, 1, 100)) {

            switch ($InsertOrUpdate) {
                case "Insert":
                    $errors['body'] = 'Intentas crear una nota vacia!! Min 1, Max 100 caracteres';
                    break;
                case "Update":
                    $errors['body'] = 'La modicacion de la nota, tiene que tener un cuerpo entre 1 y 100 caracteres';
                    break;
                default:
                    $errors['body'] = 'El método isNoteBodyValidLength() tiene un fallo';
                    break;
            }

        }

        return $errors;
    }

    public function insertNote($NotaModificada)
    {
        // TODO donde es crear el objeto $notaDAO


        $this->notaDAO->insertNote($NotaModificada, $this->currentUserId);

    }

    public function eliminarNota($notaID)
    {



        $this->notaDAO->eliminarNotaBD($notaID, $this->currentUserId);
    }

    public function updateNota($notaID, $bodyNote)
    {


        $this->notaDAO->updateNota($notaID, $bodyNote);
    }

    public function getAllNotasCurrentUser()
    {


        return $this->notaDAO->getAllNotasCurrentUser($this->currentUserId);
    }

    public function getCurrentUserId(): mixed
    {
        return $this->currentUserId;
    }


}