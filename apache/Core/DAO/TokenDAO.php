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

    public function storeTokenInDatabase($token, $user_id, $created_at, $finaliza) : void
    {
        $db = App::resolve(Database::class); // Instancia de tu clase de base de datos


        $db ->query('INSERT INTO tokens(token, user_id, created_at, finaliza) VALUES(:token, :user_id, :created_at, :finaliza)', [
            'token' => $token,
            'user_id' => $user_id,
            'created_at' => $created_at,
            'finaliza' => $finaliza
        ]);

    }

}