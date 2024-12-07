<?php
namespace Core;
use PDO;
class Database {
    public $connection;
    public $statement;

    public function __construct($config, $username = 'admin', $password = 'admin')
    {
        // Asegúrate de que 'dbname' sea 'personas', como en docker-compose.yml
        $dsn = 'mysql:host=mysql257;dbname=personas';
        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);

        return $this;
    }
    public function get()
    {
        return $this->statement->fetchAll();
    }
    public function find(){
        return $this->statement->fetch(); // fetch = ir a buscar
    }
    public function findOrFail()
    {
        $result = $this->find();
        if (!$result){
            abort();
        }
        return $result;
    }
}