<?php


namespace Core\interfaces;

use Core\model\Usuario;

interface CrudNota
{

    public function buscarUnaNota($notaId, $currentUserId);
    public function eliminarNotaBD($notaID, $currentUserId);
    public function insertNote($NotaModificada, $currentUserId);
    public function updateNota($notaID, $bodyNote);
    public function getAllNotasCurrentUser($currentUserId);

}
