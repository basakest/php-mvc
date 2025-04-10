<?php

namespace App\Models;

use Framework\Model;

class Product extends Model
{
    // protected ?string $table = 'product';

    protected function validate(array $data): void
    {
        if (empty($data['name'])) {
            $this->addError('name', 'Name is required');
        }
    }

    public function getTotalNum(): int
    {
        $pdo = $this->database->getConnection();
        $sql = "SELECT COUNT(*) as total FROM `{$this->getTable()}`";
        $statement = $pdo->query($sql);
        $result = $statement->fetch();
        return intval($result['total']);
    }
}