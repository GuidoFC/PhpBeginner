<?php
// TODO Aqui definimos las rutas. Por un lado obtenemos las rutas de la
//  vista, y esto no llevara a un controlador. Que luego hara una serie de cosas (Que en mi opinion, deberia hacerlo el servicio
//  y no el controlador) y desde el controlador nos dirige a la vista
//
$router->get('/', 'index.php');
$router->get('/about', 'about.php');
$router->get('/contact', 'contact.php');

//$router->get('/notes', 'notes/index.php')->only('auth');
$router->get('/notes', 'NotesController@index')->only('auth');
$router->get('/note', 'NotesController@showNote')->only('auth');

// TODO Como se hace para eliminar una nota, sin coger el id
//  luego en el metodo destroy usa una variable $_POST['id']
$router->post('/note/borrarNota', 'NotesController@destroy')->only('auth');

//$router->get('/note/edit', 'notes/edit.php');
$router->get('/note/edit', 'NotesController@edit')->only('auth');

$router->patch('/note', 'NotesController@update')->only('auth');
//$router->patch('/note', 'notes/update.php');

// TODO El profesor quiere que hagamos esto: @ para todas las notas
$router->get('/notes/create', 'NotesController@create')->only('auth');
//$router->get('/notes/create', 'notes/create.php');
//$router->post('/notes/create', 'notes/store.php');
$router->post('/notes/create', 'NotesController@store')->only('auth');

$router->get('/register', 'registration/create.php')->only('guest');
$router->post('/register', 'registration/store.php');

$router->get('/login', 'session/create.php')->only('guest');
$router->post('/session', 'session/store.php')->only('guest');
$router->delete('/session', 'session/destroy.php')->only('auth');