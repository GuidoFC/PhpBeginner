<?php

namespace Http\controllers\notes;

class NotesController
{
    public  function create()
    {
        // Mueve el contenido de create.php aquí.
        view("notes/create.view.php", [
            'heading' => 'Create a Note',
            'errors' =>[]
        ]);
    }
}