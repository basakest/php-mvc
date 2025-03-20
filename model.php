<?php

class Model
{
    public function getData(): false|array
    {
        $dsn = 'mysql:dbname=mvc;host=127.0.0.1;charset=UTF8';
        $user = 'root';
        $password = '';

        $pdo = new PDO($dsn, $user, $password);
        $sql = 'SELECT * FROM `product`';
        $statement = $pdo->query($sql,  PDO::FETCH_ASSOC);
        return $statement->fetchAll();
    }
}