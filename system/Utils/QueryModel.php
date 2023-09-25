<?php

namespace System\Utils;

use PDO;

trait QueryModel
{
    public function find($value)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->conn()->prepare($query);

        $stmt->execute(['id' => $value]);
        return $stmt->fetchObject();
    }

    public function all()
    {
        $stmt = $this->conn()->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
