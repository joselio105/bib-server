<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use plugse\server\core\app\entities\Entity;

class Create
{
    private PDO $connection;
    private string $tablename;
    private Entity $entity;
    private string $query;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function setQuery(string $tablename, Entity $entity)
    {
        $this->query = "
        INSERT INTO {$this->tablename}(
            {$this->getFieldsToCreate($entity)}
        ) VALUES(
            {$this->getFieldValuesToCreate($entity)}
        );";

        return $this;
    }

    public function run(array $subQueries = [])
    {
        $this->connection->beginTransaction();

            $stmtCreate = $this->connection->prepare($this->query);
            $stmtCreate->execute($this->getEntityAtributes());

            $stmtRead = $this->setSubQueries($subQueries);
            $response = $stmtRead->fetchObject();

            $this->connection->commit();

            return $response->id;
    }

    private function setSubQueries(array $subQueries)
    {
        $stmtRead = $this->connection->query('SELECT last_insert_id() id;');
        foreach ($subQueries as $query){
            $this->connection->query($query);
        }

        return $stmtRead;
    }

    private function getEntityAtributes()
    {
        return get_class_vars(get_class($this->entity));
    }

    private function getFieldsToCreate(Entity $entity): string
    {
        return implode(
            ', ',
            $this->getEntityAtributes()
        );
    }

    private function getFieldValuesToCreate(Entity $entity): string
    {
        return ':' . implode(
            ', :',
            get_class_vars(get_class($this->entity))
        );
    }
}
