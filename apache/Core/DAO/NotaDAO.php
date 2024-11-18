<?php

namespace Core\DAO;

use Core\App;
use Core\Database;
use Core\model\Nota;

class NotaDAO
{

    protected $conexionBaseDatos;


    public function __construct()
    {
        $this->conexionBaseDatos = App::resolve(Database::class);

    }

    public function crearNotaBD(Nota $nota)
    {

    }

    public function buscarUnaNota($notaId, $currentUserId)
    {
        // Tengo la info de toda la nota
        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $notaId
            ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        return $note;
    }

    public function eliminarNotaBD($notaID, $currentUserId)
    {


        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $notaID])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        $this->conexionBaseDatos->query('delete from notes where id = :id', [
            'id' => $_POST['id']
        ]);


    }

    public function insertNote($NotaModificada, $currentUserId){

        $this->conexionBaseDatos->query('INSERT INTO notes(body, user_id) VALUES(:body, :user_id)', [
            'body' => $NotaModificada,
            'user_id' => $currentUserId
        ]);
    }

}
