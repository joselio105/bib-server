<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use plugse\server\core\app\entities\Entity;

class Create
{
    private readonly string $tablename;
    private Entity $entity;
    private ?string $foreignKey;
    private string $query;

    public function __construct()
    {}

    public function setQuery(string $tablename, Entity $entity, $foreignKey=null)
    {
        $this->tablename = $tablename;
        $this->entity = $entity;
        $this->foreignKey = $foreignKey;
        
        $this->query = "
        INSERT INTO {$this->tablename}(
            {$this->getFieldsToCreate($entity)}
        ) VALUES(
            {$this->getFieldValuesToCreate($entity)}
        );";

        return $this;
    }

    public function run(PDO $connection, array $subQueries = []): Entity
    {
        $connection->beginTransaction();

            $stmtCreate = $connection->prepare($this->query);
            $stmtCreate->execute($this->entity->getAttributes());

            $stmtRead = $this->setSubQueries($connection, $subQueries);
            $response = $stmtRead->fetchObject(get_class($this->entity));
            
            $connection->commit();

            return $response;
    }

    public function getQuery()
    {
        return $this->query;
    }

    private function setSubQueries(PDO $connection, array $subQueries)
    {
        $connection->query("SET @last_id = LAST_INSERT_ID();");
        foreach ($subQueries as $query){
            $connection->query($query);
        }
        $stmtRead = $connection->query("SELECT * FROM {$this->tablename} WHERE id=@last_id;");

        return $stmtRead;
    }

    private function getFieldsToCreate(): string
    {
        $fieds = implode(
            ', ',
            array_keys($this->entity->getAttributes())
        );

        return is_null($this->foreignKey) ? $fieds : "{$this->foreignKey}, " . $fieds;
    }

    private function getFieldValuesToCreate(): string
    {
        $valuesToPrepare = ':' . implode(
            ', :',
            array_keys($this->entity->getAttributes())
        );

        $values = ['@last_id'];
        foreach ($this->entity->getAttributes() as $value) {
            array_push($values, is_string($value) ? "\"{$value}\"" : $value);
        }

        return is_null($this->foreignKey) 
            ? $valuesToPrepare 
            : implode(', ', $values);
    }
}
