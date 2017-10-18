<?php

namespace Vios\Devops;

class PDO
{
    private $pdo;

    public function __construct($dbName, $password, $user = 'root', $host = 'localhost')
    {
        define( 'MYSQL_HOST', $host );
        define( 'MYSQL_USER', $user );
        define( 'MYSQL_PASSWORD', $password );
        define( 'MYSQL_DB_NAME', $dbName);

        try {
            $this->pdo = new \PDO( 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ] );
        } catch ( \PDOException $e ) {
            echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();
            exit;
        }
    }

    public function query(string $sql) : array
    {
        $result = $this->pdo->query($sql);
        return $result->fetchAll();
    }

}