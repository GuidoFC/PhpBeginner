<?php

namespace Core\model;



class Nota
{
    private $id;
    private $body;
    private $userId;
    private $createdAt;

    /**
     *
     *
     * @param int $id
     * @param string $body
     * @param int $userId
     * @param string $createdAt
     */
    public function __construct($body, $userId)
    {
        // id lo genera la base de datos
        // la fecha de creacion de la nota, tmb lo hace la base de datos
        $this->body = $body;
        $this->userId = $userId;

    }

    // Getters y Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
