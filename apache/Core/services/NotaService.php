<?php

namespace Core\services;

use Core\DAO\NotaDAOImplMySql;
use Core\model\Nota;
use Core\Validator;

class NotaService
{

    protected $currentUserId;

    public function __construct()
    {

        $this->currentUserId = $_SESSION['user']['id'];

    }

    public function obtenerNota($notaID)
    {
        $notaDAO = new NotaDAOImplMySql();

        $getNote = $notaDAO->buscarUnaNota($notaID, $this->currentUserId);
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
        $notaDAO = new NotaDAOImplMySql();

        $notaDAO->insertNote($NotaModificada, $this->currentUserId);

    }

    public function eliminarNota($notaID)
    {

        $notaDAO = new NotaDAOImplMySql();

        $notaDAO->eliminarNotaBD($notaID, $this->currentUserId);
    }

    public function updateNota($notaID, $bodyNote)
    {
        $notaDAO = new NotaDAOImplMySql();

        $notaDAO->updateNota($notaID, $bodyNote ,$this->currentUserId);
    }

    public function getAllNotasCurrentUser()
    {
        $notaDAO = new NotaDAOImplMySql();

        return $notaDAO->getAllNotasCurrentUser($this->currentUserId);
    }

    public function getCurrentUserId(): mixed
    {
        return $this->currentUserId;
    }




}