<?php

namespace Core\DAO;

use Core\App;
use Core\Database;
use Core\interfaces\CrudNota;
use Core\model\Nota;

class NotaDAOImplMySql implements CrudNota
{

    protected $conexionBaseDatos;


    public function __construct()
    {
        $this->conexionBaseDatos = App::resolve(Database::class);

    }


    public function buscarUnaNota($notaId, $currentUserId)
    {
        // Tengo la info de toda la nota
        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $notaId
            ])->find();


        return $note;
    }

    public function eliminarNotaBD($notaID, $currentUserId)
    {

        $this->conexionBaseDatos->query('delete from notes where id = :id', [
            'id' => $notaID
        ]);

    }

    public function insertNote($NotaModificada, $currentUserId)
    {

        $this->conexionBaseDatos->query('INSERT INTO notes(body, user_id) VALUES(:body, :user_id)', [
            'body' => $NotaModificada,
            'user_id' => $currentUserId
        ]);
    }

    public function updateNota($notaID, $bodyNote)
    {

        $this->conexionBaseDatos->query('update notes set body = :body where id = :id', [
            'id' => $notaID,
            'body' => $bodyNote
        ]);

    }

    public function getAllNotasCurrentUser($currentUserId)
    {
        $notes = $this->conexionBaseDatos->query('select * from notes where user_id = :idUser',
            ['idUser' => $currentUserId])->get();

        return $notes;
    }

}

