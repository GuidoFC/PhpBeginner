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

    public function crearNota(Nota $nota)
    {

    }

    public function eliminarNota($nota)
    {

       $notaDAO = new NotaDAO();

       $notaDAO->eliminarNotaBD($nota, $this->currentUserId);
    }

    public function comprobarNotaPropietario($nota) :bool
    {

    }

    public function getCurrentUserId(): mixed
    {
        return $this->currentUserId;
    }




    public function getIdNota()
    {
        return $this->idNota;
    }

    /**
     * @param mixed $idNota
     */
    public function setIdNota($idNota): void
    {
        $this->idNota = $idNota;
    }



}