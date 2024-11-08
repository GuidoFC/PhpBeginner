<?php

use Core\App;
use Core\Database;
use Core\Session;
use Core\Validator;
use Core\Authenticator;

$db = App::resolve(Database::class);
$email = $_POST['email'];
$password = $_POST['password'];
$errors = [];
$auth = new Authenticator();

// Validaciones
if(!Validator::email($email)){
    $errors['email'] = 'Please provide a valid email address.';
}

if(!Validator::string($password, 7, 255)){
    $errors['password'] = 'Please provide a password of at least seven characters.';
}

if (!empty($errors)) {
    // Guarda el email y errores en la sesión
    Session::flash('errors', $errors);
    Session::flash('old', ['email' => $email]);
    return redirect('/register');
}

// Consulta si el usuario ya existe
$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

if (!$user) {
    // Inserta el nuevo usuario
    $db->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ]);
    $auth->login(['email' => $email]);
} else {
    // Error si el correo ya existe
    $errors['email'] = "Email already exists! Go to Log In.";
    Session::flash('errors', $errors);
    Session::flash('old', ['email' => $email]);
    return redirect('/register');
}
