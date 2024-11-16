<?php

namespace Core;

// Empezamos con la tarea
//  Implementar autentificació i autorització.
class Authenticator
{
    public function attempt($email, $password): bool
    {
        // Aqui cojo todos los datos del User que esta en mi base de datos
        $user = App::resolve(Database::class)
            ->query('select * from users where email = :email', [
                'email' => $email
            ])->find();


        if ($user) {
            //  $user['password'] -> contraseña que hay guarda en la base de datos
            // $password -> contraseña que ha introducido para hacer el login

            if (password_verify($password, $user['password'])) {

                $this->login(
                    $user['email'], $user['id']
                );
                return true;
            }
        }
        return false;
    }

    public function login($email, $id): void
    {
        $_SESSION['user'] = [
            'email' => $email,
            'id' => $id
        ];
//        dd($_SESSION['user']);

        session_regenerate_id(true);
        redirect('/');
    }

    public function logout(): void
    {
        Session::destroy();
    }
}