<?php

namespace App\Models;

// When importing a class by using the use statement, it's assumed the class is in the root namespace
use App\Database;
use PDO;

class Product
{
    public function __construct(
        private readonly Database $database,
    )
    {
    }

    public function getData(): false|array
    {
        $pdo = $this->database->getConnection();
        $sql = 'SELECT * FROM `product`';
        $statement = $pdo->query($sql, PDO::FETCH_ASSOC);
        return $statement->fetchAll();
    }
}