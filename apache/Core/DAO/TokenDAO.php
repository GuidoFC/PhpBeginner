<?php

namespace Core\DAO;

use Core\Authenticator;
use Core\interfaces\CrudUsuario;
use Core\interfaces\Usuario;
use Core\App;
use Core\Database;

class TokenDAO
{
    public function deleteTokenFromDatabase($idToken)
    {

        $db = App::resolve(Database::class); // Instancia de tu clase de base de datos

        $db->query('delete from tokens where id = :id', [
            'id' => $idToken
        ]);

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

}