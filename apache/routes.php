<?php
// TODO Aqui definimos las rutas. Por un lado obtenemos las rutas de la
//  vista, y esto no llevara a un controlador. Que luego hara una serie de cosas (Que en mi opinion, deberia hacerlo el servicio
//  y no el controlador) y desde el controlador nos dirige a la vista
//
$router->addRutasMetodoGet('/', 'index.php');
$router->addRutasMetodoGet('/about', 'about.php');
$router->addRutasMetodoGet('/contact', 'contact.php');

//$router->get('/notes', 'notes/index.php')->only('auth');
$router->addRutasMetodoGet('/notes', 'NotesController@index')->only('auth');
$router->addRutasMetodoGet('/note', 'NotesController@showNote')->only('auth');

// TODO Como se hace para eliminar una nota, sin coger el id
//  luego en el metodo destroy usa una variable $_POST['id']
$router->delete('/note/borrarNota', 'NotesController@destroy')->only('auth');

//$router->get('/note/edit', 'notes/edit.php');
$router->addRutasMetodoGet('/note/edit', 'NotesController@edit')->only('auth');

$router->patch('/note', 'NotesController@update')->only('auth');
//$router->patch('/note', 'notes/update.php');

// TODO El profesor quiere que hagamos esto: @ para todas las notas
$router->addRutasMetodoGet('/notes/create', 'NotesController@create')->only('auth');
//$router->get('/notes/create', 'notes/create.php');
//$router->post('/notes/create', 'notes/store.php');
$router->post('/notes/create', 'NotesController@store')->only('auth');

$router->addRutasMetodoGet('/register', 'registration/create.php')->only('guest');
$router->post('/register', 'registration/store.php');

$router->addRutasMetodoGet('/login', 'session/create.php')->only('guest');
$router->post('/session', 'session/store.php')->only('guest');
$router->delete('/session', 'session/destroy.php');

// Rutas para Api
$router->post('/api/login', 'UserApiController@loginUser');
$router->delete('/api/logout', 'UserApiController@logoutUser')->only("AuthApiRestFul");

$router->addRutasMetodoGet('/api/note', 'NotesController@showNote')->only("AuthApiRestFul");
$router->put('/api/note', 'NotesController@update')->only("AuthApiRestFul");
$router->delete('/api/note', 'NotesController@destroy')->only('AuthApiRestFul');
$router->post('/api/notes/create', 'NotesController@store')->only('AuthApiRestFul');
