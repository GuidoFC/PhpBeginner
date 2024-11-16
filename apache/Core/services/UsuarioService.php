<?php

namespace Core\services;
use Core\DAO\UsuarioDAO;
use Core\model\Usuario;

class UsuarioService
{
    private $usuarioDAO;

    // Constructor para inyectar la dependencia
    public function __construct(UsuarioDAO $usuarioDAO)
    {
        $this->usuarioDAO = $usuarioDAO;
    }

    public function crearUsuario(Usuario $usuario)
    {
        // Antes de crear el usuario encriptaremos la contraseña
        $getContrasena = $usuario->getContrasena();
        $contrasenaCripatada = UsuarioService::encriptarContrasena($getContrasena);

        $usuario->setContrasena($contrasenaCripatada);

//        var_dump("Hola",$contrasenaCripatada, $getContrasena);
//        die();

        // Usar la instancia de UsuarioDAO inyectada
        $this->usuarioDAO->crearUsuarioBD($usuario);
    }

    private static function encriptarContrasena($contrasenaSinEncriptar)
    {
        return password_hash($contrasenaSinEncriptar, PASSWORD_BCRYPT);
    }
}