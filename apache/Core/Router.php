<?php

namespace Core;

use Core\Middleware\Auth;
use Core\Middleware\Guest;
use Core\Middleware\Middleware;

use Http\controllers\notes\NotesController;


class Router
{
    protected $routesGuardas = [];

    public function addRutas($method, $uri, $CarpetaControllers)
    {
       // si vamos a routes.php estoy añadiendo todas las rutas en mi array $routesGuardas
        $this->routesGuardas[] = [
            'uri' => $uri,
            'controller' => $CarpetaControllers,
            'method' => $method,
            'middleware' => null
        ];
        return $this;
    }

    public function addRutasMetodoGet($uri, $CarpetaControllers)
    {
        return $this->addRutas('GET', $uri, $CarpetaControllers);
    }

    public function delete($uri, $Carpetacontroller)
    {
        return $this->addRutas('DELETE', $uri, $Carpetacontroller);
    }

    public function post($uri, $Carpetacontroller)
    {
        return $this->addRutas('POST', $uri, $Carpetacontroller);
    }

    public function patch($uri, $Carpetacontroller)
    {

        return $this->addRutas('PATCH', $uri, $Carpetacontroller);
    }

    public function put($uri, $Carpetacontroller)
    {
        return $this->addRutas('PUT', $uri, $Carpetacontroller);
    }

    public function only($key)
    {
//        dd( $this->routesGuardas[array_key_last($this->routesGuardas)]); // estoy asignanado un valor al middleware, en este caso le asigno el valor "auth", "guest", "AuthApiRestFul"
        $this->routesGuardas[array_key_last($this->routesGuardas)]['middleware'] = $key; //  middleware traducción -> Filtro: Por su rol de permitir o bloquear solicitudes.
        return $this;
    }

    public function route($uri, $method)
    {

        foreach ($this->routesGuardas as $route) {

            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {

                Middleware::resolve($route['middleware']);


                // TODO: buscamos los controladores que tiene un @
                if (strpos($route['controller'], "@") !== false) {

                    $parts = explode('@', $route['controller']);
                    $functionClass = $parts[1]; // Esto te dará "el metodo del NotesController"
                    $class  = $parts[0]; // Tengo el nombre del controlador



                    return $this->nuevaRutaConArroba($functionClass, $class);

                }
                return require base_path('Http/controllers/' . $route['controller']);
            }
        }

        if (strpos($route['uri'], "/api") !== false) {

            $Isapi = true;
            $this->abort($Isapi);
            die();
        }else{
            // Si no encuentra ninguna ruta válida, aborta
            $this->abort();
            return $this;
        }

    }

    public function nuevaRutaConArroba($functionClass, $classControler ){



//      TODO: NO se si crear un contendor es una forma dinamica de hacer esto.
        $controllerInstance = App::resolve($classControler);


//        $controlerNote = new NotesController($baseDatos);
        // TODO lo queria hacer dinamico, pero no funciona, el parametro $classControler no
        //  me lo interprea como una clase sino como un String
//        $controllerInstance = new $classControler() ;
//        dd($controllerInstance);

         return  $controllerInstance->$functionClass();

    }

    protected function abort($Isapi = false)
    {
        $code = 404;
        if ($Isapi) {
        $this->sendErrorResponse($code, "Esta ruta no existe");
        }else{
            http_response_code($code);
            require base_path("views/{$code}.php");
            die();
        }

    }


    private function sendErrorResponse($statusCode, $message)
    {

        http_response_code($statusCode);
        echo json_encode([
            'status' => 'error',
            'message' => $message,
        ]);
        // Detiene la ejecución después de enviar la respuesta.
        exit;
    }
    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}

