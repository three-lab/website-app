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

    public function get(array $params, bool $isSingle = false) {
        $query = "SELECT * FROM {$this->table} WHERE";
        $query .= $this->composeQuery($params, "AND");

        $stmt = $this->conn()->prepare($query);
        $stmt->execute($params);

        return $isSingle ?
            $stmt->fetchObject() :
            $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update(array $clauses, array $values)
    {
        $prefix = 'up_';
        $query = "UPDATE {$this->table} SET";

        $query .= $this->composeQuery($values, ',');
        $query .= " WHERE " . $this->composeQuery($clauses, 'AND', $prefix);

        $params = array_merge($values, keyprefix($prefix, $clauses));
        $stmt = $this->conn()->prepare($query);

        return $stmt->execute($params);
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

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->conn()->prepare($query);

        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    private function composeQuery(array $params, string $operator, string $prefix = '')
    {
        $query = " ";
        $conditions = [];

        foreach($params as $key => $value)
            $conditions[] = "`{$key}` = :{$prefix}{$key}";

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
