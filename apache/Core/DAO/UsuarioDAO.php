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


        $auth->login($crearUsuario->getCorreo(), $crearUsuario->getId());
        redirect('/');
    }

    public static function getIdUserCreate($crearUsuario): int
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


        // La busqueda en la base de datos con el find() te devuelve un Array, y tu quieres coger lo que te da
        // ek array ["id"]=> int(20)
        return $idUser["id"];

    }

    public function storeTokenInDatabase($token, $dispotivo, $user_id, $created_at, $finaliza) : void
    {
        $db = App::resolve(Database::class); // Instancia de tu clase de base de datos


        $db ->query('INSERT INTO tokens(token, dispotivo, user_id, created_at, finaliza) VALUES(:token, :dispotivo, :user_id, :created_at, :finaliza)', [
            'token' => $token,
            'dispotivo' => $dispotivo,
            'user_id' => $user_id,
            'created_at' => $created_at,
            'finaliza' => $finaliza
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

    public function deleteTokenFromDatabase($idToken)
    {

        $db = App::resolve(Database::class); // Instancia de tu clase de base de datos

        $db->query('delete from tokens where id = :id', [
            'id' => $idToken
        ]);

    }

}
