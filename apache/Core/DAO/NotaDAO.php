<?php

namespace Core\DAO;

use Core\App;
use Core\Database;
use Core\model\Nota;

class NotaDAO
{

    protected $conexionBaseDatos;
    protected $currentUserId;

    public function __construct()
    {
        $this->conexionBaseDatos = App::resolve(Database::class);
        $this->currentUserId = $_SESSION['user']['id'];
    }

    public function crearNotaBD(Nota $nota)
    {

    }

    public function editarNota($notaId, $currentUserId)
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

}
