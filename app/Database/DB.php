<?php

namespace App\Database;

use PDO;

class DB
{
    private string $host;
    private string $dbname;
    private string $user;
    private string $password;

    public function __construct(
        string $host,
        string $dbname,
        string $user,
        string $password
    )
    {
        $this->password = $password;
        $this->user = $user;
        $this->dbname = $dbname;
        $this->host = $host;
    }

    final public function connect(): PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4";

        $pdo = new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        return $pdo;
    }

    final public function checkConnection(): array
    {
        try {
            $this->connect();
            return [
                'status' => true,
                'message' => 'Connection successful'
            ];
        } catch (\PDOException $e) {
            return [
                'status' => false,
                'message' => 'Connection failed',
                'errors' => $e->getMessage()
            ];
        }
    }
}