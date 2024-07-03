<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use Exception;
use PDOStatement;

class Read
{
    private readonly string $tablename;
    private string $query;

    public function __construct(private readonly PDO $connection)
    {}

    public function setQuery(string $tableName, string $whereClauses, string $fields = '*'): Read
    {
        $this->tablename = $tableName;        
        $this->query = "SELECT {$fields} FROM {$this->tablename} WHERE {$whereClauses}";

        return $this;
    }

    public function setQueryCount(string $tableName, string $whereClauses, string $field='*'): Read
    {
        $this->tablename = $tableName;        
        $this->query = "SELECT COUNT({$field}) AS total FROM {$this->tablename} WHERE {$whereClauses}";

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function run(array $values = []): PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($this->query);
            $stmt->execute($values);
            
            return $stmt;
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function fetchOne(PDOStatement $stmt, string $entity)
    {
        return $stmt->fetchObject($entity);
    }

    public function fetchMany(PDOStatement $stmt, string $entity): array
    {
        return $stmt->fetchAll(PDO::FETCH_CLASS, $entity);
    }

    public function fetchCount(PDOStatement $stmt)
    {
        return intval($stmt->fetchObject()->total);
    }
}
