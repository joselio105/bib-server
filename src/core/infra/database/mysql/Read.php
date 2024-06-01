<?php

namespace plugse\server\core\infra\database\mysql;

use Exception;
use PDO;
use PDOStatement;
use plugse\server\core\app\entities\Entity;

class Read
{
    private readonly string $tablename;
    private string $query;

    public function __construct(private readonly PDO $connection, private readonly string $entity)
    {}

    public function setQuery(string $tableName, string $whereClauses, string $fields = '*'): Read
    {
        $this->tablename = $tableName;        
        $this->query = "SELECT {$fields} FROM {$this->tablename} WHERE {$whereClauses}";

        return $this;
    }

    public function run(array $values = []): PDOStatement
    {
        $stmt = $this->connection->prepare($this->query);
        if(!$stmt->execute($values)){
            http_response_code(500);
            throw new Exception("Falha ao executar a query '{$this->query}'");            
        }
        
        return $stmt;

    }

    public function fetchOne(PDOStatement $stmt)
    {
        return $stmt->fetchObject($this->entity);
    }

    public function fetchMany(PDOStatement $stmt): array
    {
        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->entity);
    }
}
