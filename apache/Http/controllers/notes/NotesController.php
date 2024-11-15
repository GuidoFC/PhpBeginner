<?php

namespace Http\controllers\notes;
use Core\App;
use Core\Database;

class NotesController
{
    public static function create()
    {
        // Mueve el contenido de create.php aquí.
        view("notes/create.view.php", [
            'heading' => 'Create a Note',
            'errors' =>[]
        ]);
    }
    public static function edit(){
        $db = App::resolve(Database::class);

        $currentUserId = 1;

        $note = $db->query('select * from notes where  id = :id',
            ['id' => $_GET['id']
            ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        view("notes/edit.view.php", [
            'heading' => 'Edit a Note',
            'errors' =>[],
            'note' => $note
        ]);
    }
}