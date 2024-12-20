<?php

use Core\App;
use Core\DAO\UsuarioDAO;
use Core\Database;
use Core\services\UsuarioService;
use Core\Session;
use Core\Validator;
use Core\Authenticator;

$db = App::resolve(Database::class);
// TODO Tengo que guardar todas las variables que el usuari ha introducido
// Muestra todas las variables guardadas en $_POST
//var_dump($_POST);

// Detiene la ejecución del script
//die();
$nombre = $_POST['nombre'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$email = $_POST['email'];
$password = $_POST['password'];


$errors = [];
$auth = new Authenticator();

// Validaciones
if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password, 7, 255)) {
    $errors['password'] = 'Please provide a password of at least seven characters.';
}

if (!empty($errors)) {
    // Guarda el email y errores en la sesión
    // TODO aqui es a lo mejor para guardar los campos. Hacer luego
    Session::flash('errors', $errors);
    Session::flash('old', ['email' => $email]);
    return redirect('/register');
}

// Consulta si el usuario ya existe
// TODO esto lo tengo que meter en el Service y luego en el DAO
$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

if (!$user) {
    // TODO Creo el usuario y luego se lo paso al Service, este se lo Pasa al DAO y luego se hace el Insert del nuevo usuario
    $newUsuario = new \Core\model\Usuario(null, $nombre, $fecha_nacimiento, $email, $password);

    // Crear una instancia de UsuarioDAO
    $usuarioDAO = new UsuarioDAO();

// Crear una instancia de UsuarioService con la dependencia inyectada
    $usuarioService = new UsuarioService($usuarioDAO);

// Usar el servicio para crear un usuario
    $usuarioService->crearUsuario($newUsuario);

    // TODO Lo dejo comentado para tenerlo como esquema
//    $db->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
//        'email' => $email,
//        'password' => password_hash($password, PASSWORD_BCRYPT)
//    ]);
//    $auth->login(['email' => $email]);
} else {
    // Error si el correo ya existe
    $errors['email'] = "Email already exists! Go to Log In.";
    Session::flash('errors', $errors);
    Session::flash('old', ['email' => $email]);
    return redirect('/register');
}
