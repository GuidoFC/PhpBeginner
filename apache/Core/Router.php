<?php

namespace Core;

use Core\Middleware\Auth;
use Core\Middleware\Guest;
use Core\Middleware\Middleware;

use Http\controllers\notes\NotesController;


class Router
{
    protected $routesGuardas = [];

    public function add($method, $uri, $controller)
    {
        $this->routesGuardas[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null
        ];
        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function only($key)
    {
        $this->routesGuardas[array_key_last($this->routesGuardas)]['middleware'] = $key;
        return $this;
    }

    public function route($uri, $method)
    {

        foreach ($this->routesGuardas as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                Middleware::resolve($route['middleware']);

                // TODO: aqui cuando lo encuentras. Y ahora hacemos un metodo
                if (strpos($route['controller'], "@") !== false) {
                    // Crear una instancia de NotesController
                    $this->nuevaRuta();
                    exit();
                }

                return require base_path('Http/controllers/' . $route['controller']);
            }
        }

        // Si no encuentra ninguna ruta válida, aborta
        $this->abort();
        return $this;
    }

    public function nuevaRuta(){
        $notesController = new NotesController();
        $notesController->create();
    }

    protected function abort($code = 404)
    {
        http_response_code($code);
        require base_path("views/{$code}.php");
        die();
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}

