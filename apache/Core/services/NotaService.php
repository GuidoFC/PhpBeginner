<?php

namespace Core\services;

use Core\DAO\NotaDAO;
use Core\model\Nota;

class NotaService
{

    protected $currentUserId;

    public function __construct()
    {

        $this->currentUserId = $_SESSION['user']['id'];

    }

    public function editarNota( $notaID)
    {
        $notaDAO = new NotaDAO();

        $getNote = $notaDAO->editarNota($notaID, $this->currentUserId);
        return $getNote;
    }

    public function eliminarNota($notaID)
    {

        $notaDAO = new NotaDAO();

         $notaDAO->eliminarNotaBD($notaID, $this->currentUserId);
    }


    public function getCurrentUserId(): mixed
    {
        return $this->currentUserId;
    }





}