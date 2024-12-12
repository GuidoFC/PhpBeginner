<?php
use Core\Response;
function urlIs($value)
{
    // $_SERVER['REQUEST_URI'] te dice en que URL estas actualmente
    return $_SERVER['REQUEST_URI'] === $value;
}
function dd(...$vars) {
    foreach ($vars as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    die(); // Detiene la ejecución del script
}
function authorize($condition)
{

    // si la condicion que me pasa es falsa, porque la nota no coincide con el usuario
    // te enviare a un view de que no tienes autorización a de ver dicha nota.
    // La condicion viene como falsa, pero con el "!" se convierte en TRUE entonces se
    // ejecuta el if
    if (! $condition){
        abort(Response::FORBIDDEN);
    }
}
function base_path($path)
{
    return BASE_PATH . $path;
}
function PathGoview($path, $attribute = []): void
{

    // Las claves del arreglo ('name' y 'age') se convierten en variables $name y $age con sus respectivos valores.
    // En este caso el array tiene un key-value, en este caso, key = heading y value = About.
    // Entonces se crea una variable $heading que tiene guardado el valor About
    extract($attribute);
    require base_path('views/'. $path);
}
function abort($code = 404)
{
    http_response_code($code);
    require base_path("views/{$code}.php");
    die();
}
function redirect($path)
{
    header("location: {$path}");
    exit();
}
function old($key, $default = '')
{
    return Core\Session::get('old')[$key] ?? $default;
}