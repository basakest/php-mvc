<?php

namespace Framework;

// When importing a class by using the use statement, it's assumed the class is in the root namespace
use App\Database;
use PDO;

abstract class Model
{
    protected ?string $table;

    private function getTable(): string
    {
        if (isset($this->table) && $this->table) {
            return $this->table;
        }
        // $this::class 类似于 self::class 吗
        // 返回实际被调用的类名(namespaced)
        $classInfo = explode('\\', $this::class);
        return strtolower(array_pop($classInfo));
    }

    public function __construct(
        private Database $database,
    )
    {
        // Typed property Framework\Model::$table must not be accessed before initialization
        // $this->table = $this->table ?: $this->getTable();
    }

    public function findAll(): false|array
    {
        $pdo = $this->database->getConnection();
        $sql = "SELECT * FROM `{$this->getTable()}`";
        $statement = $pdo->query($sql, PDO::FETCH_ASSOC);
        return $statement->fetchAll();
    }

    public function find(string $id): array|false
    {
        $pdo = $this->database->getConnection();
        $sql = "SELECT * FROM `{$this->getTable()}` WHERE `id` = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}