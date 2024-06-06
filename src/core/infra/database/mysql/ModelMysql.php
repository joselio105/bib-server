<?php

namespace plugse\server\core\infra\database\mysql;

use Exception;
use PDO;
use plugse\server\core\app\entities\Entity;
use plugse\server\core\helpers\File;
use plugse\server\core\infra\database\Model;

abstract class ModelMysql implements Model
{
    private array $dbSettings;
    private PDO $connection;
    protected string $tableName;
    // protected QueryBuilder $querySelectMany;
    // private array|Entity $found;
    // public array $relations;
    protected array $indexUniques;
    protected array $indexForeigns;
    protected string $entity;
    protected string $mapper;

    public function __construct()
    {
        $this->dbSettings = File::getProperty(SETTINGS_FILE, 'db');
        $this->setTableName();
        // $this->setQuerySelectMany();
        $this->connection = Connection::getInstance($this->dbSettings);
        $this->setEntity();
        // $this->setMapper();
        // $this->setRelations();
        $this->setIndexUniques();
        $this->setIndexForeigns();
    }

    abstract protected function setTableName(): void;

    abstract protected function setEntity(): void;

    protected function setIndexUniques()
    {
        $this->indexUniques = [];
    }

    protected function setIndexForeigns()
    {
        $this->indexForeigns = [];
    }

    public function getTableName(): string
    {
        $table_prefix = $this->dbSettings['prefix'];;

        return "{$table_prefix}{$this->tableName}";
    }

    public function clearTable()
    {
        $this->connection->query("TRUNCATE {$this->getTableName()}");
        
    }

    public function create(Entity $entity): Entity
    {
        try {
            $create = new Create($this->connection);
            $response = $create->setQuery($this->getTableName(), $entity)->run();
            
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(string $id, Entity $entity): Entity
    {
        try {
            $update = new Update($this->connection);
            $response = $update->setQuery($this->getTableName(), $entity, $id)->run();
            
            return $response;
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findMany(string $whereClauses, array $values, string $fields = '*'): array
    {
        try {
            $read = new Read($this->connection, $this->entity);
            $stmt = $read->setQuery($this->getTableName(), $whereClauses, $fields)->run($values);

            $response = $read->fetchMany($stmt);
            
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findOne(string $whereClauses, array $values, string $fields = '*') : Entity
    {
        try {
            $read = new Read($this->connection, $this->entity);
            $stmt = $read->setQuery($this->getTableName(), $whereClauses, $fields)->run($values);

            return $read->fetchOne($stmt);
        } catch (\Throwable $th) {
            throw $th;
        }   
    }
}
