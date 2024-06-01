<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use plugse\server\core\app\entities\Entity;

class Update
{
    
    private readonly string $tablename;
    private Entity $entity;
    private string $query;

    public function __construct(private readonly PDO $connection)
    {}

    public function setQuery(string $tablename, Entity $entity, string $id)
    {
        $this->tablename = $tablename;
        $entity->id = $id;
        $this->entity = $entity;
        
        $this->query = "
        UPDATE {$this->tablename} SET {$this->getFieldsToUpdate($entity)}
        WHERE id=:id;";

        return $this;
    }

    public function run(): Entity
    {
        $this->connection->beginTransaction();

            $stmtUpdate = $this->connection->prepare($this->query);
            $stmtUpdate->execute($this->entity->getAttributes());

            $stmtRead = $this->connection->prepare("SELECT * FROM {$this->tablename} WHERE id=:id;");
            $stmtRead->execute([':id'=>$this->entity->id]);
            $response = $stmtRead->fetchObject(get_class($this->entity));
            
            $this->connection->commit();

            return $response;
    }

    private function getFieldsToUpdate(Entity $entity): string
    {
        $response = [];

        foreach(array_keys($entity->getAttributes()) as $key) {
            array_push($response, "{$key} = :{$key}");
        }

        return implode("\n\t, ", $response);
    }
}
