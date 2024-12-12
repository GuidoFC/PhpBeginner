<?php

use Core\Session;
use Core\ValidationException;

session_start();

const BASE_PATH = __DIR__.'/../';

// require: Incluye otro archivo PHP en el archivo actual.
require BASE_PATH . 'Core/functions.php';


spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    require base_path("{$class}.php");
});

require base_path('bootstrap.php');

// Creamos un objeto de la clase Router
$router = new \Core\Router();
// TODO que hace aqui?
//  gracias al require el $routes tendrá acceso a todas las rutas definidas en routes.php.
//  PEro cuando usamos esta variable $routes

$routes = require base_path('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path']; // me da la ruta en la que me encuentro

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD']; // me deci si estoy por el metodo post, get, put, delete


try {
    // Este es el paso donde pasamos de la vista al controlador
    $router->route($uri, $method);
} catch (ValidationException $exception) {
    Session::flash('errors', $exception->errors);
    Session::flash('old', $exception->old);

    return redirect($router->previousUrl());
}

Session::unflash();