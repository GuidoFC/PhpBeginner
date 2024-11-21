<?php

namespace Http\controllers\notes;

use Core\App;
use Core\DAO\NotaDAOImplMySql;
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
        $notaDAO = new NotaDAOImplMySql();

        $notaService = new NotaService($notaDAO);
        $notaService->eliminarNota($notaID);

        header('location: /notes');
        exit();
    }

    public function edit()
    {

        $notaID = $_GET['id'];
        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);

        $getNote =  $notaService->obtenerNota($notaID);

        PathGoview("notes/edit.view.php", [
            'heading' => 'Edit a Note',
            'errors' => [],
            'note' => $getNote
        ]);
    }

    public function index()
    {

        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);
        $getNote =  $notaService->getAllNotasCurrentUser();

        PathGoview("notes/index.view.php", [
            'heading' => 'Todas Mis Notas Personales!!!',
            'notes' => $getNote
        ]);
    }

    public function showNote()
    {
// TODO coincide con el metodo edit()

        $notaID = $_GET['id'];

        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);
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

        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);

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

        $notaID = $_POST['id'];
        $bodyNote = $_POST['body'];
        $notaDAO = new NotaDAOImplMySql();
        $notaService = new NotaService($notaDAO);

        $getNote =  $notaService->obtenerNota($notaID);

        $errors = $notaService->isNoteBodyValidLength($bodyNote, "Update");
        if (count($errors)) {
            PathGoview("notes/edit.view.php", [
                'heading' => 'Edit Note',
                'errors' => $errors,
                'note' => $getNote
            ]);
        }

        $notaService->updateNota($notaID, $bodyNote);



        header('location: /notes');
        die();
    }


}