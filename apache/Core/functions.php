<?php
use Core\Response;
function urlIs($value)
{
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