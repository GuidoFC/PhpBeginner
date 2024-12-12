<?php

namespace Core\Middleware;

class Middleware // Traducción de Middleware --> Filtro: Por su rol de permitir o bloquear solicitudes.
{
    public const MAPEAR_AUTORIZACION = [
        'guest' => Guest::class,
        'auth' => Auth::class,
        'AuthApiRestFul' => AuthApiRestFul::class
    ];
    public static function resolve($key)
    {
        if (!$key){
            return;
        }
        $middleware = static::MAPEAR_AUTORIZACION[$key] ?? false;
        // Si $middleware = false implica que !$middleware es TRUE entonces se ejecutaria el condicional
        if(!$middleware){
            throw new \Exception("no matching middle ware for {$key}. ");
        }
        (new $middleware)->handle();
    }
}