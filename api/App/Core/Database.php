<?php

namespace App\Core;
use PDO;

class Database
{
    protected $connection;

    public function __construct($config){
        $username = $config['user'] ?? 'root';
        $password = $config['password'] ?? '';
        $dsn = 'mysql:' . http_build_query($config, '', ';');
        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    public function query($query, $params = []){
        $statement = $this->connection->prepare($query);
        return $statement;
    }

}