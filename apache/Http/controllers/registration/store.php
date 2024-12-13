<?php

use Core\App;
use Core\DAO\UsuarioDAO;
use Core\Database;
use Core\services\UsuarioService;
use Core\Session;
use Core\Validator;
use Core\Authenticator;

$conexionBaseDatos = App::resolve(Database::class);
// TODO Tengo que guardar todas las variables que el usuari ha introducido
// Muestra todas las variables guardadas en $_POST
//var_dump($_POST);










