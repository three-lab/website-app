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

    public function get(array $params) {
        $stmt = $this->conn()->prepare($this->composeQuery($params, "AND"));
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function all()
    {
        $stmt = $this->conn()->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function insert(array $params)
    {
        $stmt = $this->conn()->prepare($this->composeInsertQuery($params));
        $stmt->execute($params);

        return $this->conn()->lastInsertId();
    }

    private function composeQuery(array $params, string $operator)
    {
        $query = "SELECT * FROM {$this->table} WHERE ";
        $conditions = [];

        foreach($params as $key => $value)
            $conditions[] = "`{$key}` = :{$key}";

        $query .= implode(" $operator ", $conditions);
        return $query;
    }

    private function composeInsertQuery(array $params)
    {
        $query = "INSERT INTO {$this->table} ";
        $columns = [];
        $values = [];

        foreach($params as $key => $value) {
            $columns[] = "`$key`";
            $values[] = ":{$key}";
        }

        $query .= "(" . implode(',', $columns) .") ";
        $query .= "VALUES (" . implode(',', $values) . ")";

        return $query;
    }
}
