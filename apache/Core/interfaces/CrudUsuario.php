<?php


namespace Core\interfaces;

use Core\model\Usuario;

interface CrudUsuario
{

    public function crearUsuarioBD(Usuario $crearUsuario);
}
