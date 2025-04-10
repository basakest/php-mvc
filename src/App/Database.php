<?php

namespace App;

use PDO;

class Database
{
    private ?PDO $pdo = null;

    public function __construct(
        private readonly string $host,
        private readonly string $dbname,
        private readonly string $username,
        private readonly string $password,
    )
    {
    }

    public function getConnection(): PDO
    {
        if ( ! isset($this->pdo)) {
            $dsn = "mysql:dbname=$this->dbname;host=$this->host;charset=UTF8";
            $user = $this->username;
            $password = $this->password;

            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }
        return $this->pdo;
    }
}