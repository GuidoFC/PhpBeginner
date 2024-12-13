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

$input = file_get_contents("php://input"); // Sirve para obtener los datos enviados en el cuerpo (body) de una petición HTTP y guardarlos en una variable como un string.

$req = json_decode($input, true);


if ($req) {
    $nombre = $req['nombre'];
    $fecha_nacimiento = $req['fecha_nacimiento'];
    $email = $req['email'];
    $password = $req['password'];
} else {
    $nombre = $_POST['nombre'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}

// Detiene la ejecución del script
//die();


$errors = [];
$auth = new Authenticator();

// Validaciones
if (!Validator::email($email)) {


    if ($req != null) {
        sendErrorResponse(400, 'Please provide a valid email address');
    } else {
        $errors['email'] = 'Please provide a valid email address.';
    }


}

if (!Validator::string($password, 7, 255)) {

    if ($req != null) {
        sendErrorResponse(400, 'Please provide a password of at least seven characters.');
    } else {
        $errors['password'] = 'Please provide a password of at least seven characters.';
    }

}

if ($req) {
    if (!validateDateFormat($fecha_nacimiento)) {
        sendErrorResponse(400, 'Fecha invalida. Estructura YYYY-MM-DD');
    }

    if ((validateDate($fecha_nacimiento))){
        sendErrorResponse(400, 'Tu fecha de nacimiento no puede ser superior al dia de hoy');
    }
}


if (!$req) {
    if (!empty($errors)) {
        // Guarda el email y errores en la sesión
        // TODO aqui es a lo mejor para guardar los campos. Hacer luego
        Session::flash('errors', $errors);
        Session::flash('old', ['email' => $email]);
        return redirect('/register');
    }
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
    try {
        $usuarioService->crearUsuario($newUsuario);
    } catch (\mysql_xdevapi\Exception) {
        dd("Error codigo");
    }


    if ($req != null) {
        sendSuccesResponse(200, 'Usuario creado correctamente');
    }

    // TODO Lo dejo comentado para tenerlo como esquema
//    $db->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
//        'email' => $email,
//        'password' => password_hash($password, PASSWORD_BCRYPT)
//    ]);
//    $auth->login(['email' => $email]);
} else {
    if ($req != null) {
        sendErrorResponse(400, 'Email already exists! Go to Log In');
    } else {
        // Error si el correo ya existe
        $errors['email'] = "Email already exists! Go to Log In.";
        Session::flash('errors', $errors);
        Session::flash('old', ['email' => $email]);
        return redirect('/register');
    }

}


function sendErrorResponse($statusCode, $message)
{
    http_response_code($statusCode);
    echo json_encode([
        'status' => 'error',
        'message' => $message,
    ]);
    // Detiene la ejecución después de enviar la respuesta.
    exit;
}

function sendSuccesResponse($statusCode, $message, $data = null)
{
    // Construir el array de respuesta
    $response = [
        'status' => 'Peticion realizada con exito',
        'message' => $message,
    ];

    // Agregar 'dato' solo si $data no es null
    if ($data !== null) {
        $response['dato'] = $data;
    }

    // Enviar la respuesta como JSON
    http_response_code($statusCode);
    echo json_encode($response);

    // Detener la ejecución
    exit;
}

function validateDate($date)
{
    $minAge=strtotime("0 YEAR");

    $entrantAge= strtotime($date);


    if ($entrantAge < $minAge)
    {
        return false;
    }

    return true;
}

function validateDateFormat($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
