<?php

namespace Http\controllers\notes;

use Core\App;
use Core\Database;
use Core\Validator;

class NotesController
{
    protected $conexionBaseDatos;
    protected $currentUserId;

    // Aqui estoy aplicando la injeccion de dependencias
    public function __construct($conexionBaseDatos)
    {
        $this->conexionBaseDatos = $conexionBaseDatos;
        $this->currentUserId = 1; // Esto debería venir de la sesión del usuario en lugar de estar fijo
    }

    public function create()
    {
        view("notes/create.view.php", [
            'heading' => 'Create a Note',
            'errors' => []
        ]);
    }

    public function destroy()
    {

        $currentUserId = 1;

        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $_POST['id']])->findOrFail();

        authorize($note['user_id'] === $currentUserId);
//form was submitted. delete the current note.
        $this->conexionBaseDatos->query('delete from notes where id = :id', [
            'id' => $_POST['id']
        ]);
        header('location: /notes');
        exit();
    }

    public function edit()
    {


        $currentUserId = 1;

        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $_GET['id']
            ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        view("notes/edit.view.php", [
            'heading' => 'Edit a Note',
            'errors' => [],
            'note' => $note
        ]);
    }

    public function index()
    {

        $notes = $this->conexionBaseDatos->query('select * from notes where user_id = 1')->get();

        view("notes/index.view.php", [
            'heading' => 'My notes',
            'notes' => $notes
        ]);
    }

    public function showNote()
    {


        $currentUserId = 1;

        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $_GET['id']
            ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        view("notes/show.view.php", [
            'heading' => 'Note',
            'note' => $note
        ]);
    }

    public function store()
    {


        $errors = [];
        if (!Validator::string($_POST['body'], 1, 100)) {
            $errors['body'] = 'A body of no more than 100 characters is required';
        }

        if (!empty($errors)) {
            view("notes/create.view.php", [
                'heading' => 'Create a Note',
                'errors' => $errors
            ]);
        }
        $this->conexionBaseDatos->query('INSERT INTO notes(body, user_id) VALUES(:body, :user_id)', [
            'body' => $_POST['body'],
            'user_id' => 1
        ]);
        header('location: /notes');
        die();
    }

    public function update()
    {


        $currentUserId = 1;

        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $_POST['id']
            ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        $errors = [];
        if (!Validator::string($_POST['body'], 1, 100)) {
            $errors['body'] = 'A body of no more than 100 characters is required';
        }
        if (count($errors)) {
            view("notes/edit.view.php", [
                'heading' => 'Edit Note',
                'errors' => $errors,
                'note' => $note
            ]);
        }
        $this->conexionBaseDatos->query('update notes set body = :body where id = :id', [
            'id' => $_POST['id'],
            'body' => $_POST['body']
        ]);
        echo header('location: /notes');
        header('location: /notes');
        die();
    }


}