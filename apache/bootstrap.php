<?php

use Core\App;
use Core\Container;
use Core\Database;

$container = new Container();
$container->bind('Core\Database', function (){
    $config = require(base_path('config.php'));

    return new Database($config['database']);
});
// Este paso no lo acabo de entender. Lo que hago es crear un contendor
// con una llave y una funcion de como crear un objeto para tener una conexion a la base de datos
// luego lo guardamos en la APP para poderlo sacar cuando queramos hacer una conexion en la base de datos
App::setContainer($container);