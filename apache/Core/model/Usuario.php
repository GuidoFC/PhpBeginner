<?php

namespace Core\model;

class Usuario
{

    private $id;
    private $nombre;
    private $fechaNacimiento;
    private $correo;
    private $contrasena;
    /**
     * @param int $id El ID del usuario
     * @param string $nombre El nombre del usuario
     * @param string $fechaNacimiento La fecha de nacimiento en formato AAAA-MM-DD
     * @param string $correo El correo electrónico del usuario
     * @param string $contrasena La contraseña del usuario
     */

    public function __construct($id, $nombre, $fechaNacimiento, $correo, $contrasena)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->correo = $correo;
        $this->contrasena = $contrasena;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getFechaNacimiento(): string
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(string $fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): void
    {
        $this->correo = $correo;
    }

    public function getContrasena(): string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): void
    {
        $this->contrasena = $contrasena;
    }



}
