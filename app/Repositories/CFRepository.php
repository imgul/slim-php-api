<?php

namespace App\Repositories;

use App\Database\DB;
use PDO;

class CFRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    final public function getAll(): array
    {
        $pdo = $this->db->connect();

        $stmt = $pdo->query('SELECT * FROM cf_submissions');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    final public function create(array $data): string
    {
        $query = 'INSERT INTO cf_submissions (name, email, message, ip, user_agent, http_host, request_uri)
                          VALUES (:name, :email, :message, :ip, :user_agent, :http_host, :request_uri)';

        $pdo = $this->db->connect();

        $stmt = $pdo->prepare($query);

        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':message', $data['message'], PDO::PARAM_STR);
        $stmt->bindValue(':ip', $data['ip'], PDO::PARAM_STR);
        $stmt->bindValue(':user_agent', $data['user_agent'], PDO::PARAM_STR);
        $stmt->bindValue(':http_host', $data['http_host'], PDO::PARAM_STR);
        $stmt->bindValue(':request_uri', $data['request_uri'], PDO::PARAM_STR);

        $stmt->execute();

        return $pdo->lastInsertId();
    }
}