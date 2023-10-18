<?php

namespace System\Utils;

use PDO;
use System\Exceptions\QueryException;

trait QueryModel
{
    public function find($value)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->conn()->prepare($query);

        $stmt->execute(['id' => $value]);
        return $this->mapToModel($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function get(array $params, bool $isSingle = false) {
        $query = "SELECT * FROM {$this->table} WHERE";
        $query .= $this->composeQuery($params, "AND");

        $stmt = $this->conn()->prepare($query);
        $stmt->execute($params);

        return $isSingle ?
            $this->mapToModel($stmt->fetch(PDO::FETCH_ASSOC)) :
            (array_map(fn($result) => $this->mapToModel($result), $stmt->fetchAll(PDO::FETCH_ASSOC)));
    }

    public function update(array $values, ?array $clauses = null)
    {
        $prefix = 'up_';
        $query = "UPDATE {$this->table} SET";
        $query .= $this->composeQuery($values, ',');

        $query .= is_null($clauses) ?
            " WHERE {$this->primaryKey} = :id" :
            " WHERE " . $this->composeQuery($clauses, 'AND', $prefix);

        $params = is_null($clauses) ?
            array_merge($values, ['id' => $this->{$this->primaryKey}]) :
            array_merge($values, keyprefix($prefix, $clauses));

        $stmt = $this->conn()->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    public function all()
    {
        $stmt = $this->conn()->query("SELECT * FROM {$this->table}");
        $results = $stmt->fetchAll();

        return array_map(fn($result) => $this->mapToModel($result), $results);
    }

    public function insert(array $params)
    {
        $stmt = $this->conn()->prepare($this->composeInsertQuery($params));
        $stmt->execute($params);

        $params[$this->primaryKey] = $this->conn()->lastInsertId();
        return $this->mapToModel($params);
    }

    public function delete()
    {
        if(!$this->_data) throw new QueryException("You must run query first");

        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->conn()->prepare($query);

        $stmt->execute(['id' => $this->{$this->primaryKey}]);
        return $stmt->rowCount();
    }

    public function deleteAll(array $clauses)
    {
        $query = "DELETE FROM {$this->table} WHERE" . $this->composeQuery($clauses, "AND");
        $stmt = $this->conn()->prepare($query);

        $stmt->execute($clauses);
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
