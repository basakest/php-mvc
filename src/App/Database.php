<?php

namespace App;

use PDO;

class Database
{
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
        $dsn = "mysql:dbname=$this->dbname;host=$this->host;charset=UTF8";
        $user = $this->username;
        $password = $this->password;

        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }
}