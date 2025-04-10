<?php

namespace Framework;

// When importing a class by using the use statement, it's assumed the class is in the root namespace
use App\Database;
use PDO;

abstract class Model
{
    protected ?string $table;

    protected function getTable(): string
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
        protected Database $database,
    )
    {
        // Typed property Framework\Model::$table must not be accessed before initialization
        // $this->table = $this->table ?: $this->getTable();
    }

    protected array $errors = [];

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function validate(array $data): void
    {
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

    public function insert(array $data): bool
    {
        $this->validate($data);
        if ( ! empty($this->errors)) {
            return false;
        }
        $pdo = $this->database->getConnection();
        $placeholder = array_fill(0, count($data), '?');
        $placeholder = implode(', ', $placeholder);
        $column = array_keys($data);
        $column  = implode(', ', $column);
        $sql = "INSERT INTO `{$this->getTable()}` ($column) VALUES ($placeholder)";
        $statement = $pdo->prepare($sql);
        $i = 1;
        foreach ($data as $value) {
            $type = match (gettype($value)) {
                'integer' => PDO::PARAM_INT,
                'boolean' => PDO::PARAM_BOOL,
                'NULL'    => PDO::PARAM_NULL,
                default   => PDO::PARAM_STR,
            };
            $statement->bindValue($i++, $value, $type);
        }
        return $statement->execute();
    }

    public function update(string $id, array $data): bool
    {
        $this->validate($data);
        if ( ! empty($this->errors)) {
            return false;
        }
        $pdo = $this->database->getConnection();
        if (isset($data['id'])) {
            unset($data['id']);
        }
        $placeholder = '';
        foreach ($data as $key => $value) {
            $placeholder .= "`$key` =?,";
        }
        $placeholder = rtrim($placeholder, ',');

        $sql = "UPDATE `{$this->getTable()}` SET $placeholder WHERE `id` = ?";
        $data[] = $id;
        $statement = $pdo->prepare($sql);
        $i = 1;
        foreach ($data as $value) {
            $type = match (gettype($value)) {
                'integer' => PDO::PARAM_INT,
                'boolean' => PDO::PARAM_BOOL,
                'NULL'    => PDO::PARAM_NULL,
                default   => PDO::PARAM_STR,
            };
            $statement->bindValue($i++, $value, $type);
        }
        return $statement->execute();
    }

    public function delete(string $id): bool
    {
        $pdo = $this->database->getConnection();
        $sql = "DELETE FROM `{$this->getTable()}` WHERE `id` = ?";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(1, $id, PDO::PARAM_INT);
        return $statement->execute();
    }

    public function getLastInsertId(): string|false
    {
        $pdo = $this->database->getConnection();
        return $pdo->lastInsertId();
    }
}