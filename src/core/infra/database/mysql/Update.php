<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use Exception;
use plugse\server\core\app\entities\Entity;

class Update
{
    private PDO $connection;
    private string $tablename;
    private array $values;
    private string $query;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function setQuery(string $tablename, array $values, string $id)
    {
        $this->tablename = $tablename;
        $values['id'] = $id;
        $this->values = $values;

        $this->query = "
        UPDATE {$this->tablename} SET {$this->getFieldsToUpdate()}
        WHERE id=:id;";

        return $this;
    }

    public function run(string $queryResponse, string $entity): Entity
    {
        $this->connection->beginTransaction();

        $stmtUpdate = $this->connection->prepare($this->query);
        $updated = $stmtUpdate->execute($this->values);

        $stmtRead = $this->connection->prepare($queryResponse . 'id=:id;');

        $stmtRead->execute([':id' => $this->values['id']]);
        $response = $stmtRead->fetchObject($entity);

        $this->connection->commit();

        if (!$updated) {
            throw new Exception($stmtUpdate->errorInfo()[2]);
        }

        return $response;
    }

    private function getFieldsToUpdate(): string
    {
        $response = [];

        foreach (array_keys($this->values) as $key) {
            array_push($response, "{$key} = :{$key}");
        }

        return implode("\n\t, ", $response);
    }
}
