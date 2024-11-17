<?php

namespace Http\controllers\notes;

use Core\App;
use Core\Database;
use Core\model\Nota;
use Core\Validator;

class NotesController
{
    // Ya tengo los cambios en mi master!!
    // git push origin master
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

        PathGoview("notes/create.view.php", [
            'heading' => 'Create a Note',
            'errors' => []
        ]);
    }

    public function destroy()
    {

        $currentUserId = $_SESSION['user']['id'];

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


        $currentUserId = $_SESSION['user']['id'];
        // De donde coge el ID?
        // Lo saca del  $_GET['id']
        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $_GET['id']
            ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        PathGoview("notes/edit.view.php", [
            'heading' => 'Edit a Note',
            'errors' => [],
            'note' => $note
        ]);
    }

    public function index()
    {

        $notes = $this->conexionBaseDatos->query('select * from notes where user_id = :idUser',
        ['idUser' => $_SESSION['user']['id']])->get();

        PathGoview("notes/index.view.php", [
            'heading' => 'My notes',
            'notes' => $notes
        ]);
    }

    public function showNote()
    {


        $currentUserId = $_SESSION['user']['id'];

        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $_GET['id']
            ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        PathGoview("notes/show.view.php", [
            'heading' => 'Note',
            'note' => $note
        ]);
    }

    public function store()
    {


//        $newNote = new Nota();

        $errors = [];
        if (!Validator::string($_POST['body'], 1, 100)) {
            $errors['body'] = 'A body of no more than 100 characters is required';
        }

        if (!empty($errors)) {
            PathGoview("notes/create.view.php", [
                'heading' => 'Create a Note',
                'errors' => $errors
            ]);
        }
        $this->conexionBaseDatos->query('INSERT INTO notes(body, user_id) VALUES(:body, :user_id)', [
            'body' => $_POST['body'],
            'user_id' => $_SESSION['user']['id']
        ]);
        header('location: /notes');
        die();
    }

    public function update()
    {


        $currentUserId = $_SESSION['user']['id'];

        $note = $this->conexionBaseDatos->query('select * from notes where  id = :id',
            ['id' => $_POST['id']
            ])->findOrFail();

        authorize($note['user_id'] === $currentUserId);

        $errors = [];
        if (!Validator::string($_POST['body'], 1, 100)) {
            $errors['body'] = 'A body of no more than 100 characters is required';
        }
        if (count($errors)) {
            PathGoview("notes/edit.view.php", [
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