<?php

namespace Core\DAO;

use Core\Authenticator;
use Core\interfaces\CrudUsuario;
use Core\interfaces\Usuario;
use Core\App;
use Core\Database;

class UsuarioDAO implements CrudUsuario
{
    // email: guido@gmail.com
// email: 19@gmail.com
// contra 123456789

    public function crearUsuarioBD(Usuario|\Core\model\Usuario $crearUsuario)
    {
        $db = App::resolve(Database::class);
        $db->query(
            'INSERT INTO users (email, password, nombre, fechaNacimiento) VALUES (:email, :password, :nombre, :fechaNacimiento)',
            [
                'email' => $crearUsuario->getCorreo(),
                'password' => $crearUsuario->getContrasena(),
                'nombre' => $crearUsuario->getNombre(),
                'fechaNacimiento' => $crearUsuario->getFechaNacimiento()
            ]
        );
        // TODO deberia estar en el service

        $crearUsuario->setId(self::getIdUserCreate($crearUsuario));
        $auth = new Authenticator();


        $auth->login($crearUsuario->getCorreo() , $crearUsuario->getId());
        redirect('/');
    }

    public static function getIdUserCreate($crearUsuario) : int
    {
        $db = App::resolve(Database::class);
        $email = $crearUsuario->getCorreo();

        // Ejecutar la consulta para obtener el ID del usuario
        $result = $db->query(
            'SELECT id FROM users WHERE email = :email',
            ['email' => $email]
        );

        // Obtener el ID de la primera fila del resultado

        $idUser = $result->find();


        return $idUser["id"];

    }

    public function storeTokenInDatabase($userId, $token): void
    {
        $db = App::resolve(Database::class); // Instancia de tu clase de base de datos
        $db->query("UPDATE users SET api_token = :token WHERE id = :id", [
            'token' => $token,
            'id' => $userId
        ]);
    }

    function getUserByApiToken($providedToken)
    {


        $db = App::resolve(Database::class); // Instancia de tu clase de base de datos


        $user = $db->query('select * from users where api_token = :token', [
            'token' => $providedToken
        ])->find();


        // El token es válido, puedes continuar
        return $user;
    }

}
