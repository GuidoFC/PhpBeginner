<?php

namespace Http\controllers\notes;

use Core\App;
use Core\Database;
use Core\model\Nota;
use Core\services\NotaService;
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
        $this->currentUserId = $_SESSION['user']['id'];
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

        $notaID = $_POST['id'];

        $notaService = new NotaService();
        $notaService->eliminarNota($notaID);

        header('location: /notes');
        exit();
    }

    public function edit()
    {

        $notaID = $_GET['id'];

        $notaService = new NotaService();
        $getNote =  $notaService->obtenerNota($notaID);

        PathGoview("notes/edit.view.php", [
            'heading' => 'Edit a Note',
            'errors' => [],
            'note' => $getNote
        ]);
    }

    public function index()
    {

        $notes = $this->conexionBaseDatos->query('select * from notes where user_id = :idUser',
            ['idUser' => $_SESSION['user']['id']])->get();

        PathGoview("notes/index.view.php", [
            'heading' => 'Todas Mis Notas Personales',
            'notes' => $notes
        ]);
    }

    public function showNote()
    {
// TODO coincide con el metodo edit()

        $notaID = $_GET['id'];

        $notaService = new NotaService();
        $getNote =  $notaService->obtenerNota($notaID);


        PathGoview("notes/show.view.php", [
            'heading' => 'Mostrando la nota id: ' . $getNote['id'],
            'note' => $getNote
        ]);
    }


    public function store()
    {
        // El mé_todo store() lo que hace es guardar una nota que se ha creado por primera vez!!!
        $bodyNote = $_POST['body'];

        $notaService = new NotaService();

        $errors = $notaService->isNoteBodyValidLength($bodyNote, "Insert");

        if (!empty($errors)) {
            PathGoview("notes/create.view.php", [
                'heading' => 'Create a Note',
                'errors' => $errors
            ]);
        }

        $notaService->insertNote($bodyNote);

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
            $errors['body'] = 'La modicacion de la nota, tiene que tener un cuerpo entre 1 y 100 caracteres';
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

        header('location: /notes');
        die();
    }


}