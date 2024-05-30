<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use plugse\server\core\app\entities\Entity;

class Create
{
    private readonly string $tablename;
    private Entity $entity;
    private string $query;

    public function __construct(private readonly PDO $connection)
    {}

    public function setQuery(string $tablename, Entity $entity)
    {
        $this->tablename = $tablename;
        $this->entity = $entity;
        
        $this->query = "
        INSERT INTO {$this->tablename}(
            {$this->getFieldsToCreate($entity)}
        ) VALUES(
            {$this->getFieldValuesToCreate($entity)}
        );";

        return $this;
    }

    public function run(array $subQueries = []): Entity
    {
        $this->connection->beginTransaction();

            $stmtCreate = $this->connection->prepare($this->query);
            $stmtCreate->execute($this->entity->getAttributes());

            $stmtRead = $this->setSubQueries($subQueries);
            $response = $stmtRead->fetchObject(get_class($this->entity));
            
            $this->connection->commit();

            return $response;
    }

    private function setSubQueries(array $subQueries)
    {
        $stmtRead = $this->connection->query("SELECT * FROM {$this->tablename} WHERE id=last_insert_id();");
        foreach ($subQueries as $query){
            $this->connection->query($query);
        }

        return $stmtRead;
    }

    private function getFieldsToCreate(): string
    {
        return implode(
            ', ',
            array_keys($this->entity->getAttributes())
        );
    }

    private function getFieldValuesToCreate(): string
    {
        return ':' . implode(
            ', :',
            array_keys($this->entity->getAttributes())
        );
    }
}
