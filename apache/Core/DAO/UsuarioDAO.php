<?php

namespace Core\DAO;

use Core\interfaces\CrudUsuario;
use Core\interfaces\Usuario;
use Core\App;
use Core\Database;

class UsuarioDAO implements CrudUsuario
{


    public function crearUsuarioBD(Usuario|\Core\model\Usuario $crearUsuario)
    {
        $db = App::resolve(Database::class);
        $db->query(
            'INSERT INTO users (email, password, nombre, fechaNacimiento) VALUES (:email, :password, :nombre, :fechaNacimiento)',
            [
                'email' => $crearUsuario->getCorreo(),
                'password' => password_hash($crearUsuario->getContrasena(), PASSWORD_BCRYPT),
                'nombre' => $crearUsuario->getNombre(),
                'fechaNacimiento' => $crearUsuario->getFechaNacimiento()
            ]
        );
        redirect('/');
    }
}
