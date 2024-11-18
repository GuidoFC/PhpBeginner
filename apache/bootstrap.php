<?php

use Core\App;
use Core\Container;
use Core\Database;
use Http\controllers\notes\NotesController;
use \Core\DAO\NotaDAO;
use \Core\services\NotaService;

$container = new Container();
$container->bind('Core\Database', function (){
    $config = require(base_path('config.php'));

    return new Database($config['database']);
});
// Este paso no lo acabo de entender. Lo que hago es crear un contendor
// con una llave y una funcion de como crear un objeto para tener una conexion a la base de datos
// luego lo guardamos en la APP para poderlo sacar cuando queramos hacer una conexion en la base de datos
App::setContainer($container);

// Crear un contenedor
$container2 = new Container();
$container->bind('NotesController', function (){
    // TODO aqui le tendria que pasar
    $baseDatos = App::resolve(Database::class);
    return  new NotesController($baseDatos);
});
// Este paso no lo acabo de entender. Lo que hago es crear un contendor
// con una llave y una funcion de como crear un objeto para tener una conexion a la base de datos
// luego lo guardamos en la APP para poderlo sacar cuando queramos hacer una conexion en la base de datos
App::setContainer($container);

// TODO tiene sentido hacer estos contendores?
$container->bind('Core\DAO\NotesDAO', function (){
    return new NotaDAO(App::resolve("Core\Database"));
});

$container->bind('Core\Services\NoteService', function (){
    return new NotaService(App::resolve("Core\DAO\NotesDAO"));
});